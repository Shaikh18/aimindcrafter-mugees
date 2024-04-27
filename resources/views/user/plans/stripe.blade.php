<!DOCTYPE html>
<html>

<head>
    <title>Stripe Payment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if (!empty($_SERVER['HTTPS']))
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-md-5 col-sm-12" style="margin-top: 10rem;">
                <button id="checkout-button" style="display: none;"></button>
                <div class="d-flex justify-content-center">
                    <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
                    </div>
                </div>                
                <br>
                <p style="margin: auto;" class="font-weight-bold fs-14">{{ __('Your payment is being processed, do not close the tab. . .') }}</p>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        // Create an instance of the Stripe object with your publishable API key
        var stripe = Stripe('{{ config('services.stripe.api_key') }}');
        var checkoutButton = document.getElementById('checkout-button');

        checkoutButton.addEventListener('click', function() {
            // Create a new Checkout Session using the server-side endpoint you
            // created in step 3.
            fetch('{{ route('user.payments.stripe.process') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(session) {
                    return stripe.redirectToCheckout({
                        sessionId: session.id
                    });
                })
                .then(function(result) {
                    console.log(result);
                    // If `redirectToCheckout` fails due to a browser or network
                    // error, you should display the localized error message to your
                    // customer using `error.message`.
                    if (result.error) {
                        alert(result.error.message);
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                });
        });

        document.getElementById("checkout-button").click();
    </script>
</body>

</html>