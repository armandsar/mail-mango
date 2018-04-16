<?php

namespace Armandsar\MailMango;


class MailControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->sendEmail([
            'automatic_opening' => false,
            'automatic_opening_from_background' => false,
        ]);
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
                    'nice_date' => 'Jan 70, 01:00',
                    'code' => "90000-xxx",
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
        $this->filesystem->put(Constants::$storagePath . '/1548-file/mail.json', "");
        $this->filesystem->put(Constants::$storagePath . '/1548-file/mail.eml', "");
        $this->filesystem->put(Constants::$storagePath . '/1549-file/mail.json', "");
        $this->filesystem->put(Constants::$storagePath . '/1549-file/mail.eml', "");

        $this->assertTrue($this->filesystem->has(Constants::$storagePath . '/1548-file/mail.json'));
        $this->assertTrue($this->filesystem->has(Constants::$storagePath . '/1548-file/mail.eml'));
        $this->assertTrue($this->filesystem->has(Constants::$storagePath . '/1549-file/mail.json'));
        $this->assertTrue($this->filesystem->has(Constants::$storagePath . '/1549-file/mail.eml'));

        $this->delete(route('mail-mango.destroy', '1548-file'), ['X-Requested-With' => 'XMLHttpRequest']);

        $this->assertFalse($this->filesystem->has(Constants::$storagePath . '/1548-file/mail.json'));
        $this->assertFalse($this->filesystem->has(Constants::$storagePath . '/1548-file/mail.eml'));
        $this->assertFalse($this->filesystem->has(Constants::$storagePath . '/1548-file/'));
        $this->assertTrue($this->filesystem->has(Constants::$storagePath . '/1549-file/mail.json'));
        $this->assertTrue($this->filesystem->has(Constants::$storagePath . '/1549-file/mail.eml'));
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

    public function testEmlReturnsCorrectData()
    {
        $this->filesystem->put(Constants::$storagePath . '/1548-file/mail.eml', "eml content");

        $response = $this->get(route('mail-mango.eml', '1548-file'));

        $response->assertSee('eml content');
        $response->assertHeader('Content-Type', 'message/rfc822');
    }
}
