<?php

namespace App;

use App\Settings\Model;

class FunctionalArea extends Model
{
    use \App\Relations\FunctionalArea;
	use \App\Translatable\FunctionalArea;
	use \App\Restrictions\FunctionalArea;

    public function getFunctionalAreaGroupTitleAttribute()
    {
        return $this->functionalAreaGroup->model_title;
    }

}

