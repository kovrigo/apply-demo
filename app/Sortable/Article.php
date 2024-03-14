<?php

namespace App\Sortable;

use Spatie\EloquentSortable\SortableTrait;

trait Article
{
	use SortableTrait;

	public $sortable = [
		'order_column_name' => 'sort_order',
		'sort_when_creating' => true,
		'sort_on_has_many' => true,
	];

}
