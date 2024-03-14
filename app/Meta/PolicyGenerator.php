<?php

namespace App\Meta;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PolicyGenerator extends Generator
{

    public function deletePolicy()
    {
        $this->deleteFile('Policies/' . $this->class . 'Policy');
        if ($this->meta['workflowable']) {
            // Log
            $this->deleteFile('Policies/' . $this->class . 'LogPolicy');
            // State
            $this->deleteFile('Policies/' . $this->class . 'StatePolicy');
            // Workflow
            $this->deleteFile('Policies/' . $this->class . 'WorkflowPolicy');
        }
    }

	public function generatePolicy()
	{
        $content = $this->read('Policy');
        $basicAbilities = ['viewAny', 'view', 'create', 'update', 'delete'];
        $abilitiesWithoutModel = ['viewAny', 'create'];
        $abilities = '';
        $abilityStub = $this->read('Ability');
        $abilityStubWithoutModel = $this->read('AbilityWithoutModel');
        foreach ($basicAbilities as $ability) {
            $host_permission = !in_array($ability, $this->meta['hostAbilities']);
            $tenant_permission = !in_array($ability, $this->meta['tenantAbilities']);            
            if (!$host_permission || !$tenant_permission) {
                if (in_array($ability, $abilitiesWithoutModel)) {
                    $newContent = str_replace('<ability>', $ability, $abilityStubWithoutModel);
                    $host_permission = $host_permission ? 'parent::' . $ability . '($user)' : 'false';
                    $tenant_permission = $tenant_permission ? 'parent::' . $ability . '($user)' : 'false';
                    $newContent = str_replace('<host_permission>', $host_permission, $newContent);
                    $newContent = str_replace('<tenant_permission>', $tenant_permission, $newContent);
                    $abilities .= $newContent . "\n\t";
                } else {
                    $newContent = str_replace('<ability>', $ability, $abilityStub);
                    $host_permission = $host_permission ? 'parent::' . $ability . '($user, $model)' : 'false';
                    $tenant_permission = $tenant_permission ? 'parent::' . $ability . '($user, $model)' : 'false';
                    $newContent = str_replace('<host_permission>', $host_permission, $newContent);
                    $newContent = str_replace('<tenant_permission>', $tenant_permission, $newContent);
                    $abilities .= $newContent . "\n\t";
                }
            }
        }
        // Workflowable
        if ($this->meta['workflowable']) {
            // Add policies for workflows, states and logs
            $this->generateWorkflowPolicy();
            $this->generateLogPolicy();
            $this->generateStatePolicy();
            $workflowableResourceAbilities = $this->read('WorkflowableResourceAbilities');
            $abilities .= $workflowableResourceAbilities;
        }
        $content = str_replace('<abilities>', $abilities, $content);
        $this->write($content, 'Policies/<class>Policy');
	}	

    public function generateWorkflowPolicy()
    {
        $content = $this->read('WorkflowPolicy');
        $this->write($content, 'Policies/<class>WorkflowPolicy');
    }

    public function generateLogPolicy()
    {
        $content = $this->read('LogPolicy');
        $this->write($content, 'Policies/<class>LogPolicy');
    }

    public function generateStatePolicy()
    {
        $content = $this->read('StatePolicy');
        $this->write($content, 'Policies/<class>StatePolicy');
    }

    public function generateRelation()
    {
        $modelClass = $this->class;
        $relationType = $this->meta['relationType'];
        $relatedModelClasses = $this->meta['relatedClasses'];
        $relatedModelClass = $relatedModelClasses[0];
        switch ($relationType) {
            case 'OneToMany':
                $content = file_get_contents(base_path() . '/app/Policies/' . $relatedModelClass . 'Policy.php');
                $stub = $this->read('Add');
                $relation = str_replace('<relatedModelClass>', $relatedModelClass, $stub);
                $pos = strrpos($content, '}');
                $newContent = substr_replace($content, $relation, $pos - 1, 0);
                $this->write($newContent, 'Policies/' . $relatedModelClass . 'Policy');
                break;
            case 'ManyToMany':
                $content1 = file_get_contents(base_path() . '/app/Policies/' . $modelClass . 'Policy.php');
                $content2 = file_get_contents(base_path() . '/app/Policies/' . $relatedModelClass . 'Policy.php');
                $stub = $this->read('Attach', false, true);
                $relation1 = str_replace('<modelClass>', $modelClass, $stub);
                $relation1 = str_replace('<relatedModelClass>', $relatedModelClass, $relation1);
                $relation2 = str_replace('<modelClass>', $relatedModelClass, $stub);
                $relation2 = str_replace('<relatedModelClass>', $modelClass, $relation2);
                $pos1 = strrpos($content1, '}');
                $newContent1 = substr_replace($content1, $relation1, $pos1 - 1, 0);
                $this->write($newContent1, 'Policies/' . $modelClass . 'Policy');
                $pos2 = strrpos($content2, '}');
                $newContent2 = substr_replace($content2, $relation2, $pos2 - 1, 0);  
                $this->write($newContent2, 'Policies/' . $relatedModelClass . 'Policy');
                break;
            case 'OneToManyPolymorphic':
                foreach ($relatedModelClasses as $relatedModelClass) {
                    $content = file_get_contents(base_path() . '/app/Policies/' . $relatedModelClass . 'Policy.php');
                    $stub = $this->read('Add', false, true);
                    $relation = str_replace('<modelClass>', $relatedModelClass, $stub);
                    $relation = str_replace('<relatedModelClass>', $modelClass, $relation);
                    $pos = strrpos($content, '}');
                    $newContent = substr_replace($content, $relation, $pos - 1, 0);
                    $this->write($newContent, 'Policies/' . $relatedModelClass . 'Policy');
                }
                break;
            default:
                break;
        }
    }

    public function deleteRelation()
    {
        $modelClass = $this->class;
        $relationType = $this->meta['relationType'];
        $relatedModelClasses = $this->meta['relatedClasses'];
        $relatedModelClass = $relatedModelClasses[0];
        switch ($relationType) {
            case 'OneToMany':
                $content = file_get_contents(base_path() . '/app/Policies/' . $relatedModelClass . 'Policy.php');
                preg_match('/public function add' . $relatedModelClass . '(.*?)}/s', $content, $matches);
                if (isset($matches[0])) {
                    $newContent = str_replace($matches[0], '', $content);
                }
                $this->write($newContent, 'Policies/' . $relatedModelClass . 'Policy');
                break;
            case 'ManyToMany':
                $content = file_get_contents(base_path() . '/app/Policies/' . $modelClass . 'Policy.php');
                preg_match('/public function attach' . $relatedModelClass . '(.*?)}/s', $content, $matches);
                if (isset($matches[0])) {
                    $newContent = str_replace($matches[0], '', $content);
                }
                $this->write($newContent, 'Policies/' . $modelClass . 'Policy');
                $content = file_get_contents(base_path() . '/app/Policies/' . $relatedModelClass . 'Policy.php');
                preg_match('/public function attach' . $modelClass . '(.*?)}/s', $content, $matches);
                if (isset($matches[0])) {
                    $newContent = str_replace($matches[0], '', $content);
                }
                $this->write($newContent, 'Policies/' . $relatedModelClass . 'Policy');
                break;
            case 'OneToManyPolymorphic':
                foreach ($relatedModelClasses as $relatedModelClass) {
                    $content = file_get_contents(base_path() . '/app/Policies/' . $relatedModelClass . 'Policy.php');
                    preg_match('/public function add' . $modelClass . '(.*?)}/s', $content, $matches);
                    if (isset($matches[0])) {
                        $newContent = str_replace($matches[0], '', $content);
                    }
                    $this->write($newContent, 'Policies/' . $relatedModelClass . 'Policy');
                }
                break;                
            default:
                break;
        }
    }

}

