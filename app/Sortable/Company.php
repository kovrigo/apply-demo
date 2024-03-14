<?php

namespace App\Sortable;

use Spatie\EloquentSortable\SortableTrait;

trait Company
{
	use SortableTrait;

	public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

}
