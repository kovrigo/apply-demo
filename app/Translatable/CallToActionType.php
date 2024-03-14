<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait CallToActionType
{
	use HasTranslations;

	public $translatable = ['name'];

}
