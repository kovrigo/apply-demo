<?php

namespace App\Settings;

use Auth;
use App\Settings\Resolved\Settings;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SettingsResolver
{
	public $authenticated = false;
	public $settings = null;
	public $definitions = null;	
	public $user = null;
	public $isHost = false;
	public $tenantId = null;
	public $currencyId = null;
	public $systemUser = null;
	public $userProfileCode = null;
	public $userProfileSettings = null;

    protected static $_instance;

	private function __construct()
	{

    }

    public static function getInstance() 
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        if (!self::$_instance->settings) {
        	self::$_instance->init();
        }
        return self::$_instance;
    }
 
    private function __clone() 
    {

    }

    private function __wakeup() 
    {

    }

	public function init()
    {
    	// Not authenticated
		if (!Auth::hasUser()) {
			return;
		}
		// Has cached settings
		if (Cache::has(Auth::id())) {
			// Restore settings from cache
			$this->restore();
		} else {			
			// Get settings from DB
			$user = Auth::user();
			$tenant = DB::table('tenants')
				->where('id', $user->tenant_id)
				->select('is_host', 'system_user_id', 'currency_id')
				->first();
			$systemUser = DB::table('users')
				->where('id', $tenant->system_user_id)
				->select('api_token')
				->first();
			$this->authenticated = true;
			$this->tenantId = $user->tenant_id;
			$this->currencyId = $tenant->currency_id;
			$this->isHost = (bool) $tenant->is_host;
			$this->systemUser = ['api_token' => $systemUser->api_token];

			// Load basic refs
			$basicRefs = DB::table('resource_settings')
				->whereNotNull('name')
				->whereNull('deleted_at')
				->select('name', 'is_json', 'json_value', 'js_value')
				->get()
				->map(function ($basicRef) {
					$basicRef->is_json = (bool) $basicRef->is_json;
					if ($basicRef->is_json) {
						$basicRef->json_value = json_decode($basicRef->json_value, true);
					}
					return $basicRef;
				});
			// Add scripts to basic definitions (only scripts to avoid loops in refs)
			$definitions = [ 
				"basic" => $basicRefs->filter(function ($basicRef) {
						return !$basicRef->is_json;
					})
					->mapWithKeys(function ($basicRef) {
						return [
							$basicRef->name => $basicRef->js_value
						];
					})->all()
			];
			// Dereference basic refs
			$basicRefs = $basicRefs->mapWithKeys(function ($basicRef) use ($definitions) {
					if ($basicRef->is_json) {
						$value = JsonDereferencer::dereference($basicRef->json_value, $definitions);
					} else {
						$value = $basicRef->js_value;
					}
					return [
						$basicRef->name => $value
					];
				})->all();
			// Load custom refs
			$customRefs = DB::table('resource_custom_settings')
				->whereNotNull('name')
				->whereNull('deleted_at')
				->select('name', 'is_json', 'json_value', 'js_value')
				->get()
				->map(function ($customRef) {
					$customRef->is_json = (bool) $customRef->is_json;
					if ($customRef->is_json) {
						$customRef->json_value = json_decode($customRef->json_value, true);
					}
					return $customRef;
				});
			// Add scripts to custom definitions (only scripts to avoid loops in refs)
			$definitions = [ 
				"basic" => $basicRefs,
				"custom" => $customRefs->filter(function ($customRef) {
						return !$customRef->is_json;
					})
					->mapWithKeys(function ($customRef) {
						return [
							$customRef->name => $customRef->js_value
						];
					})->all()
			];
			// Dereference custom refs
			$customRefs = $customRefs->mapWithKeys(function ($customRef) use ($definitions) {
					if ($customRef->is_json) {
						$value = JsonDereferencer::dereference($customRef->json_value, $definitions);
					} else {
						$value = $customRef->js_value;
					}
					return [
						$customRef->name => $value
					];
				})->all();
			// Get definitions to resolve role settings
			$definitions = [ 
				"basic" => $basicRefs,
				"custom" => $customRefs,
			];
			$this->definitions = $definitions;
			// Get user profile (role)
			$userProfile = DB::table('user_profiles')		
				->where('id', $user->user_profile_id)
				->select('settings', 'code')
				->first();
			$this->userProfileCode = $userProfile->code;
			$userProfileSettings = json_decode($userProfile->settings, true);
			$userProfileSettings = JsonDereferencer::dereference($userProfileSettings, $definitions);
			$this->userProfileSettings = $userProfileSettings;

			// Cache settings
			$this->cache();
		}
		// Parse settings
		$this->user = Auth::user();
		$this->settings = new Settings($this->userProfileSettings);
    }

	public function cache()
    {
		$data = [
			'authenticated' => $this->authenticated,
			'tenantId' => $this->tenantId,
			'currencyId' => $this->currencyId,
			'isHost' => $this->isHost,
			'systemUser' => $this->systemUser,
			'userProfileCode' => $this->userProfileCode,
			'userProfileSettings' => $this->userProfileSettings,
			'definitions' => $this->definitions,
		];
		Cache::put(Auth::id(), $data);
    }

	public function restore()
    {
		$data = Cache::get(Auth::id());
		$this->authenticated = $data['authenticated'];
		$this->tenantId = $data['tenantId'];
		$this->currencyId = $data['currencyId'];
		$this->isHost = $data['isHost'];
		$this->systemUser = $data['systemUser'];
		$this->userProfileCode = $data['userProfileCode'];
		$this->userProfileSettings = $data['userProfileSettings'];
		$this->definitions = $data['definitions'];
    }

}
