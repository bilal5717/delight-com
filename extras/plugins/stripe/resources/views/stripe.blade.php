<div class="row payment-plugin" id="stripePayment" style="display: none;">
    <div class="col-md-10 col-sm-12 box-center center mt-4 mb-0">
        <div class="row">

            <div class="col-xl-12 text-center">
                <img class="img-fluid" width="250px" src="{{ url('images/stripe/payment-method.png') }}" title="{{ t('Payment with Stripe') }}">
            </div>

            <div class="col-xl-12 mt-3">
                <!-- CREDIT CARD FORM STARTS HERE -->
                <div class="card card-default credit-card-box">

                    <div class="card-header">
                        <h3 class="panel-title">
                            {{ t('Payment Details') }}
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label class="col-form-label" for="stripeCardNumber">{{ t('Card Number') }}</label>
                                    <div class="input-group">
                                        <input
                                                type="tel"
                                                class="form-control"
                                                name="stripeCardNumber"
                                                placeholder="{{ t('Valid Card Number') }}"
                                                autocomplete="cc-number"
                                                required
                                        />
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="stripeCardExpiry">{!! t('Expiration Date') !!}</label>
                                            <input
                                                    type="tel"
                                                    class="form-control"
                                                    name="stripeCardExpiry"
                                                    placeholder="{{ t('MM / YY') }}"
                                                    autocomplete="cc-exp"
                                                    required
                                            />
                                        </div>
                                    </div>
                                    <div class="col-md-6 pull-right">
                                        <div class="form-group">
                                            <label class="col-form-label" for="stripeCardCVC">{{ t('CV Code') }}</label>
                                            <input
                                                    type="tel"
                                                    class="form-control"
                                                    name="stripeCardCVC"
                                                    placeholder="{{ t('CVC') }}"
                                                    autocomplete="cc-csc"
                                                    required
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="stripePaymentErrors" style="display:none;">
                                <div class="col-xs-12">
                                    <p class="payment-errors"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- CREDIT CARD FORM ENDS HERE -->
            </div>

        </div>
    </div>
</div>

@section('after_scripts')
    @parent
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script>
        $(document).ready(function ()
        {
            var currentSelectedPackage = $('input[name=package_id]:checked').val();
            var packagePrice = 0;
            var paymentMethod = $('#paymentMethodId').find('option:selected').data('name');

            /* Check Payment Method */
            checkPaymentMethodForStripe(paymentMethod, packagePrice);

            $('#paymentMethodId').on('change', function () {
                paymentMethod = $(this).find('option:selected').data('name');
                checkPaymentMethodForStripe(paymentMethod, packagePrice);
            });
            $('.package-selection').on('click', function () {
                var selectedPackage = $(this).val();
                if (currentSelectedPackage === selectedPackage)
                {
                    packagePrice = 0;
                }
                else
                {
                    packagePrice = getPackagePrice(selectedPackage);
                }
                paymentMethod = $('#paymentMethodId').find('option:selected').data('name');
                checkPaymentMethodForStripe(paymentMethod, packagePrice);
            });


            /* Fancy restrictive input formatting via jQuery.payment library */
            $('input[name=stripeCardNumber]').payment('formatCardNumber');
            $('input[name=stripeCardCVC]').payment('formatCardCVC');
            $('input[name=stripeCardExpiry]').payment('formatCardExpiry');


            /* Send Payment Request */
            $('#submitPostForm').on('click', function (e)
            {
                e.preventDefault();

                paymentMethod = $('#paymentMethodId').find('option:selected').data('name');

                if (paymentMethod != 'stripe' || packagePrice <= 0) {
                    return false;
                }

                if (!ccFormValidationForStripe()) {
                    return false;
                }

                /* Call the token request function */
                payWithStripe();

                /* Prevent form from submitting */
                return false;
            });
        });


        /* Check the Payment Method */
        function checkPaymentMethodForStripe(paymentMethod, packagePrice)
        {
            var $form = $('#postForm');

            $form.find('#submitPostForm').html('{{ t('submit') }}').prop('disabled', false);

            /* Hide errors on the form */
            $form.find('#stripePaymentErrors').hide();
            $form.find('#stripePaymentErrors').find('.payment-errors').text('');

            if (paymentMethod == 'stripe' && packagePrice > 0) {
                $('#stripePayment').show();
            } else {
                $('#stripePayment').hide();
            }
        }

        /* Pay with the Payment Method */
        function payWithStripe()
        {
            var $form = $('#postForm');

            /* Visual feedback */
            $form.find('#submitPostForm').html('{{ t('Validating') }} <i class="fa fa-spinner fa-pulse"></i>').prop('disabled', true);

            var PublishableKey = '{!! config('payment.stripe.key') !!}'; /* Replace with your API publishable key */
            Stripe.setPublishableKey(PublishableKey);

            /* Create token */
            var expiry = $form.find('[name=stripeCardExpiry]').payment('cardExpiryVal');
            var ccData = {
                number: $form.find('[name=stripeCardNumber]').val().replace(/\s/g,''),
                cvc: $form.find('[name=stripeCardCVC]').val(),
                exp_month: expiry.month,
                exp_year: expiry.year
            };

            Stripe.card.createToken(ccData, function stripeResponseHandler(status, response)
            {
                if (response.error)
                {
                    /* Visual feedback */
                    $form.find('#submitPostForm').html('{{ t('Try again') }}').prop('disabled', false);

                    /* Show errors on the form */
                    $form.find('#stripePaymentErrors').find('.payment-errors').text(response.error.message);
                    $form.find('#stripePaymentErrors').show();
                }
                else
                {
                    /* Visual feedback */
                    $form.find('#submitPostForm').html('{{ t('Processing') }} <i class="fa fa-spinner fa-pulse"></i>');

                    /* Hide Stripe errors on the form */
                    $form.find('#stripePaymentErrors').hide();
                    $form.find('#stripePaymentErrors').find('.payment-errors').text('');

                    /* Response contains id and card, which contains additional card details */
                    console.log(response.id);
                    console.log(response.card);
                    var stripeToken = response.id;

                    /* Insert the token into the form so it gets submitted to the server */
                    $form.append($('<input type="hidden" name="stripeToken" />').val(stripeToken));

                    /* and submit */
                    $form.submit();
                }
            });
        }

        function ccFormValidationForStripe()
        {
            var $form = $('#postForm');

            /* Form validation using Stripe client-side validation helpers */
            jQuery.validator.addMethod('stripeCardNumber', function(value, element) {
                return this.optional(element) || Stripe.card.validateCardNumber(value);
            }, "{{ t('Please specify a valid credit card number') }}");

            jQuery.validator.addMethod('stripeCardExpiry', function(value, element) {
                /* Parsing month/year uses jQuery.payment library */
                value = $.payment.cardExpiryVal(value);
                return this.optional(element) || Stripe.card.validateExpiry(value.month, value.year);
            }, "{{ t('Invalid expiration date') }}");

            jQuery.validator.addMethod('stripeCardCVC', function(value, element) {
                return this.optional(element) || Stripe.card.validateCVC(value);
            }, "{{ t('Invalid CVC') }}");

            var validator = $form.validate({
                rules: {
                    stripeCardNumber: {
                        required: true,
                        stripeCardNumber: true
                    },
                    stripeCardExpiry: {
                        required: true,
                        stripeCardExpiry: true
                    },
                    stripeCardCVC: {
                        required: true,
                        stripeCardCVC: true
                    }
                },
                highlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                },
                unhighlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                },
                errorPlacement: function(error, element) {
                    $(element).closest('.form-group').append(error);
                }
            });

            paymentFormReady = function() {
                if ($form.find('[name=stripeCardNumber]').closest('.form-group').hasClass('has-success') &&
                    $form.find('[name=stripeCardExpiry]').closest('.form-group').hasClass('has-success') &&
                    $form.find('[name=stripeCardCVC]').val().length > 1) {
                    return true;
                } else {
                    return false;
                }
            };

            $form.find('#submitPostForm').prop('disabled', true);
            var readyInterval = setInterval(function() {
                if (paymentFormReady()) {
                    $form.find('#submitPostForm').prop('disabled', false);
                    clearInterval(readyInterval);
                }
            }, 250);

            /* Abort if invalid form data */
            if (!validator.form()) {
                return false;
            } else {
                return true;
            }
        }

    </script>
@endsection
