<?php

namespace App\Settings\Scripting;

use Spatie\Ssr\Renderer as BaseRenderer;

class Renderer extends BaseRenderer
{

    /** @var string */
    protected $script;

    /**
     * @param string $script
     *
     * @return $this
     */
    public function script(string $script)
    {
        $this->script = $script;
        return $this;
    }

    protected function applicationScript(): string
    {
        return parent::applicationScript() . $this->script;
    }

}
