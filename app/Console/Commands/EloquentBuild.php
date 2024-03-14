<?php

namespace App\Console\Commands;

use App\Settings\Scripting\Generator;
use EloquentJs\ScriptGenerator\Console\InputParser;
//use EloquentJs\ScriptGenerator\Model\Inspector;
use App\Settings\Scripting\Inspector;

class EloquentBuild extends \EloquentJs\ScriptGenerator\Console\Command
{

    protected $buildPath;
    protected $buildPathExt;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eloquent:build
                            {--models= : Models to include in the generated javascript}
                            {--namespace=App : Namespace prefix to use with the --models option}
                            {--output=resources/js/eloquent/local.js : Where to save the generated javascript file}';    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build eloquentJs module';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $environments = ['local', 'staging', 'production'];
        foreach ($environments as $environment) {
            config(['app.url' => config('app.' . $environment . '_url') . '/']);
            $this->buildPath = 'resources/js/eloquent/' . $environment . '.js';
            $this->buildPathExt = 'resources/js/eloquent/src/' . $environment . '.js';
            $models = $this->inspectRequestedModels();
            $this->printMapping($models);
            if ($this->isConfirmed()) {
                $this->writeJavascript($models);
                $this->info("Javascript written to {$this->buildPath}");
            }
        }
    }

    /**
     * Save the generated javascript to the filesystem.
     *
     * @param array $models
     * @return bool
     */
    protected function writeJavascript($models)
    {
        $res = $this->laravel['files']->put($this->buildPath, $this->generator->buildInternal($models)) > 0;
        $this->laravel['files']->put($this->buildPathExt, $this->generator->buildExternal($models));
        return $res;
    }

    /**
     * Create a new command instance.
     *
     * @param InputParser $inputParser
     * @param ClassFinder $classFinder
     * @param Inspector $inspector
     * @param Generator $generator
     */
    public function __construct(InputParser $inputParser, Inspector $inspector, Generator $generator)
    {
        parent::__construct($inputParser, $inspector, $generator);
    }

    /**
     * Search the app path for any models that implement EloquentJs.
     *
     * @return array
     */
    protected function searchAppForModels()
    {
        return array_filter(
            $this->findClasses(app_path()),
            function($className) {
                // TODO: exclude models with the name longer than 32 symbold, 
                // because it is too long for the Symfony routing
                return $this->isEloquentJsModel($className) && strlen($className) <= 32;
            }
        );
    }

    /**
     * Prompt user to confirm.
     *
     * @return string
     */
    protected function isConfirmed()
    {
        return true;
    }

}
