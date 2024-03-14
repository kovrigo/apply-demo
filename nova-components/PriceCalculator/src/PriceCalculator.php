<?php

namespace Kovrigo\PriceCalculator;

use Laravel\Nova\Fields\Field;

class PriceCalculator extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'price-calculator';

    public function currency($currency)
    {
    	return $this->withMeta([
    		'currency' => $currency,
    	]);
    }

    public function unitPrice($price)
    {
    	return $this->withMeta([
    		'unitPrice' => $price,
    	]);
    }

}
