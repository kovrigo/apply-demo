<?php

namespace App\Meta;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ResourceGenerator extends Generator
{

    public function deleteResource()
    {
        $this->deleteFile('Nova/' . $this->class);
        if ($this->meta['workflowable']) {
            $this->deleteFile('Nova/' . $this->class . 'Log');
            $this->deleteFile('Nova/' . $this->class . 'State');
            $this->deleteFile('Nova/' . $this->class . 'Workflow');
        }
    }

	public function generateResource()
	{
        $content = $this->read('Resource');
		// Sortable
        $traits = [];
        $uses = [];		
        if ($this->meta['sortable']) {
        	$traits[] = "use \\App\\Settings\\Traits\\HasSortableRows;";
        	//$uses[] = "use OptimistDigital\\NovaSortable\\Traits\\HasSortableRows;";
        }
        // Searchable
        $fields = $this->formatFields($this->meta['searchableFields']);
        // Group by
        $options = '';
        if ($this->meta['groupBy']) {
        	$options = 'public static $optionsGroup = \'' . $this->meta['groupBy'] . '\';';
        }
        // Workflowable
        $extends = 'CustomizedResource';
        if ($this->meta['workflowable']) {
            $extends = 'WorkflowableResource';
            // Add resources for workflows, states and logs
            $this->generateWorkflowResource();
            $this->generateLogResource();
            $this->generateStateResource();
        }
        // Resource
        $uses = implode("\n", $uses);
        $traits = implode("\n\t", $traits);
        $content = str_replace('<extends>', $extends, $content);
        $content = str_replace('<uses>', $uses, $content);
        $content = str_replace('<traits>', $traits, $content);
        $content = str_replace('<fields>', $fields, $content);
        $content = str_replace('<options>', $options, $content);
        $this->write($content, 'Nova/<class>');
    }

    public function generateWorkflowResource()
    {
        $content = $this->read('WorkflowResource');
        $this->write($content, 'Nova/<class>Workflow');
    }

    public function generateLogResource()
    {
        $content = $this->read('LogResource');
        $this->write($content, 'Nova/<class>Log');
    }

    public function generateStateResource()
    {
        $content = $this->read('StateResource');
        $this->write($content, 'Nova/<class>State');
    }

}

