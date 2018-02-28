<?php

namespace Armandsar\MailMango;

use Illuminate\Contracts\Filesystem\Filesystem;
use Mockery\MockInterface;
use Swift_Message;
use VirtualFileSystem\FileSystem as Vfs;
use League\Flysystem\Vfs\VfsAdapter;
use League\Flysystem\Filesystem as FilesystemImpl;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * @var FilesystemImpl
     */
    protected $filesystem;
    /**
     * @var MockInterface
     */
    protected $mockedHelpers;

    public function setUp()
    {
        $vfs = new Vfs();
        $adapter = new VfsAdapter($vfs);
        $this->filesystem = new FilesystemImpl($adapter);

        parent::setUp();

        $mockedHelpers = \Mockery::mock(Helpers::class);
        $mockedHelpers->shouldReceive('time')->andReturn(90000);
        $mockedHelpers->shouldReceive('bin2hex')->andReturn('xxx');
        $mockedHelpers->shouldReceive('os')->andReturn('Linux');
        $mockedHelpers->shouldReceive('exec');

        $this->mockedHelpers = $mockedHelpers;
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
            $config,
            $this->mockedHelpers
        );

        $message = (new Swift_Message)
            ->setSubject('Subject')
            ->setFrom(['john@doe.com' => 'John Doe'])
            ->setTo(['jane@doe.com' => 'Jane Doe'])
            ->setBody('<div>Message</div>', 'text/html')
            ->addPart('Message', 'text/plain');

        $transport->send($message);
    }

}