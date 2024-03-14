<?php

namespace App;

use Spatie\EloquentSortable\Sortable;

use App\Settings\Model;

class ProfileSkillLevel extends Model implements Sortable
{
    use \App\Relations\ProfileSkillLevel;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Sortable\ProfileSkillLevel;
	use \App\Restrictions\ProfileSkillLevel;

}

