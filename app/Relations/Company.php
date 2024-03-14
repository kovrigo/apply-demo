<?php

namespace App\Relations;

trait Company
{

    public function companySize()
    {
        return $this->belongsTo('App\CompanySize');
    }

    public function popularityRating()
    {
        return $this->belongsTo('App\PopularityRating');
    }

    public function places()
    {
        return $this->hasMany('App\Place');
    }

    public function placeGroups()
    {
        return $this->hasMany('App\PlaceGroup');
    }

    public function contacts()
    {
        return $this->morphMany('App\Contact', 'contactable');
    }

    public function industry()
    {
        return $this->belongsTo('App\Industry');
    }

    public function faqs()
    {
        return $this->morphMany('App\Faq', 'relatable');
    }

    public function numbers()
    {
        return $this->morphMany('App\Number', 'relatable');
    }

    public function infoBlocks()
    {
        return $this->morphMany('App\InfoBlock', 'relatable');
    }

}
