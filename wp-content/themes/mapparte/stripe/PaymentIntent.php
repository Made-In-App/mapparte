<?php

namespace Mapparte\Stripe;

require('Call.php');

/**
 * Consente di effettuare un pagamento
 *
 * Che consiste nel creare un "Payment Intent"
 * e prende in ingresso:
 *
 * - la chiave segreta di stripe
 * - l'ID del locatore (connected account)
 *
 * Il Payment Intent invece ha bisogno di sapere
 * - la somma da pagare (es. 10,30 )
 * - la percentuale da riconoscere alla piattaforma (valore tra 0 e 100)
 *
 * //https://stripe.com/docs/connect/enable-payment-acceptance-guide
 */
class PaymentIntent extends Call {

    private $connected_account_id;

    /**
     * @param string $sk                     secret-key della piattaforma
     * @param string $connected_account_id   id del connnected account (negozio / locatore)
     */
    function __construct($sk, $connected_account_id){

        parent::__construct($sk);

        $this->connected_account_id = $connected_account_id;
    }


    /**
     * Crea un Payment Intent
     *
     * @param string  $amount       - costo totale del prodotto con  i decimali separati da virgola ','
     * @param integer $platform_fee - [0-100] percentuale del pagamento da riconoscere alla piattaforma
     *
     * @return void
     */
    function createPaymentIntent(string $amount, int $platform_fee , $info = null ) {

    	$info = json_decode( $info );

        $intAmount   = $this->getAmount($amount);
        $intFee      = $this->getFee($intAmount, $platform_fee);
        $shipping    = $this->getShipping($info);

        $args = [
            'payment_method_types[]' => 'card',
            'amount' => $intAmount,
            'currency' => 'eur',
            'description' => $this->getDescription($info),
        ];

	    if($this->connected_account_id) $args['application_fee_amount'] = $intFee;

        if($shipping != null) $args['shipping'] = $shipping;

        $payment_intent = $this->http->post(

            'https://api.stripe.com/v1/payment_intents',$args,
            [
                'Stripe-Account' => $this->connected_account_id
            ],
            $this->sk
        );

        return $payment_intent;
    }

    /**
     * ritorna il valore intero in centesimi a partire
     * dalla stringa in ingresso che rappresenta la somma
     * (con virgola o senza)
     *
     * es. 1,10 € = 110
     *
     * @param string $amount
     *
     * @return integer
     */
    private function getAmount(string $amount) {

        // rimuove i punti
        $amount = str_replace ( '.', '', $amount);
        $amount = explode ( ',' , $amount);
        $cents  = isset($amount[1]) ? str_pad($amount[1],2,"0") : "00";

        return (int) ( $amount[0] . $cents );
    }

    /**
     * ritorna il valore intero in centesimi della fee (per eccesso)
     * riconosciuta alla piattaforma, calcolata a partire
     * dal valore intero in centesimi e la percentuale da applicare
     *
     * @param amount_in_cents:integer  - valore in centestimi
     * @param fee_perc:integer         - numero da 0 a 100 che indica la percentuale da applicare
     *
     * @return integer
     */
    private function getFee( $amount_in_cents, $fee_perc) {

        return ($fee_perc == 0) ? 0 : ceil ( $amount_in_cents * $fee_perc / 100  );
    }

    private function getDescription($info) {

        $description = 'Mapparte';

        if(isset($info->locatore)) $description .= ' - ' . $info->locatore;
        if(isset($info->ordine))   $description .= ' - ' . $info->ordine;
	    if(isset($info->invoice))  $description .= ' - ' . $info->invoice;

        return $description;
    }

    private function getShipping($info) {

        $shipping = null;

        if(isset($info->shipping)) $shipping = $info->shipping;

        return $shipping;
    }

}
