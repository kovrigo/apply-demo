<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Meta\Extractor;
use App\Meta\Manager;
use Illuminate\Support\Str;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Helper\TableCell;

class ApplyManager extends Command
{

    protected $meta;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apply:manage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage apply resources';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $env = config('app.env');
        if ($env != 'local') {
            $this->error('This command can only be run in local environment');
            exit;
        }
        
        $this->meta = [];
        // Select resource to work with
        list($this->meta['class'], $isNew) = $this->selectResource();
        // If there is no such resource, ask to create a new one
        if ($isNew) {
            if (!$this->confirm('There is no such resource, do you wish to create a new one?')) {
                exit;
            }
            // Create new resource
            $this->meta['label'] = $this->ask('Resource label');
            $this->meta['singularLabel'] = $this->ask('Resource singular label');
            $this->meta['respectsTenancy'] = $this->confirm('Make resource respect tenancy?');
            $this->meta['workflowable'] = $this->confirm('Make resource workflowable?');
            if ($this->meta['workflowable']) {
                $this->meta['respectsOwnership'] = true;
                $this->info("Your resource will respect ownership by default. \nWatch Relations\UserProfile.php to see workflow relation.");
                $this->meta['operations'] = [];
                $addOperations = $this->confirm('Do you like to add operations? (Default operation is log)');
                if ($addOperations) {
                    $this->operationMenu();
                }
            } else {
                $this->meta['respectsOwnership'] = $this->confirm('Make resource respect ownership?');
            }
            $this->meta['sortable'] = $this->confirm('Make resource sortable?');
            $this->meta['searchableFields'] = $this->ask('Comma separated searchable fields (leave empty to skip)');
            $this->meta['registerMediaCollection'] = $this->confirm('Register media collection?');
            $this->meta['addTranslatableName'] = $this->confirm('Add translatable name?');
            $this->meta['addTranslatableDescription'] = false;
            if ($this->meta['addTranslatableName']) {
                $this->meta['addTranslatableDescription'] = $this->confirm('Add translatable description?');    
            }
            $this->meta['groupBy'] = $this->ask('Enter the field to group resource records (leave empty to skip)');
            if ($this->confirm('Set custom permissions?')) {
                $this->meta['hostAbilities'] = $this->selectAbilities('[Host] ');
                $this->meta['hostHiddenFields'] = $this->ask('[Host] Comma separated hidden fields (leave empty to skip)');
                $this->meta['hostReadonlyFields'] = $this->ask('[Host] Comma separated readonly fields (leave empty to skip)');
                $this->meta['tenantAbilities'] = $this->selectAbilities('[Tenant] ');
                $this->meta['tenantHiddenFields'] = $this->ask('[Tenant] Comma separated hidden fields (leave empty to skip)');
                $this->meta['tenantReadonlyFields'] = 
                    $this->ask('[Tenant] Comma separated readonly fields (leave empty to skip)');
            } else {
                $this->meta['hostAbilities'] = [];
                $this->meta['hostHiddenFields'] = '';
                $this->meta['hostReadonlyFields'] = '';
                $this->meta['tenantAbilities'] = [];
                $this->meta['tenantHiddenFields'] = '';
                $this->meta['tenantReadonlyFields'] = '';
            }
            Manager::makeResource($this->meta);
            $this->info('The resource has been created');
            exit;
        }
        $this->resourceMenu();
    }

    public function resourceMenu()
    {
        $actions = [
            'Add field',
            'Add relation',
            'Delete field',
            'Delete relation',
            'Delete resource',
            'Set default fields',
            'Exit',
        ];
        $selectedAction = $this->choice('Choose action:', $actions, count($actions) - 1);
        switch ($selectedAction) {
            case $actions[0]:
                $this->addField();
                $this->resourceMenu();
                break;
            case $actions[1]:
                $this->addRelation();
                $this->resourceMenu();
                break;
            case $actions[2]:
                $this->deleteField();
                $this->resourceMenu();
                break;
            case $actions[3]:
                $this->deleteRelation();
                $this->resourceMenu();
                break;
            case $actions[4]:
                if ($this->confirm('Are you sure you want to delete the entire resource?')) {
                    if ($this->confirm('Are you absolutelly positive?')) {
                        Manager::deleteResource($this->meta['class']);
                        $this->info('The resource has been deleted');
                        exit;
                    }
                }
                $this->resourceMenu();
                break;                
            case $actions[5]:
                $this->defaultFieldsMenu();
                break;
            case $actions[6]:
                exit;
                break;                
            default:
                break;
        }
    }

    public function operationMenu() 
    {
        $actions = [
            'Create Operation',
            'Back'
        ];

        $chosenAction = $this->choice('Choose action:', $actions);

        switch ($chosenAction) {
            case 'Create Operation':
                $this->operationCreator();
                break;
            case 'Back to resource':
                break;
            default:
                break;
        }
    }

    public function operationCreator()
    {
        $operationName = $this->ask('Type your operation name (snake_case)');
        $this->meta['operations'][$operationName] = ['fields' => []];
        $addFields = $this->confirm('Do you like to add fields to your operation?');
        if (!$addFields) {
            $this->operationMenu();
        } else {
            $this->addOperationField($operationName);
        }
    }

    public function addOperationField($operationName)
    {
        $classes = Manager::getFieldClasses();
        $fieldName = $this->ask("Operation's field name");
        $fieldClass = $this->choice("Operation's field class", $classes, 0);
        $fieldLabel = $this->ask("Operation's field label");
        
        $this->meta['operations'][$operationName]['fields'][] = [
            'name' => $fieldName,
            'class' => $fieldClass,
            'label' => $fieldLabel
        ];

        $actions = [
            'Add more fields',
            'Back to menu'
        ];

        $chosenAction = $this->choice('', $actions);

        switch ($chosenAction) {
            case 'Add more fields':
                $this->addOperationField($operationName);
                break;
            case 'Back to menu':
                $this->operationMenu();
                break;
            default:
                break;
        }
    }

    public function addField()
    {
        $classes = Manager::getFieldClasses();
        $this->meta['fieldClass'] = $this->choice('Field class:', $classes, 0);
        $this->meta['fieldName'] = $this->ask('Field name');
        $this->meta['fieldLabel'] = $this->ask('Field label');
        Manager::addField($this->meta);
        $this->info('The field has been added to the resource');
    }

    public function deleteField()
    {
        $fields = Extractor::getResourcesFields($this->meta['class']);
        $fieldNames = array_keys($fields);
        $this->meta['fieldName'] = $this->choice('Choose field to delete:', $fieldNames, 0);
        $this->meta['fieldClass'] = $fields[$this->meta['fieldName']];
        Manager::deleteField($this->meta);
        $this->info('The field has been removed from the resource');
    }

    public function addRelation()
    {
        $types = Manager::getRelationTypes();
        $this->meta['relationType'] = $this->choice('Relation type:', $types, 0);
        // Polymorphic
        if ($this->meta['relationType'] == 'OneToManyPolymorphic') {
            // Ask for multiple related resource classes
            list($relatedClasses, $isNew) = $this->selectResource('Related resources', true, true);
            $this->meta['relatedClasses'] = $relatedClasses;
            $this->meta['fieldName'] = $this->ask('Generalized relation name (like "addressable", "contactable" etc.)');
            $this->meta['fieldLabel'] = $this->ask('Generalized relation label (like "Owner", "Reference" etc.)');
        } else {
            // Other
            // Ask for related resource class
            list($relatedClasses, $isNew) = $this->selectResource('Related resources', true);
            $this->meta['relatedClasses'] = [$relatedClasses];
            $this->meta['fieldName'] = '';
            $this->meta['fieldLabel'] = '';
        }
        Manager::addRelation($this->meta);
        $this->info('The relation has been added to the resource');
    }

    public function deleteRelation()
    {
        $relations = Extractor::getResourcesRelations($this->meta['class']);
        $relationNames = array_keys($relations);
        if ($relationNames) {
            $this->meta['fieldName'] = $this->choice('Choose relation to delete:', $relationNames, 0);
            $relation = $relations[$this->meta['fieldName']];
            $this->meta['relationType'] = $relation['type'];
            $this->meta['relatedClasses'] = $relation['classes'];
            Manager::deleteRelation($this->meta);
            $this->info('The relaton has been removed from the resource and related resources');
        } else {
            $this->error('This resource contains no relations');
        }
    }

    public function defaultFieldsMenu($skipTenantSelection = false)
    {
        if (!$skipTenantSelection) {
            $this->meta['forTenant'] = !$this->confirm('Make changes to the host settings?');
        }
        list($useTabs, $fields) = Extractor::getResourcesDefaultFields($this->meta['class'], $this->meta['forTenant']);
        $this->meta['selectedFieldIndex'] = 0;
        $this->meta['selectedTabIndex'] = 0;
        $this->meta['useTabs'] = $useTabs;
        $this->meta['fields'] = $fields;
        $this->printFields();
        $actions = [
            'Reset', 
            'Make changes',
            'Back',
        ];
        $selectedAction = $this->choice('Choose action:', $actions, count($actions) - 1);
        switch ($selectedAction) {
            case $actions[0]:
                $this->resetDefaultFields();
                break;
            case $actions[1]:
                $this->changeDefaultFields();
                break;
            case $actions[2]:
                $this->resourceMenu();
                break;
            default:
                break;
        }
    }

    public function resetDefaultFields()
    {
        if ($this->confirm('Are you sure you want to reset default fields?')) {
            $useTabs = $this->confirm('Use tabs?');
            Manager::resetResourceDefaultFields($this->meta['class'], $useTabs, $this->meta['forTenant']);
        }
        $this->defaultFieldsMenu(true);
    }

    public function changeDefaultFields()
    {
        $actions = [
            'Select field ...',
            'Move field to ...',
            'Toggle index',
            'Toggle detail',
            'Toggle update',
            'Toggle create',
        ];
        $tabActions = [
            'Select tab ...',
            'Move tab to ...',
            'Move field to another tab ...',
            'Change tab label',
            'Add tab',
            'Remove tab',
        ];
        $allActions = array_merge($actions, $tabActions);
        if ($this->meta['useTabs']) {
            $actions = $allActions;
        }
        $actions[] = 'Save';
        $actions[] = 'Cancel';
        $selectedAction = $this->choice('Choose action:', $actions, count($actions) - 1);
        switch ($selectedAction) {
            case $allActions[0]:
                $this->meta['selectedFieldIndex'] = $this->ask('Type field index to select');
                $this->printFields();
                $this->changeDefaultFields();
                break;
            case $allActions[1]:
                $newIndex = $this->ask('Type new field index');
                $this->moveField($newIndex);
                $this->printFields();
                $this->changeDefaultFields();                
                break;
            case $allActions[2]:
                $this->toggleFieldVisibility('index');
                $this->printFields();
                $this->changeDefaultFields();
                break;
            case $allActions[3]:
                $this->toggleFieldVisibility('detail');
                $this->printFields();
                $this->changeDefaultFields();
                break;
            case $allActions[4]:
                $this->toggleFieldVisibility('update');
                $this->printFields();
                $this->changeDefaultFields();
                break;
            case $allActions[5]:
                $this->toggleFieldVisibility('create');
                $this->printFields();
                $this->changeDefaultFields();
                break;
            case $allActions[6]:
                $this->meta['selectedTabIndex'] = $this->ask('Type tab index to select');
                $this->printFields();
                $this->changeDefaultFields();
                break;
            case $allActions[7]:
                $newIndex = $this->ask('Type new tab index');
                $this->moveTab($newIndex);
                $this->printFields();
                $this->changeDefaultFields();                
                break;
            case $allActions[8]:
                $newIndex = $this->ask('Type tab index to move field to');
                $this->moveFieldToTab($newIndex);
                $this->printFields();
                $this->changeDefaultFields();                
                break;
            case $allActions[9]:
                $newLabel = $this->ask('New tab label (leave empty to skip)');
                if ($newLabel) {
                    $this->changeTabLabel($newLabel);
                    $this->printFields();                    
                }
                $this->changeDefaultFields();                
                break;
            case $allActions[10]:
                $label = $this->ask('Tab label');
                if ($label) {
                    $this->addTab($label);
                    $this->printFields();
                }
                $this->changeDefaultFields();                
                break;
            case $allActions[11]:
                if ($this->confirm('Are you sure you want to remove this tab?')) {
                    if ($this->removeTab()) {
                        $this->printFields();
                    }
                }
                $this->changeDefaultFields();                
                break;
            case 'Save':
                Manager::setResourceDefaultFieldsFromTemplate($this->meta['class'], $this->meta['fields'], 
                    $this->meta['useTabs'], $this->meta['forTenant']);
                $this->defaultFieldsMenu(true);
                break;
            case 'Cancel':
                $this->defaultFieldsMenu(true);
                break;
            default:
                break;
        }
    }

    public function moveField($newIndex)
    {
        $useTabs = $this->meta['useTabs'];
        $fields = $this->meta['fields'];
        $selectedFieldIndex = intval($this->meta['selectedFieldIndex']);
        $selectedTabIndex = intval($this->meta['selectedTabIndex']);
        if ($useTabs) {
            $selectedTabKey = array_keys($fields)[$selectedTabIndex];
            $newIndex = $newIndex >= count($fields[$selectedTabKey]) ? count($fields[$selectedTabKey]) - 1 : $newIndex;
            reposition_array_element($fields[$selectedTabKey], $selectedFieldIndex, $newIndex);
        } else {
            $newIndex = $newIndex >= count($fields) ? count($fields) - 1 : $newIndex;
            reposition_array_element($fields, $selectedFieldIndex, $newIndex);
        }
        $this->meta['selectedFieldIndex'] = $newIndex;
        $this->meta['fields'] = $fields;
    }

    public function addTab($label)
    {
        $fields = $this->meta['fields'];
        $fields[$label] = [];
        $this->meta['selectedTabIndex'] = count($fields) - 1;
        $this->meta['selectedFieldIndex'] = 0;
        $this->meta['fields'] = $fields;
    }

    public function removeTab()
    {
        $fields = $this->meta['fields'];
        $selectedTabIndex = intval($this->meta['selectedTabIndex']);
        $selectedTabKey = array_keys($fields)[$selectedTabIndex];
        if (count($fields[$selectedTabKey]) > 0) {
            $this->error('Move all fields to another tabs before removing this one!');
            return false;
        }
        unset($fields[$selectedTabKey]);
        $this->meta['selectedTabIndex'] = 0;
        $this->meta['selectedFieldIndex'] = 0;        
        $this->meta['fields'] = $fields;
        return true;
    }

    public function moveTab($newIndex)
    {
        $fields = $this->meta['fields'];
        $selectedTabIndex = intval($this->meta['selectedTabIndex']);
        $selectedTabKey = array_keys($fields)[$selectedTabIndex];
        $newIndex = $newIndex >= count($fields) ? count($fields) - 1 : $newIndex;
        reposition_array_element($fields, $selectedTabKey, $newIndex);
        $this->meta['selectedTabIndex'] = $newIndex;
        $this->meta['fields'] = $fields;
    }

    public function moveFieldToTab($newIndex)
    {
        $fields = $this->meta['fields'];
        $selectedTabIndex = intval($this->meta['selectedTabIndex']);
        $selectedFieldIndex = intval($this->meta['selectedFieldIndex']);
        $selectedTabKey = array_keys($fields)[$selectedTabIndex];
        if ($newIndex >= count($fields) || $newIndex == $selectedTabIndex) {
            return;
        }
        $field = $fields[$selectedTabKey][$selectedFieldIndex];
        //unset($fields[$selectedTabKey][$selectedFieldIndex]);
        unset_and_reindex($fields[$selectedTabKey], $selectedFieldIndex);
        $newTabKey = array_keys($fields)[$newIndex];        
        $fields[$newTabKey][] = $field;
        $this->meta['selectedTabIndex'] = $newIndex;
        $this->meta['selectedFieldIndex'] = count($fields[$newTabKey]) - 1;
        $this->meta['fields'] = $fields;
    }

    public function changeTabLabel($newLabel)
    {
        $fields = $this->meta['fields'];
        $selectedTabIndex = intval($this->meta['selectedTabIndex']);
        $selectedTabKey = array_keys($fields)[$selectedTabIndex];
        $fields = change_array_key($fields, $selectedTabKey, $newLabel);
        $this->meta['fields'] = $fields;
    }

    public function toggleFieldVisibility($form)
    {
        $useTabs = $this->meta['useTabs'];
        $fields = $this->meta['fields'];
        $selectedFieldIndex = intval($this->meta['selectedFieldIndex']);
        $selectedTabIndex = intval($this->meta['selectedTabIndex']);
        if ($useTabs) {
            $selectedTabKey = array_keys($fields)[$selectedTabIndex];
            $fields[$selectedTabKey][$selectedFieldIndex][$form] = !$fields[$selectedTabKey][$selectedFieldIndex][$form];
        } else {
            $fields[$selectedFieldIndex][$form] = !$fields[$selectedFieldIndex][$form];
        }
        $this->meta['fields'] = $fields;
    }

    public function printFields()
    {
        $useTabs = $this->meta['useTabs'];
        $fields = $this->meta['fields'];
        $selectedFieldIndex = intval($this->meta['selectedFieldIndex']);
        $selectedTabIndex = intval($this->meta['selectedTabIndex']);
        $table = new Table($this->output);
        $separator = new TableSeparator;
        $table->setHeaders([
            '#', 'name', 'index', 'detail', 'update', 'create',
        ]);
        $rows = [];
        if ($useTabs) {
            // Normalize indexes
            $selectedTabIndex = $selectedTabIndex >= count($fields) ? 0 : $selectedTabIndex;
            $selectedTabKey = array_keys($fields)[$selectedTabIndex];
            $selectedFieldIndex = $selectedFieldIndex >= count($fields[$selectedTabKey]) ? 0 : $selectedFieldIndex;
            $this->meta['selectedFieldIndex'] = $selectedFieldIndex;
            $this->meta['selectedTabIndex'] = $selectedTabIndex;
            // Print table
            $tabIndex = 0;
            foreach ($fields as $tabName => $tabFields) {
                if ($tabIndex > 0) {
                    $rows[] = $separator;
                }
                $tabIndexFormatted = $tabIndex == $selectedTabIndex ? "[$tabIndex]" : $tabIndex;
                $rows[] = [$tabIndexFormatted, new TableCell($tabName, ['colspan' => 5])];
                $rows[] = $separator;
                $fieldIndex = 0;
                foreach ($tabFields as $field) {
                    $fieldIndexFormatted = ($tabIndex == $selectedTabIndex && $fieldIndex == $selectedFieldIndex) ? 
                        "[$fieldIndex]" : $fieldIndex;
                    $rows[] = [
                        $fieldIndexFormatted, $field['field'], 
                        $field['index'] ? '' : 'x',
                        $field['detail'] ? '' : 'x',
                        $field['update'] ? '' : 'x',
                        $field['create'] ? '' : 'x',                        
                    ];
                    $fieldIndex += 1;
                }
                $tabIndex += 1;
            }
        } else {
            // Normalize indexes
            $selectedFieldIndex = $selectedFieldIndex >= count($fields) ? 0 : $selectedFieldIndex;
            $this->meta['selectedFieldIndex'] = $selectedFieldIndex;
            // Print table
            $fieldIndex = 0;
            foreach ($fields as $field) {
                $fieldIndexFormatted = $fieldIndex == $selectedFieldIndex ? 
                    "[$fieldIndex]" : $fieldIndex;
                $rows[] = [
                    $fieldIndexFormatted, $field['field'], 
                    $field['index'] ? '' : 'x',
                    $field['detail'] ? '' : 'x',
                    $field['update'] ? '' : 'x',
                    $field['create'] ? '' : 'x',
                ];
                $fieldIndex += 1;
            }
        }
        $table->setRows($rows);
        $table->render();
    }

    public function selectAbilities($prefix)
    {
        $selectedAbilities = [];
        $abilities = ['viewAny', 'view', 'create', 'update', 'delete'];
        do {
            $formattedAbilities = collect($abilities)->map(function ($ability) use ($selectedAbilities) {
                return $ability . (in_array($ability, $selectedAbilities) ? ' NOT ALLOWED   ' : '');
            })->all();
            $selectedItem = $this->choice($prefix . 'Choose ability to toggle:', array_merge($formattedAbilities, ['DONE']), count($abilities));
            $selectedAbility = collect($abilities)->first(function ($ability) use ($selectedItem) {
                return Str::startsWith($selectedItem, $ability);
            });
            if ($selectedAbility) {
                if (in_array($selectedAbility, $selectedAbilities)) {
                    $selectedAbilities = collect($selectedAbilities)->filter(function ($ability) use ($selectedAbility) {
                        return $ability != $selectedAbility;
                    })->all();
                } else {
                    $selectedAbilities[] = $selectedAbility;
                }
            }
        } while ($selectedItem !== 'DONE');
        return $selectedAbilities;
    }

    public function selectResource($prompt = 'Enter resource name', $forceExisting = false, $multiple = false)
    {
        $resources = Extractor::getResources();
        $selected = [];
        $selectMore = false;
        do {
            do {
                $selectedResourceClass = $this->anticipate($prompt, $resources->pluck('class')->all());
                $selectedResource = $resources->first(function ($resource) use ($selectedResourceClass) {
                    return $resource['class'] == $selectedResourceClass;
                });
                $isNew = is_null($selectedResource);
                if ($forceExisting && $isNew) {
                    $this->error("Resource doesn't exits, please try again");
                }
            } while ($forceExisting && $isNew);
            if ($multiple) {
                $selected[] = $selectedResourceClass;
                echo "Selected resources:\n";
                foreach ($selected as $class) {
                    echo " - " . $class . "\n";
                }
                echo "\n";
                $selectMore = $this->confirm('Select more resources?');
            } else {
                $selected = $selectedResourceClass;
            }
        } while ($multiple && $selectMore);
        return [$selected, $isNew];
    }

}
