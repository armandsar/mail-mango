<?php

namespace Armandsar\MailMango;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Mail\Transport\Transport;
use Swift_Mime_SimpleMessage;
use Swift_MimePart;

class MangoTransport extends Transport
{
    private $storagePath;
    private $files;
    private $config;
    private $helpers;

    public function __construct(Filesystem $files, $config, Helpers $helpers)
    {
        $this->files = $files;
        $this->config = $config;
        $this->storagePath = Constants::$storagePath;
        $this->helpers = $helpers;
    }

    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        $this->save($message);
    }

    private function save(Swift_Mime_SimpleMessage $message)
    {
        $this->initializeStorageDirectory();

        $this->cleanStorageDirectory();
        $filename = $this->storeMessage($message);

        $this->openBrowser($filename);
    }

    private function prepareData(Swift_Mime_SimpleMessage $message)
    {
        $data = [
            'from' => $message->getFrom(),
            'to' => $message->getTo(),
            'reply-to' => $message->getReplyTo(),
            'cc' => $message->getCc(),
            'bcc' => $message->getBcc(),
            'subject' => $message->getSubject()
        ];

        $children[] = [
            'type' => $message->getContentType(),
            'content' => $message->getBody()
        ];
        /* @var $child Swift_MimePart */
        foreach ($message->getChildren() as $child) {
            $children[] = [
                "disposition" => $child->getHeaders()->get('content-disposition') ? $child->getHeaders()->get('content-disposition')->getFieldBody('params') : null,
                'type' => $child->getContentType(),
                'content' => $child->getBody()
            ];
        }

//        dd($children);

        $data['parts'] = $children;

        return $data;
    }

    private function initializeStorageDirectory()
    {
        if (!$this->files->exists($this->storagePath)) {
            $this->files->makeDirectory($this->storagePath);

            $this->files->put(
                $this->storagePath . DIRECTORY_SEPARATOR . '.gitignore',
                "*\n!.gitignore"
            );
        }
    }

    private function storeMessage(Swift_Mime_SimpleMessage $message)
    {
        $timestamp = $this->helpers->time();
        $random = $this->helpers->bin2hex(random_bytes(5));
        $mailCode = $timestamp . '-' . $random;
        $folder = $this->storagePath . DIRECTORY_SEPARATOR . $mailCode;

        $this->files->makeDirectory($folder);

        $data = $this->prepareData($message);

        $attachmentIndex = 0;

        foreach ($data['parts'] as $index => $part) {
            $filename = array_last(explode('filename=', $part['disposition'] ?? null));

            if ($filename) {
                $attachmentIndex += 1;
                $filename = $attachmentIndex . "__" . $filename;
                $this->files->put(
                    $folder . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $filename,
                    $part['content']);
                unset($data['parts'][$index]['content']);
            }
        }
        $this->files->put($folder . DIRECTORY_SEPARATOR . 'mail.json', json_encode($data));
        $this->files->put($folder . DIRECTORY_SEPARATOR . 'mail.eml', $message->toString());

        return $mailCode;
    }

    private function openBrowser($code)
    {
        if ($this->openingDisabled()) {
            return false;
        }

        if (app()->runningInConsole() && $this->openingDisabledWhenRunningInConsole()) {
            return false;
        }

        $url = route('mail-mango.index', ['code' => $code]);

        $os = $this->helpers->os();

        if ($customCommand = $this->customCommand()) {
            return $this->helpers->exec(str_replace('$URL', $url, $customCommand));
        }

        if ($os == 'Linux') {
            return $this->helpers->exec("xdg-open \"$url\" > /dev/null 2>&1 &");
        }

        if ($os == 'Darwin') {
            return $this->helpers->exec("open \"$url\" > /dev/null 2>&1 &");
        }

        return false;
    }

    private function cleanStorageDirectory()
    {
        $time = $this->helpers->time();
        $emailLifetime = $this->emailLifetime();

        collect($this->files->directories($this->storagePath))
            ->filter(function ($file) use (
                $time,
                $emailLifetime
            ) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                if ($extension == 'gitignore') {
                    return false;
                }
                $creationTime = (int)explode('-', basename($file))[0];

                return $time - $creationTime > $emailLifetime;
            })->each(function ($dir) {
                $this->files->deleteDirectory($dir);
            });
    }

    private function customCommand()
    {
        return $this->config['command'];
    }

    private function emailLifetime()
    {
        return (int)$this->config['email_lifetime'];
    }

    private function openingDisabled()
    {
        return !$this->config['automatic_opening'];
    }

    private function openingDisabledWhenRunningInConsole()
    {
        return !$this->config['automatic_opening_from_background'];
    }
}
