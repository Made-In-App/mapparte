<?php

namespace Mapparte\Stripe;

class Utils {

	public static function stripe_onboard_button( $refresh_url, $return_url ) {

		$stripe_connect = new ConnectedAccount( get_field( 'stripe_secret_key', 'option' ) );

		$stripe_connected_account_id = get_user_meta( get_current_user_id(), 'stripe_connected_account', true );

		if ( ! $stripe_connected_account_id ) {
			$label                       = __("Connetti a Stripe",'mapparte');
			$stripe_connected_account_id = $stripe_connect->createConnectedAccount();
			add_user_meta( get_current_user_id(), 'stripe_connected_account', $stripe_connected_account_id, true );
		} else {
			$label = __("Aggiorna le informazioni di verifica su stripe",'mapparte');
		}

		$onboarding     = json_decode( $stripe_connect->getOnboardingLink( $stripe_connected_account_id, $refresh_url, $return_url ) );
		$onboarding_url = ( isset ( $onboarding->url ) ) ? $onboarding->url : '#';

		?>
        <a href="<?php echo $onboarding_url; ?>" type="button"
           class="btn btn-primary"><?php echo esc_html( $label ); ?></a>
		<?php
	}

	public static function stripe_checkout_form( $args, $post ) {
		// questi messaggi possono essere definiti qui o altrove
		$success_title   = __("Pagamento effettuato",'mapparte');
		$success_message = __("Grazie per aver completato il pagamento.",'mapparte');

		$host_id = get_post_field( 'post_author', $args['spaceId'] );

		// Chiave pubblica della piattaforma
		$stripe_public_key = get_field( 'stripe_public_key', 'option' );

		// il connected_account_id è una proprietà del locatore che ha già completato l'onboarding e qui va recuperata
		$stripe_connected_account_id = get_user_meta( $host_id, 'stripe_connected_account', true );

		// Questa è la percentuale da applicare per la fee
		$fee = ( get_field( 'commissioni_stripe', 'option' ) ) ? get_field( 'commissioni_stripe', 'option' ) : 0;

		if ( $stripe_connected_account_id ) :

			$amount = str_replace( '.', ',', $args['finalPrice'] );

			$info = [
				'locatore' => $args['spaceTitle'],
				'ordine'   => sprintf( 'Ordine n. %d', $post->ID ),
			];

			// fissiamo qui alcune variabili che trasferiremo nel contesto dello script stripeclient.js

			$ajaxurl = admin_url( 'admin-ajax.php' );
			$nonce   = wp_create_nonce( 'stripe-nonce-seed' );

			wp_localize_script( 'stripeclient', 'stripe_vars', [
				'ajaxurl'              => $ajaxurl,
				'nonce'                => $nonce,
				'amount'               => $amount,
				'platform_fee'         => (int) $fee,
				'public_key'           => $stripe_public_key,
				'connected_account_id' => $stripe_connected_account_id,
				'info'                 => json_encode( $info ),
				'booking_id'           => $post->ID,
			] );

			?>

            <form id="payment-form" method="post" action="<?php echo get_the_permalink() ?>"
                  class="row align-items-center">
                <div class="col-md-8">
                    <div class="mb-4 w-75">
                        <label for="name" class="form-label"><?php echo __('Nome del titolare della carta','mapparte');?></label>
                        <input id="name" class="field form-control" type="text" placeholder="Name" required=""
                               autocomplete="name">
                    </div>
                    <div class="mb-4 w-75">
                        <label for="email" class="form-label"><?php echo __('Email','mapparte');?></label>
                        <input id="email" class="field form-control" type="email" placeholder="Email"
                               required="" autocomplete="email">
                    </div>
                    <div class="mb-4 w-75">
                        <label for="email" class="form-label"><?php echo __('Telefono','mapparte');?></label>
                        <input id="phone" class="field form-control" type="tel" placeholder="Telefono"
                               required="" autocomplete="tel">
                    </div>
                    <div class="mb-4 w-75">
                        <label for="address" class="form-label"><?php echo __('Indirizzo','mapparte');?></label>
                        <input id="address" class="field form-control" type="text" placeholder="Indirizzo"
                               required="" autocomplete="address">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="mb-4 w-75">
                        <label for="name" class="form-label"><?php echo __('Numero della carta','mapparte');?></label>
                        <div id="card-element"><!--Stripe.js injects the Card Element--></div>

                        <p id="card-error" role="alert"></p>
                        <div class="result-message hidden">
                            <h2><?= $success_title ?></h2>
                            <p><?= $success_message ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 status-note mt-0"><?php echo __('I tuoi dati sono sicuri. Leggi la nostra','mapparte');?>
                    <a href="/privacy-policy/" target="_blank">privacy policy</a>
                </div>
                <div class="col-md-8">
                    <div class="row mx-0 px-0 align-items-center submit-btns">
                        <div class="col-9 ps-0">
                            <button type="submit" id="submit" class="btn btn-outline-primary">
                                <div class="spinner hidden" id="spinner"></div>
                                <span id="button-text"><?php echo __("CONFERMA E PAGA LA PRENOTAZIONE…",'mapparte');?></span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
		<?php endif;
	}

	/**
	 * Checkout Stripe per acquisto sponsorizzazione (pagina dettaglio-sponsorizzazione).
	 */
	public static function stripe_sponsorship_form( $id = '', $plan = [] ) {

		if ( $id && ! empty( $plan ) ) {
			$success_title   = __( 'Pagamento effettuato', 'mapparte' );
			$success_message = __( 'Grazie per aver completato il pagamento.', 'mapparte' );

			$stripe_public_key = get_field( 'stripe_public_key', 'option' );

			if ( $stripe_public_key ) :

				$info = [
					'ordine' => sprintf( '%s - %s - ID space: %d', strtoupper( $plan['name'] ), get_the_title( $id ), $id ),
				];

				$ajaxurl = admin_url( 'admin-ajax.php' );
				$nonce   = wp_create_nonce( 'stripe-nonce-seed' );

				wp_localize_script(
					'stripeclient',
					'stripe_vars',
					[
						'ajaxurl'      => $ajaxurl,
						'nonce'        => $nonce,
						'amount'       => $plan['amount'],
						'platform_fee' => 0,
						'public_key'   => $stripe_public_key,
						'info'         => wp_json_encode( $info ),
					]
				);

				if ( ! $plan['amount'] ) {
					return;
				}

				?>

                <form id="payment-form" method="post" action="<?php echo esc_url( get_the_permalink() ); ?>"
                      class="row align-items-center">
                    <div class="col-md-8">
                        <div class="mb-4 w-75">
                            <label for="name" class="form-label"><?php echo esc_html__( 'Nome del titolare della carta', 'mapparte' ); ?></label>
                            <input id="name" class="field form-control" type="text" placeholder="Name" required=""
                                   autocomplete="name">
                        </div>
                        <div class="mb-4 w-75">
                            <label for="email" class="form-label"><?php echo esc_html__( 'Email', 'mapparte' ); ?></label>
                            <input id="email" class="field form-control" type="email" placeholder="Email"
                                   required="" autocomplete="email">
                        </div>
                        <div class="mb-4 w-75">
                            <label for="phone" class="form-label"><?php echo esc_html__( 'Telefono', 'mapparte' ); ?></label>
                            <input id="phone" class="field form-control" type="tel" placeholder="Telefono"
                                   required="" autocomplete="tel">
                        </div>
                        <div class="mb-4 w-75">
                            <label for="address" class="form-label"><?php echo esc_html__( 'Indirizzo', 'mapparte' ); ?></label>
                            <input id="address" class="field form-control" type="text" placeholder="Indirizzo"
                                   required="" autocomplete="address">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-4 w-75">
                            <label for="card-wrap" class="form-label"><?php echo esc_html__( 'Numero della carta', 'mapparte' ); ?></label>
                            <div id="card-element"></div>

                            <p id="card-error" role="alert"></p>
                            <div class="result-message hidden">
                                <h2><?php echo esc_html( $success_title ); ?></h2>
                                <p><?php echo esc_html( $success_message ); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 status-note mt-0"><?php echo esc_html__( 'I tuoi dati sono sicuri. Leggi la nostra', 'mapparte' ); ?>
                        <a href="/privacy-policy/" target="_blank">privacy policy</a>
                    </div>
                    <div class="col-md-8">
                        <div class="row mx-0 px-0 align-items-center submit-btns">
                            <div class="col-9 ps-0">
                                <button type="submit" id="submit" class="btn btn-outline-primary">
                                    <div class="spinner hidden" id="spinner"></div>
                                    <span id="button-text"><?php echo esc_html__( 'PAGA LA SPONSORIZZAZIONE…', 'mapparte' ); ?></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
				<?php
			endif;
		}
	}
}