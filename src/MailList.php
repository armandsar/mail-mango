<?php

namespace Armandsar\MailMango;

use Illuminate\Contracts\Filesystem\Filesystem;

class MailList
{
    private $files;
    protected $storagePath;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
        $this->storagePath = Constants::$storagePath;
    }

    public function call()
    {
        return collect($this->files->directories($this->storagePath))
            ->reverse()
            ->values()
            ->map(function ($folder) {
                return (new Mail(basename($folder)))->toArray();
            })
            ->filter();
    }
}
