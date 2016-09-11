<?php

namespace Armandsar\MailMango;


class MailControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->sendEmail();
    }

    public function testIndexRespondsWithSuccess()
    {
        $response = $this->get(route('mail-mango.index'));
        $response->assertStatus(200);
    }

    public function testIndexRespondsWithSuccessForJsonRequest()
    {
        $response = $this->getJson(route('mail-mango.index'), ['X-Requested-With' => 'XMLHttpRequest']);
        $response->assertStatus(200);
    }

    public function testIndexRespondsWithCorrectData()
    {
        $response = $this->getJson(route('mail-mango.index'), ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertExactJson([
            'mails' => [
                [
                    'date' => '1970-01-02 01:00:00',
                    'file' => "90000-xxx",
                    'subject' => 'Subject'
                ]
            ]
        ]);
    }

    public function testShowRespondsWithSuccessForJsonRequest()
    {
        $response = $this->getJson(route('mail-mango.show', '90000-xxx'), ['X-Requested-With' => 'XMLHttpRequest']);
        $response->assertStatus(200);
    }

    public function testDestroyDeletesSingleMail()
    {
        $this->filesystem->put(Constants::$storagePath . '/1548-file.json', "");
        $this->filesystem->put(Constants::$storagePath . '/1548-file.eml', "");
        $this->filesystem->put(Constants::$storagePath . '/1549-file.json', "");
        $this->filesystem->put(Constants::$storagePath . '/1549-file.eml', "");

        $this->delete(route('mail-mango.destroy', '1548-file'), ['X-Requested-With' => 'XMLHttpRequest']);

        $this->assertFalse($this->filesystem->has(Constants::$storagePath . '/1548-file.json'));
        $this->assertFalse($this->filesystem->has(Constants::$storagePath . '/1548-file.eml'));
        $this->assertTrue($this->filesystem->has(Constants::$storagePath . '/1549-file.json'));
        $this->assertTrue($this->filesystem->has(Constants::$storagePath . '/1549-file.eml'));
    }

    public function testDestroyDeletesAllMails()
    {
        $this->filesystem->put(Constants::$storagePath . '/1548-file.json', "");
        $this->filesystem->put(Constants::$storagePath . '/1548-file.eml', "");
        $this->filesystem->put(Constants::$storagePath . '/1549-file.json', "");
        $this->filesystem->put(Constants::$storagePath . '/1549-file.eml', "");

        $this->delete(route('mail-mango.destroy', 'all'), ['X-Requested-With' => 'XMLHttpRequest']);

        $this->assertFalse($this->filesystem->has(Constants::$storagePath . '/1548-file.json'));
        $this->assertFalse($this->filesystem->has(Constants::$storagePath . '/1548-file.eml'));
        $this->assertFalse($this->filesystem->has(Constants::$storagePath . '/1549-file.json'));
        $this->assertFalse($this->filesystem->has(Constants::$storagePath . '/1549-file.eml'));
    }

    public function testDownloadReturnsCorrectData()
    {
        $this->filesystem->put(Constants::$storagePath . '/1548-file.eml', "eml content");

        $response = $this->get(route('mail-mango.download', '1548-file'));

        $response->assertSee('eml content');
        $response->assertHeader('Content-Type', 'message/rfc822');
    }
}
