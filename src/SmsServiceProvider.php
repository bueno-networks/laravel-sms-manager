<?php

namespace Bnw\SmsManager;

use Bnw\SmsManager\SmsManager;
use Bnw\SmsManager\Contracts\Sms as SmsContract;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        // Bind the SMS Manager  
        $this->app->singleton(SmsManager::class, function ($app) {
            return new SmsManager($app);
        });

        // Bind the contract of SMSContract to the default driver from SMSManager
        $this->app->bind(SmsContract::class, function($app) {
            return $app->make(SmsManager::class)->driver();
        });
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Config file path.
        $dist = __DIR__.'/../config/sms-manager.php';

        // Merge config.
        $this->mergeConfigFrom($dist, 'sms-manager');

        // Pulish config.
        $this->publishes([
            $dist => config_path('sms-manager'),
        ]);

        if (! class_exists('CreateSmsMessagesTable')) {
            $this->publishes([
              __DIR__ . '/../database/migrations/create_sms_messages_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . 'create_sms_messages_table.php'),
            ], 'migrations');
        }
    }
}
