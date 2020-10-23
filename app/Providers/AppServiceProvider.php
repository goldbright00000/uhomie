<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use App\Message;
use App\Observers\MessageObserver;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Blade::directive('url', function ($expression) {
            return "<?php echo url($expression); ?>";
        });

        Blade::directive('route', function ($expression) {
            return "<?php echo route($expression); ?>";
        });

        Blade::directive('dd', function ($expression) {
            return "<?php dd($expression); ?>";
        });

        Message::observe(MessageObserver::class);

        if(env('REDIRECT_HTTPS'))
        {
          URL::forceScheme('https');
        }

        Validator::extend(
            'recaptcha',
            'App\\Validators\\ReCaptcha@validate'
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
