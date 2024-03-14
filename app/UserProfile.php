<?php

namespace App;

use App\Settings\Model;
use App\Settings\JsonDereferencer;

class UserProfile extends Model
{
    use \App\Relations\UserProfile;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Restrictions\UserProfile;

    protected $casts = [
        'settings' => 'array',
        'navigation' => 'array',
        'settings_backup' => 'array'
    ];

}

