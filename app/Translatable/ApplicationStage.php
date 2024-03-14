<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait ApplicationStage
{
	use HasTranslations;

	public $translatable = ['name'];

}
