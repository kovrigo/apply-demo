<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait Industry
{
	use HasTranslations;

	public $translatable = ['name'];

}
