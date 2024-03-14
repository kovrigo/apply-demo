<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Ampeco\Filters\DateRangeFilter;

class DateRange extends DateRangeFilter
{
    /**
     * The column that should be filtered on.
     *
     * @var string
     */
    protected $column;

    /**
     * The filter label.
     *
     * @var string
     */
    protected $label;

    /**
     * Create a new filter instance.
     *
     * @param  string  $column
     * @return void
     */
    public function __construct($column, $label)
    {
        $this->column = $column;
        $this->label = $label;
        $this->withMeta(['mode' => 'range']);
    }

    /**
     * Get the displayable name of the filter.
     *
     * @return string
     */
    public function name()
    {
        return $this->label;
    }

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        $from = Carbon::parse($value[0])->startOfDay();
        $to = Carbon::parse($value[1])->endOfDay();
        return $query->whereBetween($this->column, [$from, $to]);
    }

    /**
     * Get the key for the filter.
     *
     * @return string
     */
    public function key()
    {
        return 'date_range_' . $this->column;
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [];
    }

}