<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait SkillLevel
{
	use HasTranslations;

	public $translatable = ['name', 'description'];

}
