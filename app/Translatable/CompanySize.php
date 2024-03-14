<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait CompanySize
{
	use HasTranslations;

	public $translatable = ['name', 'description'];

}
