<?php

namespace App;

use App\Settings\Model;

class Conversion extends Model
{
	use \App\Relations\Conversion;
	use \App\Restrictions\Conversion;

	const IMPRESSION_TO_CLICK = 0;
	const CLICK_TO_ACTION = 1;

	public static function impressionToAction()
	{
		return self::where('type', self::IMPRESSION_TO_CLICK)->first()->conversion *
			self::where('type', self::CLICK_TO_ACTION)->first()->conversion;
	}

}

