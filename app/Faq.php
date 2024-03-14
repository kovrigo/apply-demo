<?php

namespace App;

use App\Settings\Model;
use Spatie\EloquentSortable\Sortable;

class Faq extends Model implements Sortable
{
    use \App\Relations\Faq;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Sortable\Faq;
	use \App\Restrictions\Faq;

}

