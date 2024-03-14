<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait PopularityRating
{
	use HasTranslations;

	public $translatable = ['name'];

}
