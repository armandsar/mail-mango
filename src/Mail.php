<?php

namespace Armandsar\MailMango;

use Illuminate\Contracts\Filesystem\Filesystem;

class Mail
{
    /**
     * @var Filesystem
     */
    private $files;
    private $jsonFilePath;
    private $emlFilePath;
    private $code;
    private $folder;

    public function __construct($code)
    {
        $this->code = $code;
        $this->folder = Constants::$storagePath . DIRECTORY_SEPARATOR . $code;
        $this->jsonFilePath = $this->folder . DIRECTORY_SEPARATOR . 'mail.json';
        $this->emlFilePath = $this->folder . DIRECTORY_SEPARATOR . 'mail.eml';
        $this->files = app(Filesystem::class);
    }

    public function emlContent()
    {
        return $this->files->get($this->emlFilePath);
    }

    public function delete()
    {
        return $this->files->deleteDirectory($this->folder);
    }

    public function subject()
    {
        return $this->json()['subject'];
    }

    public function date()
    {
        $timestamp = array_first(explode('-', $this->code));

        return date('Y-m-d H:i:s', $timestamp);
    }

    public function json()
    {
        return json_decode($this->files->get($this->jsonFilePath), true);
    }

    public function toArray()
    {
        return [
            'code' => $this->code,
            'date' => $this->date(),
            'subject' => $this->subject()
        ];
    }
}
