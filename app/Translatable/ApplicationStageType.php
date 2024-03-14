<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait ApplicationStageType
{
	use HasTranslations;

	public $translatable = ['name', 'description'];

}
