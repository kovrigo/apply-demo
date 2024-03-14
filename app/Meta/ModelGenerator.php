<?php

namespace App\Meta;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ModelGenerator extends Generator
{

    public function deleteModel()
    {
        $this->deleteFile($this->class);
        $this->deleteFile('Media/' . $this->class);
        $this->deleteFile('Relations/' . $this->class);
        $this->deleteFile('Restrictions/' . $this->class);
        $this->deleteFile('Sortable/' . $this->class);
        $this->deleteFile('Translatable/' . $this->class);
        if ($this->meta['workflowable']) {
            // Log
            $this->deleteFile($this->class . 'Log');
            $this->deleteFile('Relations/' . $this->class . 'Log');
            $this->deleteFile('Restrictions/' . $this->class . 'Log');
            // State
            $this->deleteFile($this->class . 'State');
            $this->deleteFile('Relations/' . $this->class . 'State');
            $this->deleteFile('Restrictions/' . $this->class . 'State');
            $this->deleteFile('Translatable/' . $this->class . 'State');
            // Workflow
            $this->deleteFile($this->class . 'Workflow');
            $this->deleteUserProfileWorkflowRelation();
        }
    }

	public function generateModel()
	{
        $content = $this->read('Model');
        $traits = [];
        $implements = [];
        $uses = [];
        if ($this->meta['respectsTenancy']) {
        	$traits[] = "use \\App\\Tenancy\\Traits\\RespectsTenancy;";
        }
        if ($this->meta['workflowable']) {
        	$traits[] = "use \\App\\Workflows\\Traits\\Workflowable;";
        	// Add models for workflows, states and logs
            $this->generateWorkflowModel();
            $this->generateUserProfileWorkflowRelation();
            $this->generateLogModel();
            $this->generateStateModel();
        }
        if ($this->meta['respectsOwnership']) {
            $traits[] = "use \\App\\Traits\\RespectsOwnership;";
        }
        if ($this->meta['addTranslatableName']) {
        	$traits[] = "use \\App\\Translatable\\" . $this->class . ";";
        	$this->generateTranslatable();
        }        
        if ($this->meta['sortable']) {
        	$traits[] = "use \\App\\Sortable\\" . $this->class . ";";
        	$uses[] = "use Spatie\\EloquentSortable\\Sortable;";
        	$implements[] = "Sortable";
        	$this->generateSortable();
        }
        if ($this->meta['registerMediaCollection']) {
        	$traits[] = "use \\App\\Media\\" . $this->class . ";";
        	$uses[] = "use Spatie\\MediaLibrary\\HasMedia\\HasMedia;";
        	$implements[] = "HasMedia";
			$this->generateMedia();
        }
        // Relations
        $traits[] = "use \\App\\Relations\\" . $this->class . ";";
        $this->generateRelations();
        // Restrictions
        $traits[] = "use \\App\\Restrictions\\" . $this->class . ";";
        $this->generateRestrictions();
        // Model
        $uses = implode("\n", $uses);
        $implements = implode(", ", $implements);
        $implements = $implements != '' ? 'implements ' . $implements : '';
        $traits = implode("\n\t", $traits);
        $content = str_replace('<uses>', $uses, $content);
        $content = str_replace('<implements>', $implements, $content);
        $content = str_replace('<traits>', $traits, $content);
        $this->write($content, '<class>');
	}	

	public function generateRelations()
	{
        $content = $this->read('Relations');
        $this->write($content, 'Relations/<class>');
	}

	public function generateRestrictions()
	{
        $content = $this->read('Restrictions');
        $host_hidden = $this->formatFields($this->meta['hostHiddenFields']);
        $tenant_hidden = $this->formatFields($this->meta['tenantHiddenFields']);
        $host_readonly = $this->formatFields($this->meta['hostReadonlyFields']);
        $tenant_readonly = $this->formatFields($this->meta['tenantReadonlyFields']);
        $fields = $this->generateRestrictedFields($this->meta);
        $content = str_replace('<host_hidden>', $host_hidden, $content);
        $content = str_replace('<tenant_hidden>', $tenant_hidden, $content);
        $content = str_replace('<host_readonly>', $host_readonly, $content);
        $content = str_replace('<tenant_readonly>', $tenant_readonly, $content);
        $content = str_replace('<fields>', $fields, $content);
        $this->write($content, 'Restrictions/<class>');
	}

	public function generateRestrictedFields()
	{
        $fieldStub = $this->read('RestrictedField');
        $hostReadonlyFields = static::parseFields($this->meta['hostReadonlyFields']);
        $hostHiddenFields = static::parseFields($this->meta['hostHiddenFields']);
        $tenantReadonlyFields = static::parseFields($this->meta['tenantReadonlyFields']);
        $tenantHiddenFields = static::parseFields($this->meta['tenantHiddenFields']);
        $attributes = array_unique(array_merge($hostReadonlyFields, $hostHiddenFields, 
            $tenantReadonlyFields, $tenantHiddenFields));
        $fieldsContent = '';
        foreach ($attributes as $attributeKey) {
            $attribute = Str::studly($attributeKey);
            $host_hidden = in_array($attributeKey, $hostHiddenFields) ? 'null' : '$value';
            $tenant_hidden = in_array($attributeKey, $tenantHiddenFields) ? 'null' : '$value';
            $host_readonly = in_array($attributeKey, $hostReadonlyFields) ? '' : 
                '$this->attributes[\'' . $attributeKey . '\'] = $value;';
            $tenant_readonly = in_array($attributeKey, $tenantReadonlyFields) ? '' : 
                '$this->attributes[\'' . $attributeKey . '\'] = $value;';
            $fieldContent = str_replace('<attribute>', $attribute, $fieldStub);
            $fieldContent = str_replace('<attribute>', $attribute, $fieldContent);
            $fieldContent = str_replace('<host_hidden>', $host_hidden, $fieldContent);
            $fieldContent = str_replace('<tenant_hidden>', $tenant_hidden, $fieldContent);
            $fieldContent = str_replace('<host_readonly>', $host_readonly, $fieldContent);
            $fieldContent = str_replace('<tenant_readonly>', $tenant_readonly, $fieldContent);
            $fieldsContent .= $fieldContent . "\n\n";
        }
        return $fieldsContent;
	}

	public function generateSortable()
	{
        $content = $this->read('Sortable');
        $this->write($content, 'Sortable/<class>');        
	}

	public function generateTranslatable()
	{
        $content = $this->read('Translatable');
        $fields = $this->getTranslatableFields();
		$content = str_replace('<fields>', $fields, $content);
        $this->write($content, 'Translatable/<class>');
	}

	public function generateMedia()
	{
        $content = $this->read('Media');
		$content = str_replace('<collection>', 'photos', $content);
        $this->write($content, 'Media/<class>');
	}

	public function generateWorkflowModel()
	{
        $content = $this->read('WorkflowModel');
        $this->write($content, '<class>Workflow');
	}

    // generate UserProfile relation for workflow
    public function generateUserProfileWorkflowRelation() {
        $content = file_get_contents(base_path() . '/app/Relations/UserProfile.php');
        $stub = $this->read('relations/BelongsToMany', false, true);
        $modelWorkflowClass = "$this->class".'Workflow';
        $modelWorkflowClassCamelPlural = Str::plural(Str::camel($modelWorkflowClass));
        $workflowRelation = str_replace('<modelClass>', $modelWorkflowClass, $stub);
        $workflowRelation = str_replace('<modelClassCamelPlural>', $modelWorkflowClassCamelPlural, $workflowRelation);
        $pos = strrpos($content, '}');
        $newContent = substr_replace($content, $workflowRelation, $pos - 1, 0);
        $this->write($newContent, 'Relations/UserProfile');
    }

    public function generateLogModel()
    {
        // Model
        $content = $this->read('LogModel');
        $this->write($content, '<class>Log');
        // Relations
        $content = $this->read('LogModelRelations');
        $modelClassCamel = Str::camel($this->class);
        $content = str_replace('<modelClassCamel>', $modelClassCamel, $content);
        $this->write($content, 'Relations/<class>Log');
        // Restrictions
        $content = $this->read('LogModelRestrictions'); 
        $this->write($content, 'Restrictions/<class>Log');
    }

    public function generateStateModel()
    {
        $content = $this->read('StateModel');
        $this->write($content, '<class>State');
        // Relations
        $content = $this->read('StateModelRelations');
        $modelClassCamel = Str::camel($this->class);
        $modelClassPlural = Str::plural($modelClassCamel);
        $content = str_replace('<modelClassPlural>', $modelClassPlural, $content);
        $this->write($content, 'Relations/<class>State');
        // Restrictions
        $content = $this->read('StateModelRestrictions'); 
        $this->write($content, 'Restrictions/<class>State');
        // Translatable
        $content = $this->read('StateModelTranslatable'); 
        $this->write($content, 'Translatable/<class>State');        
    }

    public function generateRelation()
    {
        $modelClass = $this->class;
        $modelClassCamelPlural = Str::plural(Str::camel($modelClass));
        $relationType = $this->meta['relationType'];
        $fieldName = $this->meta['fieldName'];
        $relatedModelClasses = $this->meta['relatedClasses'];
        $relatedModelClass = $relatedModelClasses[0];
        $relatedModelClassCamel = Str::camel($relatedModelClass);
        $relatedModelClassCamelPlural = Str::plural(Str::camel($relatedModelClass));
        switch ($relationType) {
            case 'OneToMany':
                $content = file_get_contents(base_path() . '/app/Relations/' . $modelClass . '.php');
                $stub = $this->read('relations/belongsTo');
                $relation = str_replace('<relatedModelClass>', $relatedModelClass, $stub);
                $relation = str_replace('<relatedModelClassCamel>', $relatedModelClassCamel, $relation);
                $pos = strrpos($content, '}');
                $newContent = substr_replace($content, $relation, $pos - 1, 0);
                $this->write($newContent, 'Relations/' . $modelClass);
                $content = file_get_contents(base_path() . '/app/Relations/' . $relatedModelClass . '.php');
                $relation = $this->read('relations/hasMany');
                $pos = strrpos($content, '}');
                $newContent = substr_replace($content, $relation, $pos - 1, 0);
                $this->write($newContent, 'Relations/' . $relatedModelClass);
                break;
            case 'ManyToMany':
                $content1 = file_get_contents(base_path() . '/app/Relations/' . $modelClass . '.php');
                $content2 = file_get_contents(base_path() . '/app/Relations/' . $relatedModelClass . '.php');
                $stub = $this->read('relations/belongsToMany', false, true);
                $relation1 = str_replace('<modelClass>', $relatedModelClass, $stub);
                $relation1 = str_replace('<modelClassCamelPlural>', $relatedModelClassCamelPlural, $relation1);
                $relation2 = str_replace('<modelClass>', $modelClass, $stub);
                $relation2 = str_replace('<modelClassCamelPlural>', $modelClassCamelPlural, $relation2);
                $pos1 = strrpos($content1, '}');
                $newContent1 = substr_replace($content1, $relation1, $pos1 - 1, 0);
                $this->write($newContent1, 'Relations/' . $modelClass);
                $pos2 = strrpos($content2, '}');
                $newContent2 = substr_replace($content2, $relation2, $pos2 - 1, 0);  
                $this->write($newContent2, 'Relations/' . $relatedModelClass);
                break;
            case 'OneToManyPolymorphic':
                $content = file_get_contents(base_path() . '/app/Relations/' . $modelClass . '.php');
                $stub = $this->read('relations/morphTo');
                $relation = str_replace('<fieldName>', $fieldName, $stub);
                $pos = strrpos($content, '}');
                $newContent = substr_replace($content, $relation, $pos - 1, 0);
                $this->write($newContent, 'Relations/' . $modelClass);
                foreach ($relatedModelClasses as $relatedModelClass) {
                    $content = file_get_contents(base_path() . '/app/Relations/' . $relatedModelClass . '.php');
                    $stub = $this->read('relations/morphMany', false, true);
                    $relation = str_replace('<modelClass>', $modelClass, $stub);
                    $relation = str_replace('<fieldName>', $fieldName, $relation);
                    $relation = str_replace('<modelClassCamelPlural>', $modelClassCamelPlural, $relation);
                    $pos = strrpos($content, '}');
                    $newContent = substr_replace($content, $relation, $pos - 1, 0);
                    $this->write($newContent, 'Relations/' . $relatedModelClass);
                }
                break;
            default:
                break;
        }
    }

    public function deleteRelation()
    {
        $modelClass = $this->class;
        $modelClassCamelPlural = Str::plural(Str::camel($modelClass));
        $relationType = $this->meta['relationType'];
        $fieldName = $this->meta['fieldName'];
        $relatedModelClasses = $this->meta['relatedClasses'];
        $relatedModelClass = $relatedModelClasses[0];
        $relatedModelClassCamel = Str::camel($relatedModelClass);
        $relatedModelClassCamelPlural = Str::plural(Str::camel($relatedModelClass));
        switch ($relationType) {
            case 'OneToMany':
                $content = file_get_contents(base_path() . '/app/Relations/' . $modelClass . '.php');
                preg_match('/public function ' . $relatedModelClassCamel . '(.*?)}/s', $content, $matches);
                if (isset($matches[0])) {
                    $newContent = str_replace($matches[0], '', $content);
                }
                $this->write($newContent, 'Relations/' . $modelClass);
                $content = file_get_contents(base_path() . '/app/Relations/' . $relatedModelClass . '.php');
                preg_match('/public function ' . $modelClassCamelPlural . '(.*?)}/s', $content, $matches);
                if (isset($matches[0])) {
                    $newContent = str_replace($matches[0], '', $content);
                }
                $this->write($newContent, 'Relations/' . $relatedModelClass);                
                break;
            case 'ManyToMany':
                $content = file_get_contents(base_path() . '/app/Relations/' . $modelClass . '.php');
                preg_match('/public function ' . $relatedModelClassCamelPlural . '(.*?)}/s', $content, $matches);
                if (isset($matches[0])) {
                    $newContent = str_replace($matches[0], '', $content);
                }
                $this->write($newContent, 'Relations/' . $modelClass);
                $content = file_get_contents(base_path() . '/app/Relations/' . $relatedModelClass . '.php');
                preg_match('/public function ' . $modelClassCamelPlural . '(.*?)}/s', $content, $matches);
                if (isset($matches[0])) {
                    $newContent = str_replace($matches[0], '', $content);
                }
                $this->write($newContent, 'Relations/' . $relatedModelClass);
                break;
            case 'OneToManyPolymorphic':
                $content = file_get_contents(base_path() . '/app/Relations/' . $modelClass . '.php');
                preg_match('/public function ' . $fieldName . '(.*?)}/s', $content, $matches);
                if (isset($matches[0])) {
                    $newContent = str_replace($matches[0], '', $content);
                }
                $this->write($newContent, 'Relations/' . $modelClass);
                foreach ($relatedModelClasses as $relatedModelClass) {
                    $content = file_get_contents(base_path() . '/app/Relations/' . $relatedModelClass . '.php');
                    preg_match('/public function ' . $modelClassCamelPlural . '(.*?)}/s', $content, $matches);
                    if (isset($matches[0])) {
                        $newContent = str_replace($matches[0], '', $content);
                    }
                    $this->write($newContent, 'Relations/' . $relatedModelClass);
                }
                break;                
            default:
                break;
        }
    }

    public function deleteUserProfileWorkflowRelation() {
        $newContent = '';
        $modelWorkflowClass = Str::camel("$this->class".'Workflow');
        $content = file_get_contents(base_path() . '/app/Relations/UserProfile.php');
        preg_match('/public function ' . $modelWorkflowClass . '(.*?)}/s', $content, $matches);
        if (isset($matches[0])) {
            $newContent = str_replace($matches[0], '', $content);
        }
        $this->write($newContent, 'Relations/UserProfile');
    }
}

