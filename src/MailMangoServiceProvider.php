<?php

namespace Armandsar\MailMango;

use Illuminate\Support\ServiceProvider;

class MailMangoServiceProvider extends ServiceProvider
{
    public function boot()
    {
        require __DIR__ . '/Http/routes.php';

        $configPath = __DIR__ . '/../config/mail_mango.php';

        $this->loadViewsFrom(__DIR__ . '/views', 'mail-mango');

        $this->publishes([
            $configPath => config_path('mail_mango.php'),
        ]);

        if (!$this->configuredForMailPreview()) {
            return;
        }
    }

    public function register()
    {
        $configPath = __DIR__ . '/../config/mail_mango.php';

        $this->mergeConfigFrom($configPath, 'mail_mango');

        $this->app->register(MailMangoProvider::class);
    }

    private function configuredForMailPreview()
    {
        return $this->app['config']['mail.driver'] == 'mail_mango';
    }
}
