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
    private $json;

    public function __construct($code)
    {
        $this->code = $code;
        $this->folder = Constants::$storagePath . DIRECTORY_SEPARATOR . $code;
        $this->jsonFilePath = $this->folder . DIRECTORY_SEPARATOR . 'mail.json';
        $this->emlFilePath = $this->folder . DIRECTORY_SEPARATOR . 'mail.eml';
        $this->files = app(Filesystem::class);
        $this->json = json_decode($this->files->get($this->jsonFilePath), true);
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
        return date('Y-m-d H:i:s', $this->timestamp());
    }

    public function niceDate()
    {
        return date('M y, H:i', $this->timestamp());
    }

    public function json()
    {
        return $this->json;
    }

    public function toArray()
    {
        return [
            'code' => $this->code,
            'date' => $this->date(),
            'nice_date' => $this->niceDate(),
            'subject' => $this->subject()
        ];
    }

    private function timestamp()
    {
        return array_first(explode('-', $this->code));
    }
}
