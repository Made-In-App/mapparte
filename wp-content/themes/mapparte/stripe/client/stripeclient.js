/// Inizializziamo Stripe.js con 
///
/// - public-key della piattaforma
/// - id del locatore (connected account) fondamentalissimo!
///

$connected_account = ( stripe_vars.connected_account_id ) ? {stripeAccount: stripe_vars.connected_account_id} : {};
var stripe = Stripe(stripe_vars.public_key, $connected_account );

// Disable the button until we have Stripe set up on the page
document.querySelector("button").disabled = true;

function prepareRequestBody(json) {
    return Object.keys(json).map(function (key) {
        return key + '=' + encodeURIComponent(json[key]);
    }).join('&');
}

var data = stripe_vars;
data.action = 'secret';

fetch(stripe_vars.ajaxurl, {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: prepareRequestBody(data)
})
    .then(function (result) {
        return result.json();
    })
    .then(function (data) {
        var elements = stripe.elements();

        var style = {
            base: {
                color: "#32325d",
                fontFamily: 'Arial, sans-serif',
                fontSmoothing: "antialiased",
                fontSize: "16px",
                "::placeholder": {color: "#32325d"}
            },
            invalid: {
                fontFamily: 'Arial, sans-serif',
                color: "#fa755a",
                iconColor: "#fa755a"
            }
        };

        var card = elements.create("card", {style: style});

        // Stripe injects an iframe into the DOM
        card.mount("#card-element");

        card.on("change", function (event) {
            // Disable the Pay button if there are no card details in the Element
            document.querySelector("button").disabled = event.empty;
            document.querySelector("#card-error").textContent = event.error ? event.error.message : "";
        });

        var form = document.getElementById("payment-form");

        form.addEventListener("submit", function (event) {
            event.preventDefault();

            var form = event.target;

            var info = {
                name: form.name.value,
                email: form.email.value,
                phone: form.phone.value,
                address: form.address.value
            }

            // Complete payment when the submit button is clicked
            payWithCard(stripe, card, data.client_secret, info);
        });
    });


/// Chiama stripe.confirmCardPayment
/// All'interno aggiunto anche un esempio di come prendere i billing_details

// If the card requires authentication Stripe shows a pop-up modal to
// prompt the user to enter authentication details without leaving your page.
var payWithCard = function (stripe, card, clientSecret, info) {
    loading(true);
    stripe
        .confirmCardPayment(clientSecret, {
            payment_method: {
                card: card,  // la card che abbiamo istanziato
                billing_details: {
                    name: info.name,
                    email: info.email,
                    phone: info.phone,
                    address: {
                      city: info.address,
                    }
                }
            }
        })
        .then(function (result) {
            if (result.error) {
                // Show error to your customer
                showError(result.error.message);
            } else {
                // The payment succeeded!
                orderComplete(result.paymentIntent.id);
            }
        });
};

/* ------- UI helpers ------- */

// Shows a success message when the payment is complete
var orderComplete = function (paymentIntentId) {
    loading(false);
    document.querySelector(".result-message").classList.remove("hidden");
    document.querySelector("button").disabled = true;
    setTimeout(function () {
        window.location.href = window.location.href + "&paymentIntentId=" + paymentIntentId + "&payment=success&nonce=" + stripe_vars.nonce;
    }, 100);
};

// Show the customer the error from Stripe if their card fails to charge
var showError = function (errorMsgText) {
    loading(false);
    var errorMsg = document.querySelector("#card-error");
    errorMsg.textContent = errorMsgText;
    setTimeout(function () {
        errorMsg.textContent = "";
    }, 4000);
};

// Show a spinner on payment submission
var loading = function (isLoading) {
    if (isLoading) {
        // Disable the button and show a spinner
        document.querySelector("button").disabled = true;
        document.querySelector("#spinner").classList.remove("hidden");
        document.querySelector("#button-text").classList.add("hidden");
    } else {
        document.querySelector("button").disabled = false;
        document.querySelector("#spinner").classList.add("hidden");
        document.querySelector("#button-text").classList.remove("hidden");
    }
};




