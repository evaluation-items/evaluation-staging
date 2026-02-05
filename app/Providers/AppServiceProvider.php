<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Http\ViewComposers\draftComposer;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
	//URL::forceRootUrl('https://evaluationstaging.gujarat.gov.in/Evaluation/public');
        // $encrypted_draft_ids = getDraftIds();
        // dd($encrypted_draft_ids);

        // Or attach the composer to multiple views
        // View::composer('*', function ($view) use ($encrypted_draft_id) {
        //     (new DraftComposer)->compose($view, $encrypted_draft_id);
        // });
        // View::composer(['dashboards.eva-dd.layouts.evaldd-dash-layout'], draftComposer::class)->compose($draft_id);
            //

        Paginator::useBootstrap();
        if (session()->has('locale')) {
             app()->setLocale(session('locale'));
        }
        //AppService Provider
        view()->share('lastUpdated', date("d-m-Y H:i:s", filemtime(base_path('routes/web.php'))));
    }
}
