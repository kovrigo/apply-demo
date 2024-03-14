<?php

namespace App\Settings;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use Illuminate\Support\Str;

class CustomizedPolicy
{
    use HandlesAuthorization;

    protected $policy;

    public function before($user, $ability)
    {
        // Get resource name
        $policyClass = class_basename(get_called_class());
        $resource = Str::replaceFirst('Policy', '', $policyClass);
        // Get settings for the resource
        $resource = apply()->settings->resources->$resource;
        if (!$resource) {
            return false;
        }
        $this->policy = $resource->policy;
        // Check super admin permissions
        if ($user->is_super_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $this->policy->can('viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, $model)
    {
        return $this->policy->can('view', $model);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $this->policy->can('create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, $model)
    {
        return $this->policy->can('update', $model);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, $model)
    {
        return $this->policy->can('delete', $model);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, $model)
    {
        return $this->delete($user, $model);
    }

    /**
     * Determine whether the user can force delete the model.
     */
    public function forceDelete(User $user, $model)
    {
        return false;
    }

}
