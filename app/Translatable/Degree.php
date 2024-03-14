<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait Degree
{
	use HasTranslations;

	public $translatable = ['name', 'description'];

}
