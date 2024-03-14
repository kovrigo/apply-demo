<?php

namespace App\Settings;

use League\JsonReference\Dereferencer;
use League\JsonReference\ReferenceSerializer\InlineReferenceSerializer;
use League\JsonReference\LoaderManager;
use League\JsonReference\Loader\ArrayLoader;
use Illuminate\Support\Collection;

class JsonDereferencer
{

	public static function dereference($json = [], $definitions = [], $debug = false) 
	{
		$json['definitions'] = $definitions;
		$json = json_decode(json_encode($json));
		$dereferencer  = new Dereferencer;
		$dereferencer->setReferenceSerializer(new InlineReferenceSerializer());
		$data = ['data' => $json];
		$loaderManager = new LoaderManager();
		$loaderManager->registerLoader('array', new ArrayLoader($data));
		$dereferencer->setLoaderManager($loaderManager);
		$dereferencedJson = json_decode(json_encode($dereferencer->dereference('array://data')), true);
		$dereferencedJson = self::resolveAllOf($dereferencedJson, $debug);
		return $dereferencedJson;
	}

	public static function resolveAllOf($json = [], $debug = false)
	{
		$resolvedJson = collect($json)->recursiveMap(function ($value) use ($debug) {
		    if ($value instanceof Collection) {
		        if ($value->has('allOf')) {
		            $allOf = $value->get('allOf');
		            $newValue = clone $value;
		            $newValue->forget('allOf');
		            $allOf->prepend($newValue);
		            return array_merge_deep($allOf);
		        }
		    }
		    return $value;
		});
		$resolvedJson->forget('definitions');
		return $resolvedJson->recursiveAll()->all();
	}

}
