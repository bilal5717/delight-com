<?php

namespace extras\plugins\currencyexchange;

use App\Helpers\DBTool;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Prologue\Alerts\Facades\Alert;

class Currencyexchange
{
	/**
	 * @return array
	 */
	public static function getOptions(): array
	{
		$options = [];
		$setting = Setting::active()->where('key', 'currencyexchange')->first();
		if (!empty($setting)) {
			$options[] = (object)[
				'name'     => mb_ucfirst(trans('admin.settings')),
				'url'      => admin_url('settings/' . $setting->id . '/edit'),
				'btnClass' => 'btn-info',
			];
		}
		
		return $options;
	}
	
	/**
	 * @return bool
	 */
	public static function installed(): bool
	{
		$setting = Setting::active()->where('key', 'currencyexchange')->first();
		if (!empty($setting)) {
			if (Schema::hasColumn('countries', 'currencies')) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * @return bool
	 */
	public static function install(): bool
	{
		// Uninstall the plugin
		if (Schema::hasColumn('countries', 'currencies')) {
			self::uninstall();
		}
		
		try {
			// Perform the plugin SQL queries
			$updateSqlFile = plugin_path('currencyexchange', 'database/sql/install.sql');
			if (file_exists($updateSqlFile)) {
				$sql = file_get_contents($updateSqlFile);
				$sql = str_replace('<<prefix>>', DB::getTablePrefix(), $sql);
				$sql = str_replace('__PREFIX__', DB::getTablePrefix(), $sql);
				DB::unprepared($sql);
			}
			
			// Create plugin setting
			DB::statement('ALTER TABLE ' . DBTool::table((new Setting())->getTable()) . ' AUTO_INCREMENT = 1;');
			$pluginSetting = [
				'key'         => 'currencyexchange',
				'name'        => 'Currency Exchange',
				//'value'     => null,
				'description' => 'Currency Exchange Plugin',
				'field'       => null,
				'parent_id'   => 0,
				'lft'         => 32,
				'rgt'         => 33,
				'depth'       => 1,
				'active'      => 1,
			];
			$setting = Setting::create($pluginSetting);
			if (empty($setting)) {
				return false;
			}
			
			return true;
		} catch (\Throwable $e) {
			return false;
		}
	}
	
	/**
	 * @return bool
	 */
	public static function uninstall(): bool
	{
		try {
			// Remove plugin session
			if (session()->has('curr')) {
				session()->forget('curr');
			}
			
			// Remove plugin data
			$updateSqlFile = plugin_path('currencyexchange', 'database/sql/uninstall.sql');
			if (file_exists($updateSqlFile)) {
				$sql = file_get_contents($updateSqlFile);
				$sql = str_replace('<<prefix>>', DB::getTablePrefix(), $sql);
				$sql = str_replace('__PREFIX__', DB::getTablePrefix(), $sql);
				DB::unprepared($sql);
			}
			
			// Remove the plugin setting
			$setting = Setting::where('key', 'currencyexchange')->first();
			if (!empty($setting)) {
				$setting->delete();
			}
			
			return true;
		} catch (\Throwable $e) {
			$msg = 'ERROR: ' . $e->getMessage();
			Alert::error($msg)->flash();
		}
		
		return false;
	}
}
