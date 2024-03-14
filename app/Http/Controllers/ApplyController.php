<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Job;
use App\Profile;
use App\Application;
use App\Degree;
use App\Contact;
use App\ContactMethod;
use App\Tenant;
use App\ApplicationStage;
use App\ApplicationLog;
use App\ApplicationWorkflow;
use App\Ad;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Denismitr\Translit\Translit;
use Auth;

class ApplyController extends Controller
{

    public function apply(Request $request, $tenant)
    {
    	$tenant = Tenant::where('code', $tenant)->firstOrFail();
        Auth::loginUsingId($tenant->system_user_id);

	    $request->validate([
	        'job_id' => 'sometimes|required|exists:jobs,id',
	        'family_name' => 'required',
	        'given_name' => 'required',
	        'birthday' => 'sometimes|required|date',
	    ]);

	    // Get job
	    $job = Job::where('id', $request->job_id)->firstOrFail();
	    // Create profile
	    $profile = new Profile;
	    // Get degree
	    $degree = Degree::where('google_talent_solution_id', $request->degree)->first();
	    $profile->family_name = $request->family_name;
	    $profile->given_name = $request->given_name;
	    $profile->middle_name = $request->middle_name;
	    $profile->city = $request->city;
	    $profile->birthday = $request->birthday ? Carbon::createFromFormat('d.m.Y', $request->birthday) : null;
	    $profile->relocation = $request->relocation == 'true';
	    $profile->degree_id = optional($degree)->id;
        $profile->cv_url = $request->cv_url;
	    $profile->save();
	   	// Attach CV if present
	   	if ($request->hasFile('cv') && $request->file('cv')->isValid()) {
	   		$profile->addMedia($request->cv)->toMediaCollection('cv', 'public');
	   	}
	    // Parse contacts from mulpipart-form-data request
	    $contacts = json_decode($request->contacts);
	    // Create contacts
	    if ($contacts) {
	    	foreach ($contacts as $contact) {
	    		// Get contact method
	    		$contactMethod = ContactMethod::where('google_talent_solution_id', $contact->contact_method)->first();
	    		// Create contact
	    		if (!is_null($contactMethod)) {
		    		$newContact = new Contact;
		    		$newContact->contact = $contact->contact;
		    		$newContact->contactMethod()->associate($contactMethod);
		    		$newContact->save();
		    		$profile->contacts()->save($newContact);
	    		}
	    	}
    	}
    	// Create application
        $application = null;
        if (!is_null($job)) {
            $application = new Application;
            $application->job()->associate($job);
            // TODO: get ad_id from UTM
            //$application->ad_id = $request->ad_id;
            $application->profile()->associate($profile);
            $application->save();
        }
        return $application;
    }

}
