<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileUploadingTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testHomePage()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function testGetFilesList()
    {
        $response = $this->json('GET', '/api/get-files');
        $response->assertStatus(200);
    }


    public function testDownloadLink()
    {
        $response = $this->json('POST', '/api/download-link', ['url' => 'https://www.google.ru/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png']);
        $response->assertStatus(200);
    }
}
