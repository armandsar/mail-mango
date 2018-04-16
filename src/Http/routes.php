<?php

Route::resource('mail-mango', 'Armandsar\MailMango\Http\Controller\MailController');

Route::get(
    'mail-mango/{file}/eml',
    ['as' => 'mail-mango.eml', 'uses' => 'Armandsar\MailMango\Http\Controller\MailController@eml']
);
