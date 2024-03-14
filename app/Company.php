<?php

namespace App;

use Spatie\MediaLibrary\HasMedia\HasMedia;

use App\Settings\Model;
use Spatie\EloquentSortable\Sortable;
use App\Workplace;
use App\Place;
use App\Job;
use App\Ad;

class Company extends Model implements HasMedia, Sortable
{
    use \App\Relations\Company;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Sortable\Company;
	use \App\Media\Company;
	use \App\Restrictions\Company;

	public function ads()
	{
		$placeIds = $this->places()
			->pluck('id')
			->all();
		$workplaceIds = Workplace::whereIn('addressable_id', $placeIds)
			->where('addressable_type', Place::class)
			->pluck('id')
			->all();
		$jobIds = Job::whereIn('workplace_id', $workplaceIds)
			->pluck('id')
			->all();
		return Ad::whereIn('job_id', $jobIds);
	}

}

