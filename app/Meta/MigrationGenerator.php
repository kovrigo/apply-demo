<?php

namespace App\Meta;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Composer;
use App\Meta\MigrationCreator;
use Illuminate\Filesystem\Filesystem;
use Artisan;
use Illuminate\Support\Arr;

class MigrationGenerator extends Generator
{

	public function generateResourceMigration($delete = false)
	{
        // Create migration
        $creator = new MigrationCreator(new Filesystem);
        $stub = $delete ? 'DeleteResourceMigration' : 'CreateResourceMigration';
        $modelClassSnake = Str::snake($this->class);
        $table = Str::plural($modelClassSnake);
        $prefix = $delete ? 'delete' : 'create';
        $name = $prefix . '_' . $table . '_' . str_random(16);        
        $options = [
            'table' => $table,
            'workflowable' => $this->meta['workflowable'] ? 'true' : 'false',
            'modelClassSnake' => $modelClassSnake,
        ];
        if (!$delete) {
            $options['respectsTenancy'] = $this->meta['respectsTenancy'] ? 'true' : 'false';
            $options['respectsOwnership'] = $this->meta['respectsOwnership'] ? 'true' : 'false';
            $options['translatableFields'] = $this->getTranslatableFields();
            $options['sortable'] = $this->meta['sortable'] ? 'true' : 'false';
        }
        $creator->createFromStub($name, $stub, $options);
        // Dump-autoload
        $composer = new Composer(new Filesystem);
        $composer->dumpAutoloads();
        // Migrate
        Artisan::call('migrate');
	}

    public function generateFieldMigration($delete = false)
    {
        // Create migration
        $creator = new MigrationCreator(new Filesystem);
        $fieldClass = $this->meta['fieldClass'];
        $fieldName = $this->meta['fieldName'];
        $stub = $delete ? 'Delete' . $fieldClass . 'Migration' : 'Add' . $fieldClass . 'Migration';
        $stub = 'migrations/' . $stub;
        $modelClassSnake = Str::snake($this->class);
        $table = Str::plural($modelClassSnake);
        $prefix = $delete ? 'delete' : 'add';
        $name = $prefix . '_' . $fieldName . '_field_to_' . $table . '_table_' . str_random(16);
        $options = [
            'table' => $table,
            'field' => $fieldName,
        ];
        $creator->createFromStub($name, $stub, $options);
        // Dump-autoload
        $composer = new Composer(new Filesystem);
        $composer->dumpAutoloads();
        // Migrate
        Artisan::call('migrate');       
    }

    public function generateRelationMigration($delete = false)
    {
        // Create migration
        $creator = new MigrationCreator(new Filesystem);
        $relationType = $this->meta['relationType'];
        $relationTypeSnake = Str::snake($relationType);

        $fieldName = $this->meta['fieldName'];

        $modelClass = $this->class;
        $modelClassSnake = Str::snake($modelClass);
        $table = Str::plural($modelClassSnake);

        $relatedModelClasses = $this->meta['relatedClasses'];
        $relatedModelClass = $relatedModelClasses[0];
        $relatedModelClassSnake = Str::snake($relatedModelClass);
        $relatedTable = Str::plural($relatedModelClassSnake);

        $classes = array_merge([$modelClass], $relatedModelClasses);
        sort($classes);
        $sortedClasses = $classes;
        
        $classA = $sortedClasses[0];
        $classB = $sortedClasses[1];
        $modelClassASnake = Str::snake($classA);
        $modelClassBSnake = Str::snake($classB);

        $stub = $delete ? 'Delete' . $relationType . 'Migration' : 'Add' . $relationType . 'Migration';
        $stub = 'migrations/' . $stub;
        $prefix = $delete ? 'delete' : 'add';
        $name = $prefix . '_' . $relationTypeSnake . '_relation_on_' . $relatedTable . 
            '_to_' . $table . '_table_' . str_random(16);
        $options = [
            'table' => $table,
            'relatedModelClassSnake' => $relatedModelClassSnake,
            'modelClassASnake' => $modelClassASnake,
            'modelClassBSnake' => $modelClassBSnake,
            'fieldName' => $fieldName,
        ];
        $creator->createFromStub($name, $stub, $options);
        // Dump-autoload
        $composer = new Composer(new Filesystem);
        $composer->dumpAutoloads();
        // Migrate
        Artisan::call('migrate');
    }

}

