<div class="modal fade" id="securityTips" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title font-weight-bold" id="securityTipsLabel">{{ t('phone_number') }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<?php

			$phoneModal = '';
			$intPhone = '';
			$intPhoneMasked = '';
			$localPhone = '';
			$localPhoneMasked = '';
			// If the 'hide_phone_number' option is disabled, append phone number in modal
			if (config('settings.single.hide_phone_number') == '') {
				if (isset($post, $post->phone)) {
					$countryCode = '';
					if (preg_match('(\{|\[)', $post->phone_country_code)) {
						$countryCode = json_decode($post->phone_country_code, true)[0];
					}
					$phoneModal = "+" . $countryCode . $post->phone;
					$localPhoneMasked = $localPhone = "0" . $post->phone;
					$intPhoneMasked = $intPhone = '+' . $countryCode . $post->phone;
				}
			}
            else{
				$countryCode = '';
				if (preg_match('(\{|\[)', $post->phone_country_code)) {
					$countryCode = json_decode($post->phone_country_code, true)[0];
				}
				$phoneModal = "+" . $countryCode . $post->phone;
				$localPhoneMasked = $localPhone = "0" . $post->phone;
				$intPhoneMasked = $intPhone = '+' . $countryCode . $post->phone;

				if (config('settings.single.hide_phone_number') == '1') {
					$phoneModal = maskPhoneNumber($phoneModal, 3, true);
					$localPhoneMasked = maskPhoneNumber($localPhoneMasked, 3, true);
					$intPhoneMasked = maskPhoneNumber($intPhoneMasked, 3, true);
				}
				if (config('settings.single.hide_phone_number') == '2') {
					$phoneModal = maskPhoneNumber($phoneModal, 3, false);
					$localPhoneMasked = maskPhoneNumber($localPhoneMasked, 3, false);
					$intPhoneMasked = maskPhoneNumber($intPhoneMasked, 3, false);
				}
				if (config('settings.single.hide_phone_number') == '3') {
					$phoneModal = maskPhoneNumber($phoneModal, 0, true);
					$localPhoneMasked = maskPhoneNumber($localPhoneMasked, 0, true);
					$intPhoneMasked = maskPhoneNumber($intPhoneMasked, 0, true);
				}
			}
			?>

			<div class="modal-body">
				<div class="row">
					<div class="col-12 text-center">
						<h1 id="" class="p-4 font-weight-bold rounded" style="border: 2px dashed red; background-color: #ffebb7;">
							{{ $phoneModal }}
						</h1>
					</div>
					<div class="col-12 mt-4">
						<h3 class="text-danger" style="font-weight: bold;">
							<i class="fas fa-exclamation-triangle"></i> {!! t('security_tips_title') !!}
						</h3>
					</div>
					<div class="col-12">
						{!! t('security_tips_text', ['appName' => config('app.name')]) !!}
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ t('Close') }}</button>
				<a href="tel:{{ $localPhone }}" class="btn btn-info" style="background-color: #07642f; border-color: #07642f; color: #FFFFFF">
					<i class="icon-phone-1"></i> {{ t('call_now') ." ". $localPhoneMasked }}
				</a>
				<a href="tel:{{ $intPhone }}" class="btn btn-success" style="background-color: #178f08; border-color: #178f08; color: #FFFFFF">
					<i class="icon-phone-1"></i> {{ t('call_now') ." ". $intPhoneMasked }}
				</a>
			</div>

		</div>
	</div>
</div>