<?php

namespace Mapparte\Stripe;

/**
 * Una classe helper per la creazione e la generazione
 * dei dati principali di un connected account (Standard)
 *
 * Qui la documentazione per vedere come funziona
 * l'onboarding di account standard
 * https://stripe.com/docs/connect/standard-accounts
 *
 *
 * Responsabilità
 *
 * 1. CREARE UN NUOVO "CONNECTED ACCOUNT"
 *    e quindi ottenere l'id Stripe del locatore ("connected account")
 *    utile in ogni operazione relativa al quello specifico locatore
 *    da salvare tra le info del locatore
 *
 * 2. CREARE UN LINK PER L'ONBOARDING SU STRIPE
 *    una volta generato un nuovo "connected account" esso è vuoto
 *    e l'utente locatore potrà atterrare su un link dove compilare
 *    il proprio profilo per attivare il proprio connected account
 *
 */
class ConnectedAccount extends Call {


	/**
	 * Crea un connected account vuoto sulla piattaforma
	 * e ne restituisce l'id
	 *
	 * @return integer l'id del nuovo connected account
	 */
	public function createConnectedAccount() {

		// crea un nuovo account
		$new_account = json_decode( $this->http->post(

			'https://api.stripe.com/v1/accounts', [ 'type' => 'standard' ], [], $this->sk
		) );

		return $new_account->id;
	}


	/**
	 * Genera un link per consentire all'utente locatore di effettuare l'onboarding
	 * come connected account su stripe
	 *
	 * @param String $return_url - La url cui sarà reindirizzato l'utente alla fine dell'onboarding
	 * @param String $refresh_url - L'utente sarà inviato qui se (prendo dalla doc dell'API)
	 *
	 *                               * The link is expired (a few minutes went by since the link was created)
	 *                               * The link was already visited (the user refreshed the page or clicked back or forward in the browser)
	 *                               * Your platform is no longer able to access the account
	 *                               * The account has been rejected
	 *
	 *                               Questa pagina dovrebbe invitare l'utente a riprovare generando un nuovo link
	 *
	 *
	 * @return JSON  il formato è simile al seguente
	 *               {
	 *                  "object"     : "account_link",
	 *                  "created"    : 1616231504,
	 *                  "expires_at" : 1616231804,
	 *                  "url"        : "https://connect.stripe.com/setup/s/8EsaaeKzCTnO"
	 *                }
	 */
	public function getOnboardingLink( $account_id, $refresh_url, $return_url ) {

		// Genera il link per l'onboarding
		$result = $this->http->post(
			'https://api.stripe.com/v1/account_links',
			[
				'account'     => $account_id,
				'type'        => 'account_onboarding',
				'refresh_url' => $refresh_url,
				'return_url'  => $return_url
			],
			[], $this->sk
		);

		return $result;
	}
}

// mytest
// $test = new ConnectedAccount('sk_test_BySKgwPMwGCWIF9Dxo2mZoXh00QtW9ycpP');
// $account_id = $test->createConnectedAccount();
// $link = json_decode($test-> getOnboardingLink($account_id, 'https://qualcosa_da_rifare/', 'https://account_creato/')) -> url;