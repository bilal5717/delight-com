@if (isset($paymentMethods) and $paymentMethods->count() > 0)
	@if (isset($selectedPackage) and !empty($selectedPackage))
		
		<?php $currentPackagePrice = $selectedPackage->price; ?>
		<div class="content-subheading">
			<i class="icon-wallet"></i>
			<strong>{{ t('Payment') }}</strong>
		</div>
		
		<div class="col-md-12 page-content mb-4">
			<div class="inner-box">
				
				<div class="row">
					<div class="col-sm-12">
						
						<div class="form-group mb-0">
							<fieldset>
								
								@includeFirst([
									config('larapen.core.customizedViewPath') . 'post.createOrEdit.inc.packages.selected',
									'post.createOrEdit.inc.packages.selected'
								])
							
							</fieldset>
						</div>
					
					</div>
				</div>
			</div>
		</div>
		
	@else
	
		@if (isset($packages) and $packages->count() > 0)
			<div class="content-subheading">
				<i class="icon-tag"></i>
				<strong>{{ t('Packages') }}</strong>
			</div>
			
			<div class="col-md-12 page-content mb-4">
				<div class="inner-box">
					
					<div class="row">
						<div class="col-sm-12">
							<fieldset>
								
								@includeFirst([
									config('larapen.core.customizedViewPath') . 'post.createOrEdit.inc.packages',
									'post.createOrEdit.inc.packages'
								])
							
							</fieldset>
							
						</div>
					</div>
				</div>
			</div>
<style>
	.popupk {
		display: none;
		position: fixed;
		padding: 10px;
		width: 280px;
		left: 50%;
		margin-left: -150px;
		height: 190px;
		top: 50%;
		margin-top: -100px;
		background: #FFF;
		border: 3px solid #F04A49;
		z-index: 20;
	}
</style>
			<div class="popupk" id="popupk">
				<p>{{ trans('company.blacklist_popup_title') }}</p>
				<p>{{ trans('company.blacklist_popup_all') }}</p>
				<p>{{ trans('company.blacklist_popup_all_red') }}</p>
				<a href="#" class="btn btn-success" onclick="removeWords()">{{ trans('company.blacklist_popup_ok_button') }}</a>
				<a href="#" class="btn btn-default" style="width: 45% !important;" onclick="hide()">{{ trans('company.blacklist_popup_close_button') }}</a>
			</div>
		@endif
		
	@endif
@endif

@section('after_styles')
	@parent
@endsection

@section('after_scripts')
	@parent
	<script>
		@if (isset($packages) and isset($paymentMethods) and $packages->count() > 0 and $paymentMethods->count() > 0)
		
			var currentPackagePrice = {{ isset($currentPackagePrice) ? $currentPackagePrice : 0 }};
			var currentPaymentActive = {{ isset($currentPaymentActive) ? $currentPaymentActive : 0 }};
			$(document).ready(function ()
			{
				/* Show price & Payment Methods */
				var currentSelectedPackage = $('input[name=package_id]:checked').val();
				var packagePrice = 0;
				var packageCurrencySymbol = $('input[name=package_id]:checked').data('currencysymbol');
				var packageCurrencyInLeft = $('input[name=package_id]:checked').data('currencyinleft');
				var paymentMethod = $('#paymentMethodId').find('option:selected').data('name');
				showAmount(packagePrice, packageCurrencySymbol, packageCurrencyInLeft);
				showPaymentSubmitButton(currentPackagePrice, packagePrice, currentPaymentActive, paymentMethod);
				
				/* Select a Package */
				$('.package-selection').click(function () {
					var selectedPackage = $(this).val();
					if (currentSelectedPackage === selectedPackage) {
						packagePrice = 0;
						$('.payment-plugin').hide();
						$('#stripePayment').hide();
					}
					else {
						packagePrice = getPackagePrice(selectedPackage);
					}
					packageCurrencySymbol = $(this).data('currencysymbol');
					packageCurrencyInLeft = $(this).data('currencyinleft');
					showAmount(packagePrice, packageCurrencySymbol, packageCurrencyInLeft);
					showPaymentSubmitButton(currentPackagePrice, packagePrice, currentPaymentActive, paymentMethod);
				});
				
				/* Select a Payment Method */
				$('#paymentMethodId').on('change', function () {
					paymentMethod = $(this).find('option:selected').data('name');
					showPaymentSubmitButton(currentPackagePrice, packagePrice, currentPaymentActive, paymentMethod);
				});
				
				/* Form Default Submission */
				$('#submitPostForm').on('click', function (e) {
					e.preventDefault();

					if (packagePrice <= 0 && formValid) {
						$('#postForm').submit();
					}

					if(!formValid){
						$('html, body').scrollTop($("#title").offset().top);
						$('.popupk').show();
					}

					return false;
				});
			});
		
		@endif
		
		/* Show or Hide the Payment Submit Button */
		/* NOTE: Prevent Package's Downgrading */
		/* Hide the 'Skip' button if Package price > 0 */
		function showPaymentSubmitButton(currentPackagePrice, packagePrice, currentPaymentActive, paymentMethod)
		{
			/* This feature is related to the Multi Step Form */
			return false;
		}

		function hide()
		{
			$('.popupk').hide();

			$('html, body').scrollTop($("#title").offset().top);
		}

		function removeWords()
		{
			var titleText = $("#title").val();

			blacklistWords.forEach(function(item) {
				if (titleText.indexOf(item) >= 0) {
					titleText = titleText.replace(item, '');
					$("#title").val(titleText)
				}
			});

			var tinyMCEText =  tinyMCE.activeEditor.getContent();

			tinyMCEText = tinyMCEText.replace(/<span style="background-color: #e03e2d;">(.*?)<\/span>/g, '');

			tinyMCE.activeEditor.setContent(tinyMCEText);

			setSummary("#summary", tinyMCEText);

			formValid = true;

			$('.popupk').hide();

			$('html, body').scrollTop($("#title").offset().top);
		}
	</script>
@endsection