<?php

namespace extras\plugins\stripe;

use App\Helpers\Number;
use App\Models\Post;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Helpers\Payment;
use App\Models\Package;
use Omnipay\Omnipay;

class Stripe extends Payment
{
	/**
	 * Send Payment
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Models\Post $post
	 * @return \App\Helpers\Payment|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 * @throws \Exception
	 */
	public static function upgradePosts(Request $request, Post $post)
    {
        return self::sendPayment($request, $post);
    }

	public static function sendPayment(Request $request, Post $post)
    {
        $requestIds = request()->ids;

        if (strpos($requestIds, ',') === false) {
            $requestIds = request()->id;
            $explodedRequestIds = request()->id;
        } else {
            $explodedRequestIds = explode(',', $requestIds);
        }

		// Set URLs
        parent::$uri['previousUrl'] = str_replace(['#entryId'], [$requestIds], parent::$uri['previousUrl']);
        parent::$uri['nextUrl'] = str_replace(['#entryId'], [$requestIds], parent::$uri['nextUrl']);
        parent::$uri['paymentCancelUrl'] = str_replace(['#entryId'], [$requestIds], parent::$uri['paymentCancelUrl']);
        parent::$uri['paymentReturnUrl'] = str_replace(['#entryId'], [$requestIds], parent::$uri['paymentReturnUrl']);

		// Get the gateway token
		$token = $request->input('stripeToken');
		
		// Get the Package
		$package = Package::find($request->input('package_id'));
		
		// Don't make a payment if 'price' = 0 or null
		if (empty($package) || $package->price <= 0) {
			return redirect(parent::$uri['previousUrl'] . '?error=package')->withInput();
		}

        $length = count(explode(',', $request->ids));
		
		// API Parameters
		$providerParams = [
			'amount'   => Number::toFloat($package->price * $length),
			'currency' => $package->currency_code,
			'token'    => $token,
		];
		
		// Local Parameters
		$localParams = [
			'payment_method_id' => $request->input('payment_method_id'),
			'cancelUrl'         => parent::$uri['paymentCancelUrl'],
			'returnUrl'         => parent::$uri['paymentReturnUrl'],
			'name'              => $package->name,
			'description'       => $package->name,
			'post_id'           => $explodedRequestIds,
			'package_id'        => $package->id,
		];
		$localParams = array_merge($localParams, $providerParams);
		
		// Try to make the Payment
		try {
			$gateway = Omnipay::create('Stripe');
			$gateway->setApiKey(config('payment.stripe.secret'));
			
			// Make the payment
			$response = $gateway->purchase($providerParams)->send();
			
			// Get raw data
			$rawData = $response->getData();
			
			// Save the Transaction ID at the Provider
			if (isset($rawData['id'])) {
				$localParams['transaction_id'] = $rawData['id'];
			}
			
			// Save local parameters into session
			session()->put('params', $localParams);
			
			// Payment by Credit Card when Card info are provide from the form.
			if ($response->isSuccessful()) {
				
				// Check if redirection to offsite payment gateway is needed
				if ($response->isRedirect()) {
					return $response->redirect();
				}
				
				// Apply actions after successful Payment
				return self::paymentConfirmationActions($localParams, $post);
				
			} elseif ($response->isRedirect()) {
				
				// Redirect to offsite payment gateway
				return $response->redirect();
				
			} else {
				
				// Apply actions when Payment failed
				return parent::paymentFailureActions($post, $response->getMessage());
				
			}
		} catch (\Exception $e) {
			
			// Apply actions when API failed
			return parent::paymentApiErrorActions($post, $e);
			
		}
	}
	
	/**
	 * @param $params
	 * @param $post
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public static function paymentConfirmation($params, $post)
	{
		// Set form page URL
		parent::$uri['previousUrl'] = str_replace(['#entryToken', '#entryId'], [$post->tmp_token, $post->id], parent::$uri['previousUrl']);
		parent::$uri['nextUrl'] = str_replace(['#entryToken', '#entryId', '#entrySlug'], [$post->tmp_token, $post->id, $post->slug], parent::$uri['nextUrl']);

		// Apply actions after successful Payment
		return parent::paymentConfirmationActions($params, $post);
	}
	
	/**
	 * @return array
	 */
	public static function getOptions()
	{
		$options = [];
		
		$paymentMethod = PaymentMethod::active()->where('name', 'stripe')->first();
		if (!empty($paymentMethod)) {
			$options[] = (object)[
				'name'     => mb_ucfirst(trans('admin.settings')),
				'url'      => admin_url('payment_methods/' . $paymentMethod->id . '/edit'),
				'btnClass' => 'btn-info',
			];
		}
		
		return $options;
	}
	
	/**
	 * @return bool
	 */
	public static function installed()
	{
		$paymentMethod = PaymentMethod::active()->where('name', 'stripe')->first();
		if (empty($paymentMethod)) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * @return bool
	 */
	public static function install()
	{
		// Remove the plugin entry
		self::uninstall();
		
		// Plugin data
		$data = [
			'id'                => 2,
			'name'              => 'stripe',
			'display_name'      => 'Stripe',
			'description'       => 'Payment with Stripe',
			'has_ccbox'         => 1,
			'is_compatible_api' => 0,
			'lft'               => 2,
			'rgt'               => 2,
			'depth'             => 1,
			'active'            => 1,
		];
		
		try {
			// Create plugin data
			$paymentMethod = PaymentMethod::create($data);
			if (empty($paymentMethod)) {
				return false;
			}
		} catch (\Exception $e) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * @return bool
	 */
	public static function uninstall()
	{
		$paymentMethod = PaymentMethod::where('name', 'stripe')->first();
		if (!empty($paymentMethod)) {
			$deleted = $paymentMethod->delete();
			if ($deleted > 0) {
				return true;
			}
		}
		
		return false;
	}

    public static function upgradePostsPaymentConfirmation($params, $post)
    {
        // Set form page URL
        parent::$uri['previousUrl'] = str_replace(['ids'], [request()->ids], parent::$uri['previousUrl']);
        // parent::$uri['nextUrl'] = str_replace(['ids'], [request()->ids], parent::$uri['nextUrl']);
        parent::$uri['nextUrl'] = str_replace(['#entryId', '#entrySlug'], [request()->ids, ''], parent::$uri['nextUrl']);

        // Try to make the Payment
        try {
            $gateway = Omnipay::create('PayPal_Express');
            $gateway->setUsername(config('payment.paypal.username'));
            $gateway->setPassword(config('payment.paypal.password'));
            $gateway->setSignature(config('payment.paypal.signature'));
            $gateway->setTestMode((config('payment.paypal.mode') == 'live') ? false : true);

            // Make the payment
            $response = $gateway->completePurchase($params)->send();
            // Get raw data
            $rawData = $response->getData();

            // Check the Payment
            if (isset($rawData['PAYMENTINFO_0_ACK']) && $rawData['PAYMENTINFO_0_ACK'] === 'Success') {

                // Save the Transaction ID at the Provider (CORRELATIONID | PAYMENTINFO_0_TRANSACTIONID)
                if (isset($rawData['PAYMENTINFO_0_TRANSACTIONID'])) {
                    $params['transaction_id'] = $rawData['PAYMENTINFO_0_TRANSACTIONID'];
                }

                // Apply actions after successful Payment
                return parent::upgradePost($params, $post);
            } else {

                // Apply actions when Payment failed
                return parent::paymentFailureActions($post);
            }
        } catch (\Exception $e) {
            // Apply actions when API failed
            return parent::paymentApiErrorActions($post, $e);
        }
    }
}
