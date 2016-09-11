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
        return collect($this->files->files($this->storagePath))
            ->reverse()
            ->values()
            ->map(function ($f) {
                $filename = pathinfo($f, PATHINFO_FILENAME);
                $extension = pathinfo($f, PATHINFO_EXTENSION);
                if ($extension !== 'json') {
                    return null;
                }
                return (new Mail($filename))->toArray();
            })
            ->filter();
    }
}
