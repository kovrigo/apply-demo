<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth
Route::post('login', 'Auth\LoginController@login');
Route::middleware('auth:api')->post('logout', 'Auth\LoginController@logout');

// Eloquent API
Route::eloquent('ads', App\Ad::class);
Route::eloquent('advertising-platforms', App\AdvertisingPlatform::class);
// TODO: this name is too long for Symfony routing
//Route::eloquent('advertising-platform-call-to-action-types', App\AdvertisingPlatformCallToActionType::class);
Route::eloquent('advertising-platform-interests', App\AdvertisingPlatformInterest::class);
Route::eloquent('advertising-platform-transactions', App\AdvertisingPlatformTransaction::class);
Route::eloquent('applications', App\Application::class);
Route::eloquent('application-logs', App\ApplicationLog::class);
Route::eloquent('application-stages', App\ApplicationStage::class);
Route::eloquent('application-stage-types', App\ApplicationStageType::class);
Route::eloquent('application-workflows', App\ApplicationWorkflow::class);
Route::eloquent('benefits', App\Benefit::class);
Route::eloquent('call-to-action-types', App\CallToActionType::class);
Route::eloquent('chats', App\Chat::class);
Route::eloquent('chat-logs', App\ChatLog::class);
Route::eloquent('chat-messages', App\ChatMessage::class);
Route::eloquent('chat-message-logs', App\ChatMessageLog::class);
Route::eloquent('chat-message-states', App\ChatMessageState::class);
Route::eloquent('chat-message-workflows', App\ChatMessageWorkflow::class);
Route::eloquent('chat-states', App\ChatState::class);
Route::eloquent('chat-workflows', App\ChatWorkflow::class);
Route::eloquent('companies', App\Company::class);
Route::eloquent('company-sizes', App\CompanySize::class);
Route::eloquent('compensations', App\Compensation::class);
Route::eloquent('compensation-types', App\CompensationType::class);
Route::eloquent('compensation-units', App\CompensationUnit::class);
Route::eloquent('contacts', App\Contact::class);
Route::eloquent('contact-methods', App\ContactMethod::class);
Route::eloquent('conversions', App\Conversion::class);
Route::eloquent('cpm-geo-multipliers', App\CpmGeoMultiplier::class);
Route::eloquent('creatives', App\Creative::class);
Route::eloquent('creative-logs', App\CreativeLog::class);
Route::eloquent('creative-states', App\CreativeState::class);
Route::eloquent('creative-workflows', App\CreativeWorkflow::class);
Route::eloquent('currencies', App\Currency::class);
Route::eloquent('custom-functional-areas', App\CustomFunctionalArea::class);
Route::eloquent('custom-functional-area-groups', App\CustomFunctionalAreaGroup::class);
Route::eloquent('custom-workplace-types', App\CustomWorkplaceType::class);
Route::eloquent('degrees', App\Degree::class);
Route::eloquent('employments', App\Employment::class);
Route::eloquent('experiences', App\Experience::class);
Route::eloquent('faqs', App\Faq::class);
Route::eloquent('functional-areas', App\FunctionalArea::class);
Route::eloquent('functional-area-groups', App\FunctionalAreaGroup::class);
Route::eloquent('image-classes', App\ImageClass::class);
Route::eloquent('industries', App\Industry::class);
Route::eloquent('info-blocks', App\InfoBlock::class);
Route::eloquent('info-block-items', App\InfoBlockItem::class);
Route::eloquent('interests', App\Interest::class);
Route::eloquent('jobs', App\Job::class);
Route::eloquent('job-logs', App\JobLog::class);
Route::eloquent('job-skill-levels', App\JobSkillLevel::class);
Route::eloquent('job-states', App\JobState::class);
Route::eloquent('job-workflows', App\JobWorkflow::class);
Route::eloquent('numbers', App\Number::class);
Route::eloquent('pages', App\Page::class);
Route::eloquent('places', App\Place::class);
Route::eloquent('place-groups', App\PlaceGroup::class);
Route::eloquent('place-group-addresses', App\PlaceGroupAddress::class);
Route::eloquent('popularity-ratings', App\PopularityRating::class);
Route::eloquent('profiles', App\Profile::class);
Route::eloquent('profile-skill-levels', App\ProfileSkillLevel::class);
Route::eloquent('quizzes', App\Quiz::class);
Route::eloquent('quiz-answers', App\QuizAnswer::class);
Route::eloquent('quiz-completions', App\QuizCompletion::class);
Route::eloquent('quiz-questions', App\QuizQuestion::class);
Route::eloquent('reads', App\Read::class);
Route::eloquent('resource-custom-settings', App\ResourceCustomSetting::class);
Route::eloquent('resource-settings', App\ResourceSetting::class);
Route::eloquent('skills', App\Skill::class);
Route::eloquent('skill-levels', App\SkillLevel::class);
Route::eloquent('tenants', App\Tenant::class);
Route::eloquent('transactions', App\Transaction::class);
Route::eloquent('triggers', App\Trigger::class);
Route::eloquent('users', App\User::class);
Route::eloquent('user-profiles', App\UserProfile::class);
Route::eloquent('workflows', App\Workflow::class);
Route::eloquent('workplaces', App\Workplace::class);
Route::eloquent('workplace-types', App\WorkplaceType::class);
Route::eloquent('articles', App\Article::class);
Route::eloquent('article-states', App\ArticleState::class);
Route::eloquent('article-workflows', App\ArticleWorkflow::class);
Route::eloquent('article-logs', App\ArticleLog::class);
Route::eloquent('departments', App\Department::class);
Route::eloquent('organizations', App\Organization::class);
Route::eloquent('cities', App\City::class);
Route::eloquent('job-page-templates', App\JobPageTemplate::class);

// Eloquent extenstions
// Job
Route::middleware('auth:api')->any('jobs/{job}/promote', 'Api\JobController@promote');
Route::middleware('auth:api')->any('jobs/{job}/stop-promotion', 'Api\JobController@stopPromotion');
Route::middleware('auth:api')->any('jobs/{job}/publish', 'Api\JobController@publish');
Route::middleware('auth:api')->any('jobs/{job}/unpublish', 'Api\JobController@unpublish');
Route::middleware('auth:api')->any('jobs/{job}/reserve', 'Api\JobController@reserve');
Route::middleware('auth:api')->any('jobs/{job}/reservable-amount', 'Api\JobController@reservableAmount');
Route::middleware('auth:api')->any('jobs/{job}/refund', 'Api\JobController@refund');
// Creative
Route::middleware('auth:api')->any('creatives/{creative}/promote', 'Api\CreativeController@promote');
Route::middleware('auth:api')->any('creatives/{creative}/stop-promotion', 'Api\CreativeController@stopPromotion');
Route::middleware('auth:api')->any('creatives/{creative}/refresh', 'Api\CreativeController@refresh');

// Full-text search
Route::middleware('auth:api')->any('search/jobs', 'Api\JobController@search');

// Logout
Route::any('login', 'Auth\LoginController@login');

// Apply
Route::any('apply/{tenant}', 'ApplyController@apply');