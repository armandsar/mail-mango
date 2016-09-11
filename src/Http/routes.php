<?php

Route::resource('mail-mango', 'Armandsar\MailMango\Http\Controller\MailController');

Route::get(
    'mail-mango/download/{file}',
    ['as' => 'mail-mango.download', 'uses' => 'Armandsar\MailMango\Http\Controller\MailController@download']
);
