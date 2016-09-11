<?php

namespace Armandsar\MailMango;

function time()
{
    return 90000;
}

function bin2hex()
{
    return 'xxx';
}

function exec($command)
{
    MangoTransportTest::$execCalled = $command;
}

function php_uname()
{
    return MangoTransportTest::$osToReport;
}

class MangoTransportTest extends TestCase
{
    public static $execCalled = null;
    public static $osToReport = 'Linux';

    public function setUp()
    {
        self::$execCalled = null;
        self::$osToReport = 'Linux';

        parent::setUp();
    }

    public function testStorageDirectoryIsCreated()
    {
        $this->sendEmail();

        $this->assertTrue($this->filesystem->has(Constants::$storagePath . '/.gitignore'));
    }

    public function testJsonFileIsSaved()
    {
        $this->sendEmail();

        $this->assertTrue($this->filesystem->has(Constants::$storagePath . '/90000-xxx.json'));
    }

    public function testEmlFileIsSaved()
    {
        $this->sendEmail();

        $this->assertTrue($this->filesystem->has(Constants::$storagePath . '/90000-xxx.eml'));
    }

    public function testOldEmailsAreDeleted()
    {
        $this->filesystem->put(Constants::$storagePath . '/1548-file.json', "");
        $this->sendEmail();

        $this->assertFalse($this->filesystem->has(Constants::$storagePath . '/1548-file.json'));
    }

    public function testLinuxCommandIsCalled()
    {
        self::$osToReport = 'Linux';
        $this->sendEmail();

        $this->assertEquals(
            'xdg-open "http://localhost/mail-mango?file=90000-xxx" > /dev/null 2>&1 &',
            self::$execCalled
        );
    }

    public function testDarwinCommandIsCalled()
    {
        self::$osToReport = 'Darwin';
        $this->sendEmail();

        $this->assertEquals(
            'open "http://localhost/mail-mango?file=90000-xxx" > /dev/null 2>&1 &',
            self::$execCalled
        );
    }

    public function testCustomCommandIsCalled()
    {
        $this->sendEmail(['command' => 'custom URL']);

        $this->assertEquals('custom http://localhost/mail-mango?file=90000-xxx', self::$execCalled);
    }

    public function testNoCommandIsCalledWhenOpeningDisabled()
    {
        $this->sendEmail(['disable_automatic_opening' => true]);

        $this->assertNull(self::$execCalled);
    }

    public function testNoCommandIsCalledWhenOpeningDisabledForRunningInConsole()
    {
        $this->sendEmail(['disable_automatic_opening_from_background' => true]);

        $this->assertNull(self::$execCalled);
    }

    public function testNoCommandIsCalledWhenRunningOnUnknownOS()
    {
        self::$osToReport = 'Unknown';

        $this->sendEmail();

        $this->assertNull(self::$execCalled);
    }

}
