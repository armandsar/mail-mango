# Mail Mango

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/armandsar/mail-mango/master.svg?style=flat-square)](https://travis-ci.org/armandsar/mail-mango)
[![Total Downloads](https://img.shields.io/packagist/dt/armandsar/mail-mango.svg?style=flat-square)](https://packagist.org/packages/armandsar/mail-mango)

Mail preview for Laravel 5.

Email are opened directly in your browser.
Works for emails sent in background as well (just make sure your base url is configured).

## Install

Via Composer

``` bash
$ composer require armandsar/mail-mango --dev
```

You'll only want this for local development, 
so you should not update `providers` array in `config/app.php`. 

Instead, add the provider in `app/Providers/AppServiceProvider.php`, 
like so:

```php
public function register()
{
	if ($this->app->environment() == 'local') {
		$this->app->register(\Armandsar\MailMango\MailMangoServiceProvider::class);
	}
}
```

Set "mail_mango" as your mail driver.

Send emails and see them straight in your browser or head to yoursite.dev/mail-mango to see all emails

## Publish config

``` bash
$ php artisan vendor:publish
```

## Console command to open email in browser

See mail_mango.php from published config to configure this to fit your needs.
Defaults to xdg open on Linux and open on Mac.


See published config for other settings


## TODO

Change layout

## Testing

``` bash
$ phpunit
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
