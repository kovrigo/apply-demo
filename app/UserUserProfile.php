<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class UserUserProfile extends Pivot implements Sortable
{
    use SortableTrait;

    public $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    public function buildSortQuery()
    {
        return static::query()
			->where('user_id', $this->user_id);
    }

}
