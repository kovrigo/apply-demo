<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait Skill
{
	use HasTranslations;

	public $translatable = ['name'];

}
