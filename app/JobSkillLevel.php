<?php

namespace App;

use Spatie\EloquentSortable\Sortable;

use App\Settings\Model;

class JobSkillLevel extends Model implements Sortable
{
    use \App\Relations\JobSkillLevel;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Sortable\JobSkillLevel;
	use \App\Restrictions\JobSkillLevel;

    protected $casts = [
        'required' => 'boolean',
    ];

}

