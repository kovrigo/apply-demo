<?php

namespace Kovrigo\JobSkillLevel;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\JobSkillLevel as JobSkillLevelModel;

class JobSkillLevel extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'job-skill-level';

    public function skills($skills)
    {
    	return $this->withMeta([
    		'skills' => $skills,
    	]);
    }

    public function levels($levels)
    {
    	return $this->withMeta([
    		'levels' => $levels,
    	]);
    }

    public function resolveAttribute($resource, $attribute = null)
    {
    	if (is_null($resource->id)) {
    		return [];
    	}
		return $resource->jobSkillLevels()->with(['skill', 'skillLevel'])->get()->all();
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $value = json_decode($request->input($requestAttribute));
        $model::saved(function ($model) use ($value) {
	        // Delete old job skills
	        JobSkillLevelModel::where('job_id', $model->id)->forceDelete();
	        // Add new job skills
	        foreach ($value as $jobSkill) {
	        	$jobSkillModel = new JobSkillLevelModel;
	        	$jobSkillModel->job_id = $model->id;
	        	$jobSkillModel->skill_id = $jobSkill->skill->id;
	        	$jobSkillModel->skill_level_id = $jobSkill->skill_level->id;
	        	$jobSkillModel->required = $jobSkill->required;
	        	$jobSkillModel->save();
	        }
        });
        unset($request->$attribute);
        return;
    }

}
