<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SubDepartmentController;
use App\Http\Controllers\SchemeController;
use App\Http\Controllers\ImplementationController;
use App\Http\Controllers\DeptsecController;
use App\Http\Controllers\DeptdsController;
use App\Http\Controllers\DeptusController;
use App\Http\Controllers\GadsecController;
use App\Http\Controllers\GaddsController;
use App\Http\Controllers\GadusController;
use App\Http\Controllers\ImportSchemeController;
use App\Http\Controllers\EvaluationUserController;
use App\Http\Controllers\EvaldirController;
use App\Http\Controllers\EvaljointdirController;
use App\Http\Controllers\EvalddController;
use App\Http\Controllers\EvalroController;
use App\Http\Controllers\ODKController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProposalController;
use App\Mail\ForwardProposalMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Middleware\SessionTimeoutMiddleware;
use App\Http\Middleware\Referrer;
// use App\Http\Controllers\PowerBIController;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\TrackVisitors;


Route::post('lang/change', function (\Illuminate\Http\Request $request) {
    $locale = $request->locale;

    if (in_array($locale, ['en', 'gu'])) {
        session(['locale' => $locale]);
      //  \App::setLocale($locale); // optional, for immediate use
    }
    return response()->json(['status' => 'success']);
})->name('lang.change');


Route::middleware([SetLocale::class])->group(function () {


Route::any('/', function () {
	return view('layouts.index');
})->name('main-index');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Route::get('login-user', [LoginController::class, 'showLogin'])->name('login-user');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('refresh_captcha', [LoginController::class, 'refreshCaptcha'])->name('refresh_captcha');

Route::get('/cache-clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    dd("cache clear All");
});
Route::get('demo-mail',function(){
     $email = "jakex30161@fursee.com";
     $details = [
		   'scheme_name' =>  'test' ,
		   'department' => 'test d',
		   'hod_name' => 'test hod name'    
		];
	Mail::to($email)->send(new forwardproposalmail($details));

    // Mail::raw('This is a test email from Laravel.', function ($message) {
        // $message->to('yurtotuyda@gufum.com')
                // ->subject('Test Email');
    // });

    dd('done');
});

Route::get('/chatbot', function () {
    return view('chatbot');
});

Route::post('/chatbot/translate', [App\Http\Controllers\MessagesController::class, 'translateMessage'])->name('chatbot.translate');

Route::get('/messages/store', [App\Http\Controllers\MessagesController::class, 'store']);

Route::get('/publication_front_page', [App\Http\Controllers\HomeController::class, 'publicationfront'])->name('publication_front_page');
Route::get('/dept_publication/{id}', [App\Http\Controllers\HomeController::class, 'departmentPublication'])->name('dept_publication');
Route::get('get_the_publication_document/{id}/{document}',[ App\Http\Controllers\HomeController::class,'getthepublicationdocument'])->name('get_the_publication_document');
//Route::get('get_the_publication_document/{id}/{document}',[ App\Http\Controllers\HomeController::class,'getthepublicationdocument'])->name('get_the_publication_document');

Route::get('/contact-us', [App\Http\Controllers\HomeController::class, 'contactUs'])->name('contact-us');
Route::post('/search', [App\Http\Controllers\HomeController::class, 'search'])->name('search');
// Route::get('/powerbi', [PowerBIController::class, 'showReport']);


Route::get('/accessibility-statement', [App\Http\Controllers\SlugController::class, 'accessibility'])->name('accessibility-statement');
Route::get('/copyright-policy', [App\Http\Controllers\SlugController::class, 'copyright'])->name('copyright-policy');
Route::get('/feedback', [App\Http\Controllers\SlugController::class, 'feedback'])->name('feedback');
Route::post('/feedback', [App\Http\Controllers\SlugController::class, 'feedbackSubmit'])->name('feedback.submit');
Route::get('/help', [App\Http\Controllers\SlugController::class, 'help'])->name('help');
Route::get('/hyperlink-policy', [App\Http\Controllers\SlugController::class, 'hyperlinkPolicy'])->name('hyperlink-policy');
Route::get('/privacy-policy', [App\Http\Controllers\SlugController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-condition', [App\Http\Controllers\SlugController::class, 'termsCondition'])->name('terms-condition');
Route::get('/about_us', [App\Http\Controllers\SlugController::class, 'aboutUs'])->name('about-us');
Route::get('/organization-chart', [App\Http\Controllers\SlugController::class, 'charts'])->name('organization-chart');
Route::get('/whos-who', [App\Http\Controllers\SlugController::class, 'Whos'])->name('whos-who');
Route::get('/Dec', [App\Http\Controllers\SlugController::class, 'Dec'])->name('dec');
Route::get('/Ecc', [App\Http\Controllers\SlugController::class, 'Ecc'])->name('ecc');
Route::get('/government-resolution', [App\Http\Controllers\SlugController::class, 'governmentResolution'])->name('government-resolution');
Route::get('/media-gallery', [App\Http\Controllers\SlugController::class, 'mediaGallery'])->name('media-gallery');
Route::get('/vission-mission', function(){
    return view('common_pages.vission-mission');
})->name('vission-mission');


Route::get('/menu-item/{slug}', [App\Http\Controllers\SlugController::class, 'menuItem'])->name('slug');

//Route::group(['middleware'=>['auth','PreventBackHistory']], function(){
Route::middleware(['auth'])->group(function () {

    Route::middleware(['web',SessionTimeoutMiddleware::class, Referrer::class ])->group(function () {

    Route::get('dashboard',[ProposalController::class, 'dashboard'])->name('dashboard');
Route::post('/welcome-popup/seen', function () {
            Auth::user()->update(['welcome_popup' => false]);
            return response()->json(['status' => true]);
        })->name('welcome.popup.seen');
    Route::resource('schemes', SchemeController::class);
    Route::get('/proposal/{scheme_id}/final-report-pdf',
        [SchemeController::class, 'downloadFinalReportPdf']
    )->name('proposal.final-report.pdf');

    Route::post('districts',[SchemeController::class,'getdistricts'])->name('districts');
    Route::post('get_taluka',[SchemeController::class,'gettaluka'])->name('get_taluka');
    
    Route::post('edit_districts',[SchemeController::class,'editdistricts'])->name('schemes.edit_districts');
    Route::post('department_list',[SchemeController::class,'departmentlist'])->name('schemes.department_list');
    //Route::get('schemedata',[SchemeController::class,'schemedata'])->name('schemedata');
    //Route::get('request-proposal',[SchemeController::class,'requestproposal'])->name('schemes.request-proposal');
    //Route::post('create-proposal',[SchemeController::class,'createproposal'])->name('create-proposal');
   // Route::post('downloadpdf',[SchemeController::class,'downloadpdf'])->name('downloadpdf');
    Route::get('proposal_detail/{id}/{sendid}',[SchemeController::class,'proposaldetail'])->name('schemes.proposal_detail');
    Route::get('newproposal_detail/{id}',[SchemeController::class,'newproposaldetail'])->name('schemes.newproposal_detail');
    Route::get('beneficiaries-detail',[SchemeController::class,'beneficiariesDetails'])->name('schemes.beneficiariesDetail');


    //Profile
    Route::get('profile',[ImplementationController::class,'profile'])->name('profile');
    Route::post('update-profile-info',[ImplementationController::class,'updateInfo'])->name('UpdateInfo');
    Route::post('change-profile-picture',[ImplementationController::class,'updatePicture'])->name('PictureUpdate');
    Route::post('change-password',[ImplementationController::class,'changePassword'])->name('ChangePassword');

    Route::post('add_scheme',[SchemeController::class,'add_scheme'])->name('schemes.add_scheme');
    Route::post('save_last_item',[SchemeController::class,'saveLastitem'])->name('save_last_item');
    Route::get('proposal_edit/{id}',[SchemeController::class,'proposaledit'])->name('schemes.proposal_edit');
    Route::get('/schemes/{scheme_id}/get_the_file/{filename}', [SchemeController::class,'getthefile'])->name('schemes.get_the_file');

    Route::post('update_scheme',[SchemeController::class,'scheme_update'])->name('schemes.update_scheme');
    Route::post('onreload',[SchemeController::class,'onreload'])->name('schemes.onreload');
    Route::post('proposals/destory/{draft_id}',[SchemeController::class, 'destory'])->name('proposals.destroy');

    //Graph
    Route::get('get-label',[StageController::class,'labelItem'])->name('get-label');
    Route::get('get-scheme-count',[StageController::class,'schemeCount'])->name('get-scheme-count');
    Route::get('get-stage-count',[StageController::class,'stageCount'])->name('get-stage-count');
    Route::post('get-donutchart-count/{draft_id}',[StageController::class,'donutCount'])->name('get-donutchart-count');
    Route::post('get-donutchart-count-condept/{draft_id}',[StageController::class,'donutCount_con_dept'])->name('get-donutchart-count-condept');
    Route::get('detail-report', [StageController::class,'detailReport'])->name('detail_report');
    Route::get('summary_export/{draft_id?}', [StageController::class,'summaryReport'])->name('summary_export');
    Route::get('summary_export_all/{draft_id?}', [StageController::class,'summaryReportAll'])->name('summary_export_all');
    
    //Stage Create
    Route::resource('stages',StageController::class);
    Route::get('stages/downalod/{id}', [StageController::class, 'downloadPdf'])->name('stages.downalod');
    Route::get('stages/download_excel/{id}', [StageController::class, 'downloadExcel'])->name('stages.download_excel');

    Route::get('get_the_file/{id}/{scheme}',[EvalddController::class,'getthefile'])->name('stages.get_the_file');

    //Custom Filter
    Route::post('/custom_filter_items',[SchemeController::class,'customItems'])->name('custom_filter_items');
    Route::post('/custom_filter_condept_items',[SchemeController::class,'customdeptItems'])->name('custom_filter_condept_items');
    Route::get('proposals/{param?}',[ProposalController::class, 'proposalItems'])->name('proposals');
    Route::post('forwardtodept',[ProposalController::class,'forwardtodept'])->name('proposals.forwardtodept');

    //project Show
    Route::get('project_list', [App\Http\Controllers\DigitalProjectLibraryController::class,'projectList'])->name('project_list.index');
    Route::get('get_the_document/{id}/{document}',[ App\Http\Controllers\DigitalProjectLibraryController::class,'getthedocument'])->name('get_the_document');
    Route::get('get_images/{document}',[ App\Http\Controllers\DigitalProjectLibraryController::class,'getImages'])->name('get_images');
    Route::post('summary-digital-item',[App\Http\Controllers\DigitalProjectLibraryController::class, 'summary'])->name('summary-digital-item');
    Route::get('digital-project/{from_year}/{to_year}', [App\Http\Controllers\DigitalProjectLibraryController::class, 'projectExcel'])->name('digital-project');
    Route::get('digital-project-pdf/{from_year}/{to_year}', [App\Http\Controllers\DigitalProjectLibraryController::class, 'projectPDF'])->name('digital-project-pdf');

    Route::resource('department_hod', App\Http\Controllers\DepartmentHodController::class);
    Route::post('department_hod_param/{param?}',[App\Http\Controllers\DepartmentHodController::class, 'store'])->name('department_hod_param.store');
    Route::post('department_hod_destroy/destory/{id}',[App\Http\Controllers\DepartmentHodController::class, 'destroy'])->name('department_hod_destroy.destroy');
    });
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['isAdmin'::class,'auth',PreventBackHistory::class])->prefix('admin')->group(function(){
    Route::middleware(['web',SessionTimeoutMiddleware::class, Referrer::class ])->group(function () {
        Route::get('dashboard',[ProposalController::class, 'dashboard'])->name('dashboard');
        
        Route::get('dashboard',[AdminController::class,'index'])->name('admin.dashboard');
        Route::get('profile',[AdminController::class,'profile'])->name('admin.profile');
        // Route::get('settings',[AdminController::class,'settings'])->name('admin.settings');
        Route::post('update-profile-info',[AdminController::class,'updateInfo'])->name('adminUpdateInfo');
        Route::post('change-profile-picture',[AdminController::class,'updatePicture'])->name('adminPictureUpdate');
        Route::post('change-password',[AdminController::class,'changePassword'])->name('adminChangePassword');

        //ODK User Curl List
        // Route::get('odk_user_list',[AdminController::class, 'odk_user_list'])->name('admin.odk_user_list');
        // Route::post('add_odk_user',[AdminController::class, 'add_odk_user'])->name('admin.add_odk_user');
        // Route::post('status/{id}',[AdminController::class, 'status'])->name('admin.status');
        // Route::delete('odk_user/destory/{id}',[AdminController::class, 'odk_user_destory'])->name('odk_user.destroy');

        //Role List
        Route::get('role_list',[AdminController::class, 'role'])->name('admin.roles');
        Route::get('role/edit/{id}',[AdminController::class, 'role_edit'])->name('roles.edit');
        Route::put('role/update/{id}',[AdminController::class, 'role_update'])->name('roles.update');
        Route::delete('role/destory/{id}',[AdminController::class, 'role_destory'])->name('roles.destroy');
        Route::post('add_role',[AdminController::class, 'role_store'])->name('admin.add_role');
       
        //User_list
        Route::get('user_list',[AdminController::class, 'user_list'])->name('admin.user_list');
        Route::post('add_user',[AdminController::class, 'adduser'])->name('admin.add_user');
        Route::get('user/edit/{id}',[AdminController::class, 'editUser'])->name('user.edit');
        Route::put('user/update/{id}',[AdminController::class, 'userUpdate'])->name('user.update');
        Route::get('user/{id}',[AdminController::class, 'showUser'])->name('user.show');
        Route::delete('user/destory/{id}',[AdminController::class, 'userDestory'])->name('user.destroy');
        Route::get('user_role/{param?}',[AdminController::class, 'Userrole'])->name('user.role');

        Route::resource('evaluation_user',EvaluationUserController::class);
        
        //branch Module
        Route::resource('branch',App\Http\Controllers\BranchController::class);

        //Import Scheme
        Route::get('/file-import',[ImportSchemeController::class,'index'])->name('import-view');
        Route::post('/import',[ImportSchemeController::class,'store'])->name('import');

        //Manage State,District,Village
        Route::resource('state', App\Http\Controllers\StateController::class);
        Route::resource('district', App\Http\Controllers\DistrictController::class);
        Route::resource('taluka', App\Http\Controllers\TalukaController::class);
        Route::get('taluka_list',[App\Http\Controllers\TalukaController::class,'talukaList'])->name('taluka_list');

        Route::resource('village', App\Http\Controllers\VillageController::class);
        Route::post('village_destroy/{village}', [App\Http\Controllers\VillageController::class, 'destroy'])->name('village_destroy.destroy');

        Route::get('village_list',[App\Http\Controllers\VillageController::class,'villageList'])->name('village_list');
        Route::get('/get_district',[App\Http\Controllers\TalukaController::class,'district'])->name('get_district');

        
        //Units
        Route::resource('units', App\Http\Controllers\UnitController::class);
        Route::post('nodal-designations/delete/{id}', [App\Http\Controllers\NodalDesignationController::class, 'destroy'])->name('nodal-designations.destroy');

        Route::resource('nodal-designations', App\Http\Controllers\NodalDesignationController::class);
        
        //Beneficiaries
        Route::resource('beneficiaries', App\Http\Controllers\BeneficiariesController::class);
        Route::get('stage_update',[App\Http\Controllers\AdminController::class,'updateStage'])->name('stage_update');
        Route::post('stage-filter-item',[App\Http\Controllers\AdminController::class,'Stagefiltter'])->name('stage-filter-item');
        Route::post('stage-item',[App\Http\Controllers\AdminController::class, 'summaryReport'])->name('stage-item');
        Route::get('/stage-export-pdf', [App\Http\Controllers\AdminController::class, 'exportPdf'])->name('stage.export.pdf');
        Route::get('/stage-export', [App\Http\Controllers\AdminController::class, 'exportExcel'])->name('stage.export');

        
        //Advertiesment 
        Route::get('advertisement_list',[AdminController::class, 'advertisement_list'])->name('admin.advertisement_list');
        Route::post('add_advertisement',[AdminController::class, 'addAdvertisement'])->name('admin.add_advertisement');
        Route::get('advertisement/edit/{id}',[AdminController::class, 'editAdvertisement'])->name('advertisement.edit');
        Route::put('advertisement/update/{id}',[AdminController::class, 'advertisementUpdate'])->name('advertisement.update');
        Route::post('advertisement/destory/{id}',[AdminController::class, 'advertisementDestory'])->name('advertisement.destroy');
        Route::post('advertisement/status/{id}',[AdminController::class, 'advertisementStatus'])->name('advertisement.status');
        Route::post('advertisement/is_adverties/{id}',[AdminController::class, 'advertisementNews'])->name('advertisement.is_adverties');
        //Digital Project
        Route::resource('digital_project_libraries', App\Http\Controllers\DigitalProjectLibraryController::class);
        Route::post('digital_project_libraries/destory/{id}',[App\Http\Controllers\DigitalProjectLibraryController::class, 'destroy'])->name('digital_project_libraries_destroy.destroy');

       //Proposals
       Route::get('proposals/{param?}', [AdminController::class,'proposallist'])->name('admin.proposal');
        //approved scheme
        Route::post('/approve_scheme',[AdminController::class,'approvedPScheme'])->name('admin.approve_scheme');

        //Menu Items
        Route::resource('menu-item', App\Http\Controllers\MenuItemController::class);
        Route::post('menu-item/destory/{id}',[App\Http\Controllers\MenuItemController::class, 'destroy'])->name('menu-item-destroy.destroy');

        Route::resource('departments', DepartmentController::class);
        Route::resource('subdepartments', SubDepartmentController::class);
        Route::post('subdepartments/destory/{id}',[App\Http\Controllers\SubDepartmentController::class, 'destroy'])->name('subdepartments.destroy');


        Route::get('/department_hod-import',[ImportSchemeController::class,'index'])->name('department_hod-view');
        Route::post('department_hod/import',[ImportSchemeController::class,'hodStore'])->name('department_hod.import');

        Route::resource('publication',App\Http\Controllers\PublicationController::class);
        Route::post('publication/destory/{id}',[App\Http\Controllers\PublicationController::class, 'destroy'])->name('publication.destroy');


    });
});
//Sample file Upload Items
Route::get('export', [ImportSchemeController::class,'export'])->name('export');

Route::middleware([isUserMiddleware::class,'auth',PreventBackHistory::class])->prefix('user')->group(function(){
    Route::get('dashboard',[UserController::class,'index'])->name('user.dashboard');
    Route::get('profile',[UserController::class,'profile'])->name('user.profile');
    Route::get('settings',[UserController::class,'settings'])->name('user.settings');
});

Route::middleware([isImplementationMiddleware::class,'auth'])->prefix('implementation')->group(function(){ 
    Route::middleware(['web',SessionTimeoutMiddleware::class, Referrer::class ])->group(function () {

     Route::get('dashboard',[ImplementationController::class,'index'])->name('implementation.dashboard');

     //Impltemenation department data
     Route::get('test-proposals',[ImplementationController::class,'test_proposallist'])->name('implementation.proposals');
    });
});


Route::middleware([GadsecMiddleware::class,'auth'])->prefix('gadsec')->group(function(){ 
    Route::middleware(['web',SessionTimeoutMiddleware::class, Referrer::class ])->group(function () {

        Route::get('eval_dashboard',[GadsecController::class,'evaldashboard'])->name('gadsec.evaldashboard');

        Route::get('profile',[GadsecController::class,'profile'])->name('gadsec.profile');
        Route::post('update-profile-info',[GadsecController::class,'updateInfo'])->name('gadUpdateInfo');
        Route::post('change-profile-picture',[GadsecController::class,'updatePicture'])->name('gadPictureUpdate');
        Route::post('change-password',[GadsecController::class,'changePassword'])->name('gadChangePassword');

        // Route::get('gad_dashboard',[GadsecController::class,'gaddashboard'])->name('gadsec.gaddashboard');
        // Route::post('gad_dashboard_finyear',[GadsecController::class,'withfinyear'])->name('gadsec.gad_dashboard_finyear');
        // Route::post('deptdashboardgadstatus',[GadsecController::class,'deptdashboardgadstatus'])->name('gadsec.deptdashboardgadstatus');
        // Route::post('gadpublishedstudies',[GadsecController::class,'publishedstudies'])->name('gadsec.gadpublishedstudies');


        Route::get('dashboard', [GadsecController::class,'index'])->name('gadsec.dashboard');
        Route::get('proposals/{param?}', [GadsecController::class,'proposallist'])->name('gadsec.proposal');
        // Route::get('schemes', [GadsecController::class,'schemes'])->name('gadsec.schemes');
        // Route::get('scheme_detail/{id}',[GadsecController::class,'schemedetail'])->name('gadsec.scheme_detail');
        // Route::get('proposal_detail/{id}',[GadsecController::class,'proposaldetail'])->name('gadsec.proposal_detail');
        // Route::post('addremarks', [GadsecController::class,'addremarks'])->name('gadsec.addremarks');

        
        Route::post('gadtoeval',[GadsecController::class,'frwdtoeval'])->name('gadsec.gadtoeval');

        Route::post('gad-scheme-to-eval',[GadsecController::class,'gadSchemefrwd'])->name('gadsec.gad-scheme-to-eval');

        Route::post('returntodept',[GadsecController::class,'returntodept'])->name('gadsec.returntodept');
        Route::post('forwardtoeval',[GadsecController::class,'forwardtoeval'])->name('gadsec.forwardtoeval');
    });
});

Route::middleware([DeptsecMiddleware::class,'auth'])->prefix('deptsec')->group(function() {
       Route::middleware(['web',SessionTimeoutMiddleware::class, Referrer::class ])->group(function () {
    Route::get('dashboard', [DeptsecController::class,'index'])   ->name('deptsec.dashboard');
        Route::get('schemes', [DeptsecController::class,'schemes'])->name('deptsec.schemes');
    // Route::get('scheme_detail/{id}',[DeptsecController::class,'schemedetail'])->name('deptsec.scheme_detail');
        Route::get('proposals', [DeptsecController::class,'proposallist'])->name('deptsec.proposal');
    //  Route::get('proposal_detail/{id}/{sid}',[DeptsecController::class,'proposaldetail'])->name('deptsec.proposal_detail');
        //Route::get('newproposal_detail/{id}',[DeptsecController::class,'newproposaldetail'])->name('deptsec.newproposal_detail');
        //Route::get('proposal_edit/{id}',[DeptsecController::class,'proposaledit'])->name('deptsec.proposal_edit');
        Route::post('proposal_update',[DeptsecController::class,'proposalupdate'])->name('deptsec.proposal_update');
        Route::post('depttogad',[DeptsecController::class,'frwdtogad'])->name('deptsec.frwdtogad');
        Route::post('returntoia',[DeptsecController::class,'returntoia'])->name('deptsec.returntoia');
        // Route::get('meetings', [DeptsecController::class,'meetings'])->name('deptsec.meetings');
        // Route::post('addmeeting', [DeptsecController::class,'addmeeting'])->name('deptsec.addmeeting');
        // Route::post('addconvener',[DeptsecController::class,'addConvener'])->name('deptsec.addconveners');
        // Route::get('nodalslist',[DeptsecController::class,'nodallist'])->name('deptsec.nodallist');
        // Route::get('addnodal',[DeptsecController::class,'addNodal'])->name('deptsec.addnodal');
        // Route::post('addnodal',[DeptsecController::class,'saveNodal'])->name('deptsec.storenodal');
        // Route::get('convenerslist',[DeptsecController::class,'listconveners'])->name('deptsec.listconveners');
        // Route::get('addconvener',[DeptsecController::class,'conveneradd'])->name('deptsec.addconvener');
        // Route::post('storeconvener',[DeptsecController::class,'saveConvener'])->name('deptsec.storeconvener');
        // Route::get('adviserslist',[DeptsecController::class,'listadvisers'])->name('deptsec.listadvisers');
        // Route::get('addadviser',[DeptsecController::class,'adviseradd'])->name('deptsec.addadviser');
        // Route::post('storeadviser',[DeptsecController::class,'saveAdviser'])->name('deptsec.storeadviser');
        // Route::get('communication',[DeptsecController::class,'communication'])->name('deptsec.communication');
        // Route::post('addcommunication',[DeptsecController::class,'addcommunication'])->name('deptsec.addcommunication');
        // Route::get('get_the_file/{id}/{scheme}',[DeptsecController::class,'getthefile'])->name('deptsec.get_the_file');
    });
});

Route::middleware([EvaldirMiddleware::class,'auth'])->prefix('evaldir')->group(function() { 
       Route::middleware(['web',SessionTimeoutMiddleware::class, Referrer::class ])->group(function () {

    Route::get('dashboard', [EvaldirController::class,'index'])->name('evaldir.dashboard');
    Route::get('proposals/{param?}', [EvaldirController::class,'proposallist'])->name('evaldir.proposal');
    // Route::get('proposal_detail/{id}',[EvaldirController::class,'proposaldetail'])->name('evaldir.proposal_detail');
  
  
    Route::post('returntogad',[EvaldirController::class,'returnToGad'])->name('evaldir.returntogad');
    Route::post('sendschemetodd',[EvaldirController::class,'sendschemetodd'])->name('evaldir.sendschemetodd');

    // Route::post('get_branch',[EvaldirController::class,'branch'])->name('evaldir.get_branch');
    // Route::post('update_branch',[EvaldirController::class,'updateBranch'])->name('evaldir.update_branch');
    
    //Route::post('approveproposal',[EvaldirController::class,'approveproposal'])->name('evaldir.approveproposal');
    //Route::post('get_prop_status',[EvalroController::class,'get_prop_status'])->name('evaldir.get_prop_status');
   // Route::post('getdept',[EvaldirController::class,'getdepartment'])->name('evaldir.getdept');
   // Route::post('searchproposals',[EvaldirController::class,'searchproposals'])->name('evaldir.searchproposals');
   // Route::post('get_scheme_detail',[EvaldirController::class,'get_scheme_detail'])->name('evaldir.get_scheme_detail');
   // Route::get('fetch_scheme_detail',[EvaldirController::class,'fetch_scheme_detail'])->name('evaldir.fetch_scheme_detail');
    //Route::post('show_prop_detail',[EvaldirController::class,'show_prop_detail'])->name('evaldir.show_prop_detail');

   // Route::post('dashboardevalstatus',[EvaldirController::class,'dashboardevalstatus'])->name('evaldir.dashboardevalstatus');
   // Route::post('deptdashboardevalstatus',[EvaldirController::class,'deptdashboardevalstatus'])->name('evaldir.deptdashboardevalstatus');
    //Route::post('publishedstudies',[EvaldirController::class,'publishedstudies'])->name('evaldir.publishedstudies');
    // Route::get('activities',[EvaldirController::class,'activities'])->name('evaldir.activities');
    // Route::post('getactivityonmodal',[EvaldirController::class,'getactivityonmodal'])->name('evaldir.getactivityonmodal');
    // Route::post('update_activity',[EvaldirController::class,'updateactivity'])->name('evaldir.update_activity');
   // Route::post('index_with_finyear',[EvaldirController::class,'withfinyear'])->name('evaldir.index_with_finyear');
   // Route::get('pendingproposals',[EvaldirController::class,'pendingproposals'])->name('evaldir.pendingproposals');

    //Route::post('meeting_events_of_next_month',[EvaldirController::class,'nextmeetingevents'])->name('evaldir.meeting_events_of_next_month');
   // Route::get('get_the_file/{id}/{scheme}',[EvaldirController::class,'getthefile'])->name('evaldir.get_the_file');
   // Route::get('report_module/{id}',[EvaldirController::class,'reportmodule'])->name('evaldir.report_module');


    //Profile
    Route::get('profile',[EvaldirController::class,'profile'])->name('evaldir.profile');
    Route::post('update-profile-info',[EvaldirController::class,'updateInfo'])->name('evaldir.UpdateInfo');
    Route::post('change-profile-picture',[EvaldirController::class,'updatePicture'])->name('evaldir.PictureUpdate');
    Route::post('change-password',[EvaldirController::class,'changePassword'])->name('evaldir.ChangePassword');

    //approved scheme
    Route::post('/approve_proposal',[EvaldirController::class,'approvedProposal'])->name('evaldir.approve_proposal');
    Route::get('/cspro-item', [App\Http\Controllers\CsproDataController::class, 'viewCspro'])->name('evaldir.cspro-item'); // Upload file detail page
    Route::post('/upload-cspro', [App\Http\Controllers\CsproDataController::class, 'uploadCspro'])->name('evaldir.upload-cspro'); // Upload CSPro files
    Route::get('/cspro-data', [App\Http\Controllers\CsproDataController::class, 'index'])->name('evaldir.cspro.index'); // Main index page (after upload)
    Route::get('/cspro-detail-report', [App\Http\Controllers\CsproDataController::class, 'detailReports'])->name('evaldir.cspro-detail-report');
    Route::post('/case-filtter', [App\Http\Controllers\CsproDataController::class, 'caseFiltter'])->name('evaldir.case-filtter');
    Route::post('/case-field-filtter', [App\Http\Controllers\CsproDataController::class, 'caseFieldFiltter'])->name('evaldir.case-field-filtter');
    Route::post('/case-select-info-filtter', [App\Http\Controllers\CsproDataController::class, 'caseInfoFiltter'])->name('evaldir.case-select-info-filtter');
    Route::post('/export-cspro-answers',[App\Http\Controllers\CsproDataController::class, 'export'])->name('export.cspro.answers');
   
    Route::get('/cspro-graph-detail-report', [App\Http\Controllers\CsproDataController::class, 'graphdetailReports'])->name('evaldir.cspro-graph-detail-report');
    Route::get('case-graph-report', [App\Http\Controllers\CsproDataController::class, 'caseGraphreport'])->name('evaldir.case-graph-report');
    Route::post('/download-pdf', [App\Http\Controllers\CsproDataController::class, 'downloadPdf'])->name('evaldir.download-pdf');

    });
});

Route::middleware([EvalddMiddleware::class,'auth'])->prefix('evaldd')->group(function(){
       Route::middleware(['web',SessionTimeoutMiddleware::class, Referrer::class ])->group(function () {

    Route::get('dashboard', [EvalddController::class,'index'])->name('evaldd.dashboard');
    Route::get('odk_project',[EvalddController::class,'odk_project_list']) ->name('evaldd.odk_projects');
    Route::get('forms/{id?}', [EvalddController::class, 'allforms'])->name('evaldd.odk_project_form');
    Route::get('proposals/{param?}', [EvalddController::class,'proposallist'])->name('evaldd.proposal');
    // Route::get('proposal_detail/{id}',[EvalddController::class,'proposaldetail'])->name('evaldd.proposal_detail');
    Route::get('schemes', [EvalddController::class,'schemes'])->name('evaldd.schemes');
    Route::get('scheme_detail/{id}',[EvalddController::class,'schemedetail'])->name('evaldd.scheme_detail'); 
   
	Route::post('show_prop_detail',[EvalddController::class,'show_prop_detail'])->name('evaldd.show_prop_detail');    
    Route::post('get_prop_status',[EvalddController::class,'get_prop_status'])->name('evaldd.get_prop_status');
    Route::post('savestatus',[EvalddController::class,'savestatus'])->name('evaldd.savestatus');
    Route::post('savestatustwo',[EvalddController::class,'savestatustwo'])->name('evaldd.savestatustwo');
    Route::get('get_the_file/{id}/{scheme}',[EvalddController::class,'getthefile'])->name('evaldd.get_the_file');

    /* ODK Controller   */
    Route::get('odkgetToken',   [ODKController::class,'odkgetToken']) ->name('evaldd.odkgetToken');
    Route::post('odkStudyStore',[ODKController::class,'odkStudyStore'])->name('evaldd.odkStudyStore');
    Route::get('/user_list',[ODKController::class,'enumerator_list'])->name('evaldd.user_list');

    Route::get('/enumerator_list',[ODKController::class,'enumerator_list'])->name('evaldd.enumerator_list');
    Route::post('/show_enum_list',[ODKController::class,'show_enum_list'])->name('evaldd.show_enum_list');
    Route::post('odk_form_detail',[EvalddController::class,'odk_form_detail'])->name('evaldd.odk_form_detail');

    //Profile
    Route::get('profile',[EvalddController::class,'profile'])->name('evaldd.profile');
    Route::post('update-profile-info',[EvalddController::class,'updateInfo'])->name('evaldd.UpdateInfo');
    Route::post('change-profile-picture',[EvalddController::class,'updatePicture'])->name('evaldd.PictureUpdate');
    Route::post('change-password',[EvalddController::class,'changePassword'])->name('evaldd.ChangePassword');

    Route::post('sendschemetodd',[EvalddController::class,'sendschemetodd'])->name('evaldd.sendschemetodd');
    
    Route::post('get_branch',[EvalddController::class,'branch'])->name('evaldd.get_branch');
    Route::post('update_branch',[EvalddController::class,'updateBranch'])->name('evaldd.update_branch');

    Route::get('stage-detail-report-list',[EvalddController::class,'downloadStagereport'])->name('evaldd.stage-detail-report-list');
    });
});

});
