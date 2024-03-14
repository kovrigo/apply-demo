<?php

namespace App;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use App\Settings\Model;

class Profile extends Model implements HasMedia
{
	use \App\Relations\Profile;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Media\Profile;
	use \App\Restrictions\Profile;

    protected $casts = [
        'birthday' => 'date:Y-m-d',
        'relocation' => 'boolean',
    ];

    public function getEmailAttribute()
    {
        return optional($this->contacts->first(function ($contact) {
            return $contact->contactMethod->google_talent_solution_id == 'EMAIL';
        }))->contact;
    }

    public static $resourceTitleAttribute = "default_title";

    public function getDefaultTitleAttribute()
    {
        $title = $this->given_name . ' ' . $this->family_name;
        $title = $title == ' ' ? $this->nickname : $title;
        $title = $title == '' ? $this->email : $title;
        return $title;
    }

}

