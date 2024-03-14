<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait ArticleState
{
	use HasTranslations;

	public $translatable = ['name'];

}

