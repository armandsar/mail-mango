<?php

namespace Armandsar\MailMango;


class MangoTransportTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testStorageDirectoryIsCreated()
    {
        $this->sendEmail();

        $this->assertTrue($this->filesystem->has(Constants::$storagePath . '/.gitignore'));
    }

    public function testFilesAreSaved()
    {
        $this->sendEmail();
        $this->assertTrue($this->filesystem->has(Constants::$storagePath . '/90000-xxx/mail.json'));
        $this->assertTrue($this->filesystem->has(Constants::$storagePath . '/90000-xxx/mail.eml'));
        $this->assertTrue($this->filesystem->has(Constants::$storagePath . '/90000-xxx/attachments/1__attachment.txt'));
        $this->assertTrue($this->filesystem->has(Constants::$storagePath . '/90000-xxx/attachments/2__embed.png'));
        // todo assert content
    }

    public function testOldEmailsAreDeleted()
    {
        $this->filesystem->put(Constants::$storagePath . '/1548-code/mail.json', "");

        $this->sendEmail();

        $this->assertFalse($this->filesystem->has(Constants::$storagePath . '/1548-code/mail.json'));
        $this->assertFalse($this->filesystem->has(Constants::$storagePath . '/1548-code/'));
    }

    public function testLinuxCommandIsCalled()
    {
        $this->mockedHelpers->shouldReceive('exec')->with('xdg-open "http://localhost/mail-mango?code=90000-xxx" > /dev/null 2>&1 &');
        $this->sendEmail();
    }

    public function testDarwinCommandIsCalled()
    {
        $this->mockedHelpers->shouldReceive('os')->andReturn('Darwin');
        $this->mockedHelpers->shouldReceive('exec')->with('open "http://localhost/mail-mango?code=90000-xxx" > /dev/null 2>&1 &');

        $this->sendEmail();
    }

    public function testCustomCommandIsCalled()
    {
        $this->mockedHelpers->shouldReceive('exec')->with('custom http://localhost/mail-mango?code=90000-xxx');

        $this->sendEmail(['command' => 'custom $URL']);

    }

    public function testNoCommandIsCalledWhenOpeningDisabled()
    {
        $mockedHelpers = \Mockery::mock(Helpers::class);
        $mockedHelpers->shouldReceive('time')->andReturn(90000);
        $mockedHelpers->shouldReceive('bin2hex')->andReturn('xxx');
        $mockedHelpers->shouldReceive('os')->andReturn('Linux');
        $mockedHelpers->shouldNotReceive('exec');
        $this->mockedHelpers = $mockedHelpers;

        $this->sendEmail(['automatic_opening' => false]);
    }

    public function testNoCommandIsCalledWhenOpeningDisabledForRunningInConsole()
    {
        $mockedHelpers = \Mockery::mock(Helpers::class);
        $mockedHelpers->shouldReceive('time')->andReturn(90000);
        $mockedHelpers->shouldReceive('bin2hex')->andReturn('xxx');
        $mockedHelpers->shouldReceive('os')->andReturn('Linux');
        $mockedHelpers->shouldNotReceive('exec');
        $this->mockedHelpers = $mockedHelpers;
        $this->sendEmail(['automatic_opening_from_background' => false]);
    }

    public function testNoCommandIsCalledWhenRunningOnUnknownOS()
    {
        $mockedHelpers = \Mockery::mock(Helpers::class);
        $mockedHelpers->shouldReceive('time')->andReturn(90000);
        $mockedHelpers->shouldReceive('bin2hex')->andReturn('xxx');
        $mockedHelpers->shouldReceive('os')->andReturn('Unknown');
        $mockedHelpers->shouldNotReceive('exec');
        $this->mockedHelpers = $mockedHelpers;

        $this->sendEmail();
    }

}
