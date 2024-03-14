<?php

namespace App\Meta;

class MigrationCreator extends \Illuminate\Database\Migrations\MigrationCreator
{

	public function createFromStub($name, $stub, $options)
	{
		$path = base_path() . '/database/migrations';
        $this->ensureMigrationDoesntAlreadyExist($name);
        $stub = $this->getStubByName($stub);
        $this->files->put(
            $path = $this->getPath($name, $path),
            $this->populateStubWithOptions($name, $stub, $options)
        );
        return $path;
	}

    protected function getStubByName($stub)
    {
    	$path = base_path() . '/app/Meta/stubs';
        return $this->files->get($path . '/' . $stub . '.stub');
    }

    protected function populateStubWithOptions($name, $stub, $options)
    {
        $stub = str_replace('DummyClass', $this->getClassName($name), $stub);
        foreach ($options as $key => $value) {
        	$stub = str_replace('<' . $key . '>', $value, $stub);
        }
        return $stub;
    }

}

