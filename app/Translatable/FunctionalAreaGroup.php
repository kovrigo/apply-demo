<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait FunctionalAreaGroup
{
	use HasTranslations;

	public $translatable = ['name'];

}
