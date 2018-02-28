<?php

namespace Armandsar\MailMango;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Mail\MailServiceProvider;
use Swift_Mailer;

class MailMangoProvider extends MailServiceProvider
{

    function registerSwiftMailer()
    {
        $this->app->singleton('swift.mailer', function ($app) {
            return new Swift_Mailer(
                new MangoTransport(
                    $app->make(Filesystem::class),
                    $app['config']['mail_mango'],
                    $app->make(Helpers::class)
                )
            );
        });
    }
}
