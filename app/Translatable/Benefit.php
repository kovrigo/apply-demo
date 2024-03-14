<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait Benefit
{
	use HasTranslations;

	public $translatable = ['name', 'description'];

}
