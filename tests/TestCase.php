<?php

namespace Armandsar\MailMango;

use Illuminate\Contracts\Filesystem\Filesystem;
use Swift_Message;
use VirtualFileSystem\FileSystem as Vfs;
use League\Flysystem\Vfs\VfsAdapter;
use League\Flysystem\Filesystem as FilesystemImpl;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected $filesystem;

    public function setUp()
    {
        $vfs = new Vfs();
        $adapter = new VfsAdapter($vfs);
        $this->filesystem = new FilesystemImpl($adapter);

        parent::setUp();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('filesystems.default', 'vfs');
        $app['config']->set('filesystems.disks.vfs', [
            'driver' => 'vfs'
        ]);

        $app['filesystem']->extend('vfs', function () {
            return $this->filesystem;
        });
    }

    protected function getPackageProviders($app)
    {
        return [MailMangoServiceProvider::class];
    }

    protected function sendEmail($configOverride = [])
    {
        $config = $this->app['config']['mail_mango'];
        $config = array_merge($config, $configOverride);

        $transport = new MangoTransport(
            $this->app->make(Filesystem::class),
            $config
        );

        $message = (new Swift_Message)
            ->setSubject('Subject')
            ->setFrom(['john@doe.com' => 'John Doe'])
            ->setTo(['jane@doe.com' => 'Jane Doe'])
            ->setBody('<div>Message</div>', 'text/html')
            ->addPart('Text', 'text/plain');

        $transport->send($message);
    }

}