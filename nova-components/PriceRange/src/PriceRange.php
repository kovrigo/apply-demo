<?php

namespace Kovrigo\PriceRange;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class PriceRange extends Field
{
	public $exactAmount;
	public $amount;
	public $min;
	public $max;
	public $currency;
	public $defaultCurrency;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'price-range';

    public function fields($exactAmount, $amount, $min, $max, $currency)
    {
        $this->exactAmount = $exactAmount;
        $this->amount = $amount;
        $this->min = $min;
        $this->max = $max;
        $this->currency = $currency;
        return $this;
    }

    public function currencies($currencies)
    {
    	return $this->withMeta([
    		'currencies' => $currencies,
    	]);
    }

    public function defaultCurrency($currency)
    {
    	$this->defaultCurrency = $currency;
    	return $this;
    }

    public function resolveAttribute($resource, $attribute = null)
    {
    	$defaultCurrency = $this->defaultCurrency;
    	if (is_null($resource->id)) {
    		return [
    			'exact_amount' => false,
    			'amount' => null,
    			'min' => null,
    			'max' => null,
    			'currency' => $defaultCurrency,
    		];
    	}
        $exactAmount = $this->exactAmount;
        $amount = $this->amount;
        $min = $this->min;
        $max = $this->max;
        $currency = $this->currency;
		return [
			'exact_amount' => $resource->$exactAmount != 0,
			'amount' => $resource->$amount,
			'min' => $resource->$min,
			'max' => $resource->$max,
			'currency' => $resource->$currency,
		];
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $value = json_decode($request->input($requestAttribute));
        $exactAmount = $this->exactAmount;
        $amount = $this->amount;
        $min = $this->min;
        $max = $this->max;
        $currency = $this->currency;
        $model->$exactAmount = $value->exact_amount;
        $model->$amount = $value->amount;
        $model->$min = $value->min;
        $model->$max = $value->max;
        $model->$currency = $value->currency;
    }

}
