<?php

namespace Armandsar\MailMango\Http\Controller;

use Armandsar\MailMango\Mail;
use Armandsar\MailMango\MailList;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class MailController extends BaseController
{
    public function index(MailList $mailList, Request $request)
    {
        if ($request->ajax()) {
            return ['mails' => $mailList->call()];
        }

        return view('mail-mango::index');
    }

    public function show($file)
    {
        $mail = new Mail($file);

        return $mail->json();
    }

    public function destroy($file, Filesystem $files)
    {
        if ($file === 'all') {
            $files->deleteDirectory('mail_mango');
            return;
        }

        (new Mail($file))->delete();
    }

    public function eml($file)
    {
        $content = (new Mail($file))->emlContent();

        $headers = [
            'Content-Type' => 'message/rfc822',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $file . ".eml")
        ];

        return response()->make($content, '200', $headers);
    }
}
