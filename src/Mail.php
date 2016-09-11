<?php

namespace Armandsar\MailMango;

use Illuminate\Contracts\Filesystem\Filesystem;

class Mail
{
    private $jsonFilePath;
    private $emlFilePath;
    private $files;
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->jsonFilePath = Constants::$storagePath . DIRECTORY_SEPARATOR . $filename . '.json';
        $this->emlFilePath = Constants::$storagePath . DIRECTORY_SEPARATOR . $filename . '.eml';
        $this->files = app(Filesystem::class);
    }

    public function emlContent()
    {
        return $this->files->get($this->emlFilePath);
    }

    public function delete()
    {
        return $this->files->delete([
            $this->jsonFilePath,
            $this->emlFilePath,
        ]);
    }

    public function subject()
    {
        return $this->json()['subject'];
    }

    public function date()
    {
        $timestamp = explode('-', $this->filename)[0];

        return date('Y-m-d H:i:s', $timestamp);
    }

    public function json()
    {
        return json_decode($this->files->get($this->jsonFilePath), true);
    }

    public function toArray()
    {
        return [
            'file' => $this->filename,
            'date' => $this->date(),
            'subject' => $this->subject()
        ];
    }
}
