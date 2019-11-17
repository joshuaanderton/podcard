@extends('layouts.editing')

@section('content')

    <!-- Load Stripe.js on your website. -->
    <script src="https://js.stripe.com/v3"></script>

    @if(isset($_GET['success']))
    @endif

    @if(isset($_GET['error']))
    @endif

    <div id="editing">
        <div class="bg-gray-300 flex relative pt-48 pb-32 md:py-32" style="background-image:url(/images/bg.jpg);background-size:cover">
            @include('nav', ['title' => 'Podcast Editing'])

            <div style="background: linear-gradient(0deg, rgba(255,255,255,0.7) 0%, rgba(255,255,255,1) 100%);" class="h-full w-full top-0 left-0 absolute"></div>

            <div class="container m-auto px-4 py-32 md:py-64 font-sans">
                <div class="max-w-full relative fade-up">
                    <h1 class="font-bold text-black text-4xl sm:text-5xl md:text-6xl -mt-5">Quality podcast editing</h1>
                    <div class="text-xl sm:text-2xl text-black mb-12">Focus on the content, we'll take care of the rest.</div>
                    <a class="inline-flex text-white bg-black hover:text-white py-2 px-4 rounded align-items-center font-bold" href="#pricing">View Packages</a>
                    <a onclick="Beacon('open')" class="font-bold border-b border-black text-black text-lg ml-6" href="javascript:void(0);">Get in touch</a>
                </div>
            </div>

            <div class="absolute bottom-0 left-0 w-full">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100%" height="96px" viewBox="0 0 100 100" version="1.1" preserveAspectRatio="none" class="injected-svg" fill="#fff" data-src="assets/img/dividers/divider-2.svg">
                    <path d="M0,0 C16.6666667,66 33.3333333,99 50,99 C66.6666667,99 83.3333333,66 100,0 L100,100 L0,100 L0,0 Z"></path>
                </svg>
            </div>
        </div>

        <div id="pricing" class="flex">
            <div class="container m-auto px-4 py-32 md:py-48 font-sans">

                <h2 class="font-bold text-black text-4xl sm:text-5xl md:text-6xl -mt-5">Pricing</h2>
                <div class="text-black text-xl sm:text-2xl mb-12">Choose the price that works best for your project.</div>

                <div class="text-red text-xl sm:text-2xl" id="error-message"></div>

                <div class="flex flex-col md:flex-row flex-wrap mb-12">
                    <button role="link" id="checkout-button-sku_GATyv2jj8o6ujM" class="focus:outline-none flex flex-col mb-5 text-black bg-gray-200 p-6 rounded flex-1 md:mr-5">
                        <h3 class="text-2xl"><span class="font-bold">1</span> ep. One time.</h3>
                        <div class="text-5xl font-bold mb-12">$149</div>
                        <div class="mt-auto">
                            <span class="inline-flex text-white bg-black hover:text-white py-2 px-4 rounded align-items-center font-bold" href="#pricing">Get Started</span>
                        </div>
                    </button>

                    <button role="link" id="checkout-button-plan_GATurQyxQbOy6z" class="focus:outline-none flex flex-col mb-5 text-black bg-gray-200 p-6 rounded flex-1 md:mr-5">
                        <h3 class="text-2xl"><span class="font-bold">2</span> ep. per month</h3>
                        <div class="text-5xl font-bold mb-12">$249/mo</div>
                        <div class="mt-auto">
                            <span class="inline-flex text-white bg-black hover:text-white py-2 px-4 rounded align-items-center font-bold" href="#pricing">Get Started</span>
                        </div>
                    </button>

                    <button role="link" id="checkout-button-plan_GATup1PirYutCo" class="focus:outline-none flex flex-col mb-5 text-black bg-gray-200 p-6 rounded flex-1 lg:mr-5">
                        <h3 class="text-2xl"><span class="font-bold">4</span> ep. per month</h3>
                        <div class="text-5xl font-bold mb-12">$349/mo</div>
                        <div class="mt-auto">
                            <span class="inline-flex text-white bg-black hover:text-white py-2 px-4 rounded align-items-center font-bold" href="#pricing">Get Started</span>
                        </div>
                    </button>

                    <button role="link" id="checkout-button-plan_GATvfhb4zbhCAs" class="focus:outline-none flex flex-col mb-5 text-black bg-gray-200 p-6 rounded flex-1">
                        <h3 class="text-2xl"><span class="font-bold">8</span> ep. per month</h3>
                        <div class="text-5xl font-bold mb-12">$599/mo</div>
                        <div class="mt-auto">
                            <span class="inline-flex text-white bg-black hover:text-white py-2 px-4 rounded align-items-center font-bold" href="#pricing">Get Started</span>
                        </div>
                    </button>
                </div>

                <div class="flex flex-col lg:flex-row">
                    <div class="mb-12 lg:mb-0 lg:w-1/2">
                        <h3 class="font-bold text-black text-3xl mb-6">Each plan includes...</h3>
                        <ul class="text-black md:text-xl">
                            <li class="mb-3"><span class="mr-2">•</span> Mixing in <strong>intro and outro</strong> music</li>
                            <li class="mb-3"><span class="mr-2">•</span> <strong>Compression, EQ, and ❤️</strong> to make your audio really stand out</li>
                            <li class="mb-3"><span class="mr-2">•</span> Removal of <strong>awkward pauses, umms, awws, sneezes etc.</strong></li>
                            <li class="mb-3"><span class="mr-2">•</span> Writing of <strong>show notes</strong> with links</li>
                            <li class="mb-3"><span class="mr-2">•</span> Full episode <strong>transcript</strong></li>
                            <li class="mb-3"><span class="mr-2">•</span> Publishing each episode to your <strong>hosting platform</strong></li>
                        </ul>
                    </div>
                    <a href="javascript:void(0);" onclick="Beacon('open')" class="md:w-1/2 flex flex-col text-black border-2 border-black p-6 rounded flex-1">
                        <div class="font-bold text-black text-3xl mb-6">Have multiple podcasts?</div>
                        <div class="text-lg mb-12 max-w-sm">We'll discount the price if you're needing more than one podcast edited ongoing.</div>
                        <div class="mt-auto">
                            <span class="inline-flex text-white bg-black hover:text-white py-2 px-4 rounded align-items-center font-bold" href="javascript:void(0);">Send us a message</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-black">
            <div class="container m-auto px-4 py-48 font-sans">
                <img class="rounded-full mb-12" height="100" width="100" src="https://pbs.twimg.com/profile_images/1124918005719113733/nhF5z17L_400x400.png"/>
                <h2 class="font-bold text-white text-4xl sm:text-5xl md:text-6xl -mt-5">Hey! I'm Josh.</h2>
                <div class="text-gray-500 text-xl sm:text-2xl mb-12">I run <span class="font-semibold">Podcard</span>. Let me know if you have any <span class="cursor-ponclick="Beacon('open')" ointer underline">questions</span> 👍 ☺️</div>
                <a href="javascript:void(0);" onclick="Beacon('open')" class="inline-flex text-black bg-white py-2 px-4 rounded align-items-center font-bold">Send me a message</a>
            </div>
        </div>
    </div>

    <script>
    (function() {
      var stripe = Stripe('pk_live_FbHIOPfdYcHv7CNtEXfDKBxE00FT1YGRHZ');

      var checkoutButton = document.getElementById('checkout-button-sku_GATyv2jj8o6ujM');
      checkoutButton.addEventListener('click', function () {
        // When the customer clicks on the button, redirect
        // them to Checkout.
        stripe.redirectToCheckout({
          items: [{sku: 'sku_GATyv2jj8o6ujM', quantity: 1}],

          // Do not rely on the redirect to the successUrl for fulfilling
          // purchases, customers may not always reach the success_url after
          // a successful payment.
          // Instead use one of the strategies described in
          // https://stripe.com/docs/payments/checkout/fulfillment
          successUrl: 'https://editing.{{ env("SESSION_DOMAIN") }}?success',
          cancelUrl: 'https://editing.{{ env("SESSION_DOMAIN") }}?canceled',
        })
        .then(function (result) {
          if (result.error) {
            // If `redirectToCheckout` fails due to a browser or network
            // error, display the localized error message to your customer.
            var displayError = document.getElementById('error-message');
            displayError.textContent = result.error.message;
          }
        });
      });
    })();
    </script>

    <script>
    (function() {
      var stripe = Stripe('pk_live_FbHIOPfdYcHv7CNtEXfDKBxE00FT1YGRHZ');

      var checkoutButton = document.getElementById('checkout-button-plan_GATurQyxQbOy6z');
      checkoutButton.addEventListener('click', function () {
        // When the customer clicks on the button, redirect
        // them to Checkout.
        stripe.redirectToCheckout({
          items: [{plan: 'plan_GATurQyxQbOy6z', quantity: 1}],

          // Do not rely on the redirect to the successUrl for fulfilling
          // purchases, customers may not always reach the success_url after
          // a successful payment.
          // Instead use one of the strategies described in
          // https://stripe.com/docs/payments/checkout/fulfillment
          successUrl: 'https://editing.{{ env("SESSION_DOMAIN") }}?success',
          cancelUrl: 'https://editing.{{ env("SESSION_DOMAIN") }}?canceled',
        })
        .then(function (result) {
          if (result.error) {
            // If `redirectToCheckout` fails due to a browser or network
            // error, display the localized error message to your customer.
            var displayError = document.getElementById('error-message');
            displayError.textContent = result.error.message;
          }
        });
      });
    })();
    </script>

    <script>
    (function() {
      var stripe = Stripe('pk_live_FbHIOPfdYcHv7CNtEXfDKBxE00FT1YGRHZ');

      var checkoutButton = document.getElementById('checkout-button-plan_GATup1PirYutCo');
      checkoutButton.addEventListener('click', function () {
        // When the customer clicks on the button, redirect
        // them to Checkout.
        stripe.redirectToCheckout({
          items: [{plan: 'plan_GATup1PirYutCo', quantity: 1}],

          // Do not rely on the redirect to the successUrl for fulfilling
          // purchases, customers may not always reach the success_url after
          // a successful payment.
          // Instead use one of the strategies described in
          // https://stripe.com/docs/payments/checkout/fulfillment
          successUrl: 'https://editing.{{ env("SESSION_DOMAIN") }}?success',
          cancelUrl: 'https://editing.{{ env("SESSION_DOMAIN") }}?canceled',
        })
        .then(function (result) {
          if (result.error) {
            // If `redirectToCheckout` fails due to a browser or network
            // error, display the localized error message to your customer.
            var displayError = document.getElementById('error-message');
            displayError.textContent = result.error.message;
          }
        });
      });
    })();
    </script>

    <script>
    (function() {
      var stripe = Stripe('pk_live_FbHIOPfdYcHv7CNtEXfDKBxE00FT1YGRHZ');

      var checkoutButton = document.getElementById('checkout-button-plan_GATvfhb4zbhCAs');
      checkoutButton.addEventListener('click', function () {
        // When the customer clicks on the button, redirect
        // them to Checkout.
        stripe.redirectToCheckout({
          items: [{plan: 'plan_GATvfhb4zbhCAs', quantity: 1}],

          // Do not rely on the redirect to the successUrl for fulfilling
          // purchases, customers may not always reach the success_url after
          // a successful payment.
          // Instead use one of the strategies described in
          // https://stripe.com/docs/payments/checkout/fulfillment
          successUrl: 'https://editing.{{ env("SESSION_DOMAIN") }}?success',
          cancelUrl: 'https://editing.{{ env("SESSION_DOMAIN") }}?canceled',
        })
        .then(function (result) {
          if (result.error) {
            // If `redirectToCheckout` fails due to a browser or network
            // error, display the localized error message to your customer.
            var displayError = document.getElementById('error-message');
            displayError.textContent = result.error.message;
          }
        });
      });
    })();
    </script>

@endsection