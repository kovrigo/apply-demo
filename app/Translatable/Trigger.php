<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait Trigger
{
	use HasTranslations;

	public $translatable = ['name'];

}
