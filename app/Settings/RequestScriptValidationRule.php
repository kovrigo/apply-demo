<?php

namespace App\Settings;

use Illuminate\Contracts\Validation\Rule;
use App\Settings\RequestContext;
use App\Settings\Scripting\Script;
use App\Settings\ActionRequest;

class RequestScriptValidationRule implements Rule
{

    protected $script;
    protected $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($script)
    {
        $this->script = $script;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->script) {
            $request = ActionRequest::createFrom(request());
            $context = RequestContext::make()
                ->set('dialog', $request->dialog())
                ->set('models', $request->models())
                ->forScripting();
            if (is_array($this->script)) {
                $this->message = collect($this->script)->map(function ($script) use ($context) {
                    return Script::run($script, $context);
                })
                ->filter(function ($message) {
                    return $message != '';
                })->implode("<br>");
            } else {
                $this->message = Script::run($this->script, $context);
            }
            return $this->message ? false : true;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
