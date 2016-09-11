<?php

namespace Armandsar\MailMango;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Mail\Transport\Transport;
use Swift_Mime_Message;

class MangoTransport extends Transport
{
    private $storagePath;
    private $files;
    private $config;

    public function __construct(Filesystem $files, $config)
    {
        $this->files = $files;
        $this->config = $config;
        $this->storagePath = Constants::$storagePath;
    }

    public function send(Swift_Mime_Message $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        $this->save($message);
    }

    private function save(Swift_Mime_Message $message)
    {


        $this->initializeStorageDirectory();

        $this->cleanStorageDirectory();
        $filename = $this->storeMessage($message);

        $this->openBrowser($filename);
    }

    private function prepareData(Swift_Mime_Message $message)
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

        foreach ($message->getChildren() as $child) {
            $children[] = [
                'type' => $child->getContentType(),
                'content' => $child->getBody()
            ];
        }

        $data['parts'] = $children;

        return json_encode($data);
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

    private function storeMessage(Swift_Mime_Message $message)
    {
        $timestamp = time();
        $random = bin2hex(random_bytes(5));
        $filename = $timestamp . '-' . $random;

        $jsonFilePath = $this->storagePath . DIRECTORY_SEPARATOR . $filename . '.json';
        $emlFilePath = $this->storagePath . DIRECTORY_SEPARATOR . $filename . '.eml';

        $this->files->put($jsonFilePath, $this->prepareData($message));
        $this->files->put($emlFilePath, $message->toString());

        return $filename;
    }

    private function openBrowser($filename)
    {
        if ($this->openingDisabled()) {
            return false;
        }

        if (app()->runningInConsole() && $this->openingDisabledWhenRunningInConsole()) {
            return false;
        }

        $url = route('mail-mango.index', ['file' => $filename]);

        $os = php_uname('s');

        if ($customCommand = $this->customCommand()) {
            return exec(str_replace("URL", $url, $customCommand));
        }

        if ($os == 'Linux') {
            return exec("xdg-open \"$url\" > /dev/null 2>&1 &");
        }

        if ($os == 'Darwin') {
            return exec("open \"$url\" > /dev/null 2>&1 &");
        }

        return false;
    }

    private function cleanStorageDirectory()
    {
        $time = time();
        $emailLifetime = $this->emailLifetime();

        $filesToDelete = collect($this->files->files($this->storagePath))
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
            })->toArray();

        $this->files->delete($filesToDelete);
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
        return $this->config['disable_automatic_opening'];
    }

    private function openingDisabledWhenRunningInConsole()
    {
        return $this->config['disable_automatic_opening_from_background'];
    }
}
