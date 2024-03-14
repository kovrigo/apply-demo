<?php

namespace Kovrigo\ProfileSkillLevel;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\ProfileSkillLevel as ProfileSkillLevelModel;

class ProfileSkillLevel extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'profile-skill-level';

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
		return $resource->profileSkillLevels()->with(['skill', 'skillLevel'])->get()->all();
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $value = json_decode($request->input($requestAttribute));
        $model::saved(function ($model) use ($value) {
	        // Delete old job skills
	        ProfileSkillLevelModel::where('profile_id', $model->id)->forceDelete();
	        // Add new job skills
	        foreach ($value as $profileSkill) {
	        	$profileSkillModel = new ProfileSkillLevelModel;
	        	$profileSkillModel->profile_id = $model->id;
	        	$profileSkillModel->skill_id = $profileSkill->skill->id;
	        	$profileSkillModel->skill_level_id = $profileSkill->skill_level->id;
	        	$profileSkillModel->save();
	        }
        });
        unset($request->$attribute);
        return;
    }

}
