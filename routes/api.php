<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FreeTestController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Controller;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middle
|
*/
    Route::get('api/authorise', [Controller::class, 'authorise'])->name('login');
    Route::post('user-delete', [SiteController::class, 'user_delete'])->middleware('api');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login_otp', [AuthController::class, 'login_otp']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/admin_login_otp', [AuthController::class, 'login_otp']);
    Route::post('/admin_login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
    Route::post('/profile-update', [AuthController::class, 'profile']);
});



Route::group([
    'middleware' => ['api','role:admin'],

], function ($router) {

    //free test

    Route::get('/free_test', [FreeTestController::class, 'index']);
    Route::get('/free_test/{id}', [FreeTestController::class, 'getTest']);
    Route::post('/free_test', [FreeTestController::class, 'store']); 
    Route::put('/free_test/{id}', [FreeTestController::class, 'update']); 
    Route::delete('/free_test/{id}', [FreeTestController::class, 'deleteTestQue']); 

    //skil
    Route::get('/skill', [SkillController::class, 'index']);
    Route::get('/skill/{id}', [SkillController::class, 'getSkill']);
    Route::post('/skill', [SkillController::class, 'store']); 
    Route::put('/skill/{id}', [SkillController::class, 'update']); 
    Route::delete('/skill/{id}', [SkillController::class, 'deleteSkill']); 

    //milestone
    Route::get('/milestone', [MilestoneController::class, 'index']);
    Route::get('/milestone/{id}', [MilestoneController::class, 'getMilestone']);
    Route::post('/milestone', [MilestoneController::class, 'store']); 
    Route::put('/milestone/{id}', [MilestoneController::class, 'update']); 
    Route::delete('/milestone/{id}', [MilestoneController::class, 'deleteMilestone']); 




    //activity
    Route::get('/activity', [ActivityController::class, 'index']);
    Route::get('/activity/{id}', [ActivityController::class, 'getActivity']);
    Route::post('/activity', [ActivityController::class, 'store']); 
    Route::post('/activity/{id}', [ActivityController::class, 'update']); 
    Route::delete('/activity/{id}', [ActivityController::class, 'deleteActivity']); 



    //activity
    Route::get('/level', [LevelController::class, 'index']);
    Route::get('/level/{id}', [LevelController::class, 'getLevel']);
    Route::post('/level', [LevelController::class, 'store']); 
    Route::post('/level/{id}', [LevelController::class, 'update']); 
    Route::delete('/level/{id}', [LevelController::class, 'deleteLevel']); 
    Route::delete('/level_option/{id}', [LevelController::class, 'deleteLevelOption']); 



    //csm
    Route::get('/csm', [CmsController::class, 'index']);
    Route::get('/csm/{id}', [CmsController::class, 'getcsm']);
    Route::post('/csm', [CmsController::class, 'store']); 
    Route::post('/csm/{id}', [CmsController::class, 'update']); 
    Route::delete('/csm/{id}', [CmsController::class, 'deleteCsm']); 
    Route::delete('/csm_option/{id}', [CmsController::class, 'deleteCsmOption']); 

    Route::get('/csm-user/{id}', [CmsController::class, 'getcsmUsers']);
    Route::get('/myUser/{id}/detail', [CmsController::class, 'getCsmUserProfile']);
    Route::post('update-user-csm', [CmsController::class, 'updateCsmUser']);
    Route::get('/user/{id}/progress', [CmsController::class, 'userProgress']);
    Route::get('user/{id}/chart', [CmsController::class, 'userChart']);
    Route::get('user/{id}/age-chart', [CmsController::class, 'ageChart']);
    Route::get('user/{id}/age', [CmsController::class, 'user_age']);
    Route::get('users', [CmsController::class, 'allUsers']);


    Route::post('custom-plan', [CmsController::class, 'addCustomPlan']);
    Route::get('custom-plan/{user_id}', [CmsController::class, 'getUserCustomPlan']);


    
});






Route::group([
    'middleware' => 'api',
    'prefix' => 'site'

], function ($router) {


    Route::post('/free_test', [SiteController::class, 'free_test']); 
    Route::get('/age_group', [SiteController::class, 'age_group']); 
    Route::get('/mail/{user_id}', [SiteController::class, 'mail']); 
    Route::get('/packages', [SiteController::class, 'getPackages']); 
    Route::get('/free_packages', [SiteController::class, 'getFreePackages']); 
    Route::post('/submit-free-test', [SiteController::class, 'submitFreeTest']); 
    Route::post('/submit-free-test-result', [SiteController::class, 'submitFreeTestActivty']); 
    Route::post('/query', [SiteController::class, 'test_query']); 



});



Route::group(['middleware' => ['api','auth']], function ($router) {


    Route::post('/create_order', [OrderController::class, 'create_order']); 

    Route::get('/order/{id}', [OrderController::class, 'getOrder']); 
    Route::post('/order/{id}/update', [OrderController::class, 'updateOrder']);
    Route::get('userPackage', [MainController::class, 'userPackage']); 
   Route::get('assign-user/{user_id}', [OrderController::class, 'assignUserCsm']); 

   // User Activity
    Route::get('skills',[MainController::class,'getskills']);
    Route::get('activities/{id}',[MainController::class,'get_activities']);
    Route::get('activity-level/{id}',[MainController::class,'get_activity_level']);
    Route::get('single-level/{id}',[MainController::class,'single_level']);
    Route::post('level-submit',[MainController::class,'level_submit']);
    Route::get('activity-calendar',[MainController::class,'actvity_calendar']);
    Route::get('day-activity/{day}',[MainController::class,'day_activity']);
    Route::get('ongoing-activity',[MainController::class,'pending_activity']);
    Route::get('completed-activity',[MainController::class,'completed_activity']);
    Route::get('upcoming-activity',[MainController::class,'upcoming_activity']);
        Route::get('/user-progress', [MainController::class, 'userProgress']);
    Route::get('/user-age', [MainController::class, 'user_age']);
    Route::post('/user-age', [MainController::class, 'user_age']);
    Route::get('/user-free-package', [MainController::class, 'user_free_package']);
    Route::get('/chart', [MainController::class, 'userChart']);
    Route::get('/age-chart', [MainController::class, 'ageChart']);
    Route::get('/age', [MainController::class, 'get_user_age']);
  


});




Route::group([
    'middleware' => ['api','role:csm'],
    'prefix' => 'csmUser'
], function ($router) {


    Route::get('myUser', [CmsController::class, 'getCsmUser']); 
    Route::get('my-profile', [CmsController::class, 'myProfile']); 
    Route::get('myUser/{id}/detail', [CmsController::class, 'getCsmUserProfile']);
    Route::get('/user/{id}/progress', [CmsController::class, 'userProgress']);
    Route::post('/update-package', [CmsController::class, 'updateUserPackage']);
        Route::get('user/{id}/chart', [CmsController::class, 'userChart']);
        Route::get('user/{id}/age-chart', [CmsController::class, 'ageChart']);
    Route::get('user/{id}/age', [CmsController::class, 'user_age']);
   Route::post('/profile-update', [CmsController::class, 'profile']);

    Route::post('custom-plan', [CmsController::class, 'addCustomPlan']);
    Route::get('custom-plan/{user_id}', [CmsController::class, 'getUserCustomPlan']);
 Route::get('/milestone', [MilestoneController::class, 'index']);

});