<?php

namespace extras\plugins\currencyexchange\app\Http\Middleware;

use App\Helpers\Number;
use App\Models\Currency;
use Closure;
use Swap\Laravel\Facades\Swap;

class CurrencyExchange
{
	/**
	 * Get the Currency Exchange Rate between the country default currency and the selected currency
	 *
	 * @param $request
	 * @param \Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$currencyCode = config('country.currency');
		
		$selectedCurrency = config('currency');
		$selectedCurrency['rate'] = 1;
		
		if (session()->has('curr')) {
			$currencyCode = session('curr');
		}
		
		if (request()->has('curr')) {
			$currencyCode = request()->get('curr');
			session()->put('curr', $currencyCode);
		}
		
		if (config('settings.currencyexchange.activation')) {
			if (!empty($currencyCode)) {
				$currency = Currency::find($currencyCode);
				if (!empty($currency)) {
					if ($currency->code != config('country.currency')) {
						try {
							// Get the latest Currency Exchange Rate
							$exchange = Swap::latest(config('country.currency') . '/' . $currency->code);
							$rate = $exchange->getValue();
							$rate = Number::toFloat($rate);
							
							// Update the selected currency data (after API call is done)
							$selectedCurrency = array_merge($selectedCurrency, $currency->toArray());
							$selectedCurrency['rate'] = $rate;
						} catch (\Throwable $e) {
							// Debug
							// dd($e);
						}
					}
				}
			}
		}
		
		config()->set('selectedCurrency', $selectedCurrency);
		
		return $next($request);
	}
}
