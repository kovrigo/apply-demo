<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait NewOrderState
{
	use HasTranslations;

	public $translatable = ['name'];

}

