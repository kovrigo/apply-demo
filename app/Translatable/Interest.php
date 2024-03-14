<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait Interest
{
	use HasTranslations;

	public $translatable = ['name'];

}
