<?php

namespace extras\plugins\currencyexchange\app\Models\Setting;

use App\Models\Currency;

class CurrencyexchangeSetting
{
	public static function getValues($value, $disk)
	{
		if (empty($value)) {
			
			$value['activation'] = '1';
			$value['currencies'] = 'USD,EUR';
			$value['cache_ttl'] = '86400';
			$value['european_central_bank'] = '1';
			$value['national_bank_of_romania'] = '1';
			$value['central_bank_of_republic_turkey'] = '1';
			$value['central_bank_of_czech_republic'] = '1';
			$value['russian_central_bank'] = '1';
			$value['webservicex'] = '1';
			$value['cryptonator'] = '1';
			
		} else {
			
			if (!isset($value['activation'])) {
				$value['activation'] = '1';
			}
			if (!isset($value['currencies'])) {
				$value['currencies'] = 'USD,EUR';
			}
			if (!isset($value['cache_ttl'])) {
				$value['cache_ttl'] = '86400';
			}
			if (!isset($value['european_central_bank'])) {
				$value['european_central_bank'] = '1';
			}
			if (!isset($value['national_bank_of_romania'])) {
				$value['national_bank_of_romania'] = '1';
			}
			if (!isset($value['central_bank_of_republic_turkey'])) {
				$value['central_bank_of_republic_turkey'] = '1';
			}
			if (!isset($value['central_bank_of_czech_republic'])) {
				$value['central_bank_of_czech_republic'] = '1';
			}
			if (!isset($value['russian_central_bank'])) {
				$value['russian_central_bank'] = '1';
			}
			if (!isset($value['webservicex'])) {
				$value['webservicex'] = '1';
			}
			if (!isset($value['cryptonator'])) {
				$value['cryptonator'] = '1';
			}
			
		}
		
		return $value;
	}
	
	public static function setValues($value, $setting)
	{
		return $value;
	}
	
	public static function getFields($diskName)
	{
		// Get Countries codes
		$currencies = Currency::get(['code']);
		$currenciesCodes = [];
		if ($currencies->count() > 0) {
			$currenciesCodes = $currencies->keyBy('code')->keys()->toArray();
		}
		
		$fields = [
			[
				'name'         => 'activation',
				'label'        => trans('currencyexchange::messages.Enable the Currency Exchange Option'),
				'type'         => 'checkbox_switch',
				'hint'         => trans('currencyexchange::messages.Enable/Disable the Currency Exchange Option.'),
			],
			[
				'name'         => 'currencies',
				'label'        => trans("currencyexchange::messages.Currencies"),
				'attributes'   => [
					'placeholder' => trans('currencyexchange::messages.eg_currencies_field'),
				],
				'hint'         => trans('currencyexchange::messages.currencies_codes_list_menu_hint', ['url' => admin_url('currencies')])
					. '<br>' . trans('currencyexchange::messages.Use the codes below')
					. '<br>' . implode(', ', $currenciesCodes)
					. '<br>---<br>'
					. trans('currencyexchange::messages.currencies_codes_list_menu_hint_note'),
			],
			[
				'name'         => 'services_title',
				'type'         => 'custom_html',
				'value'        => trans('currencyexchange::messages.services_title'),
			],
			[
				'name'         => 'services_description',
				'type'         => 'custom_html',
				'value'        => trans('currencyexchange::messages.services_description'),
			],
			[
				'name'         => 'fixer_title',
				'type'         => 'custom_html',
				'value'        => trans('currencyexchange::messages.fixer_title'),
			],
			[
				'name'         => 'fixer_info',
				'type'         => 'custom_html',
				'value'        => trans('currencyexchange::messages.fixer_info'),
			],
			[
				'name'              => 'fixer_access_key',
				'label'             => 'Fixer Access Key',
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'         => 'currency_layer_title',
				'type'         => 'custom_html',
				'value'        => trans('currencyexchange::messages.currency_layer_title'),
			],
			[
				'name'         => 'currency_layer_info',
				'type'         => 'custom_html',
				'value'        => trans('currencyexchange::messages.currency_layer_info'),
			],
			[
				'name'              => 'currency_layer_access_key',
				'label'             => 'Currency Layer Access Key',
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'currency_layer_enterprise',
				'label'             => 'Currency Layer Enterprise',
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
					
				],
			],
			[
				'name'         => 'open_exchange_rates_title',
				'type'         => 'custom_html',
				'value'        => trans('currencyexchange::messages.open_exchange_rates_title'),
			],
			[
				'name'         => 'open_exchange_rates_info',
				'type'         => 'custom_html',
				'value'        => trans('currencyexchange::messages.open_exchange_rates_info'),
			],
			[
				'name'              => 'open_exchange_rates_app_id',
				'label'             => 'Open Exchange Rates App ID',
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'open_exchange_rates_enterprise',
				'label'             => 'Open Exchange Rates Enterprise',
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
					
				],
			],
			[
				'name'         => 'currency_data_feed_title',
				'type'         => 'custom_html',
				'value'        => trans('currencyexchange::messages.currency_data_feed_title'),
			],
			[
				'name'         => 'currency_data_feed_info',
				'type'         => 'custom_html',
				'value'        => trans('currencyexchange::messages.currency_data_feed_info'),
			],
			[
				'name'              => 'currency_data_feed_api_key',
				'label'             => 'Currency Data Feed API Key',
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'         => 'forge_title',
				'type'         => 'custom_html',
				'value'        => trans('currencyexchange::messages.forge_title'),
			],
			[
				'name'         => 'forge_info',
				'type'         => 'custom_html',
				'value'        => trans('currencyexchange::messages.forge_info'),
			],
			[
				'name'              => 'forge_api_key',
				'label'             => 'Forge API Key',
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'         => 'xignite_title',
				'type'         => 'custom_html',
				'value'        => trans('currencyexchange::messages.xignite_title'),
			],
			[
				'name'         => 'xignite_info',
				'type'         => 'custom_html',
				'value'        => trans('currencyexchange::messages.xignite_info'),
			],
			[
				'name'              => 'xignite_token',
				'label'             => 'Xignite Token',
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'         => 'other_services_title',
				'type'         => 'custom_html',
				'value'        => trans('currencyexchange::messages.other_services_title'),
			],
			[
				'name'              => 'european_central_bank',
				'label'             => 'European Central Bank',
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'national_bank_of_romania',
				'label'             => 'National Bank of Romania',
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'central_bank_of_republic_turkey',
				'label'             => 'Central Bank of the Republic of Turkey',
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'central_bank_of_czech_republic',
				'label'             => 'Central Bank of the Czech Republic',
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'russian_central_bank',
				'label'             => 'Central Bank of Russia',
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'webservicex',
				'label'             => 'WebserviceX',
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'cryptonator',
				'label'             => 'Cryptonator',
				'type'              => 'checkbox_switch',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'         => 'options_title',
				'type'         => 'custom_html',
				'value'        => trans('currencyexchange::messages.options_title'),
			],
			[
				'name'              => 'cache_ttl',
				'label'             => trans('currencyexchange::messages.Cache TTL'),
				'type'              => 'number',
				'hint'              => trans('currencyexchange::messages.The cache ttl in seconds.'),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'         => 'services_information_sep',
				'type'         => 'custom_html',
				'value'        => '<hr>',
			],
			[
				'name'         => 'services_information_title',
				'type'         => 'custom_html',
				'value'        => trans('currencyexchange::messages.services_information_title'),
			],
			[
				'name'         => 'services_information_content',
				'type'         => 'custom_html',
				'value'        => trans('currencyexchange::messages.services_information_content'),
			],
		];
		
		return $fields;
	}
}
