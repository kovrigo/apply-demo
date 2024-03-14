<?php

namespace App;

use App\Settings\Model;

class Contact extends Model
{
    use \App\Relations\Contact;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Restrictions\Contact;

}

