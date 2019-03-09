<?php

namespace Tests\Feature;

use App\Photo;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class photosubmitApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function ItCanBeUploadFile()
    {
        Storage::fake('s3');

        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.create'), [
                'photo' => UploadedFile::fake()->image('photo.jpg'),
            ]);
        
        $response->assertStatus(201);

        $photo = Photo::first();

        $this->assertRegExp('/^[0-9a-zA-Z-_]{12}$/', $photo->id);

        Storage::cloud()->assertExists($photo->filename);
    }

    /**
     * @test
     */
    public function dontSaveFileWhenDBError()
    {
        Schema::drop('photos');

        Storage::fake('s3');

        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.create'), [
                'photo' => UploadedFile::fake()->image('photo.jpg'),
            ]);

        $response->assertStatus(500);

        $this->assertEquals(0, count(Storage::cloud()->files()));
    }

    /**
     * @test
     */
    public function DontInsertDBWhenFileError()
    {
        Storage::shouldReceive('cloud')
            ->once()
            ->andReturnNull();
        
        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.create'), [
                'photo' => UploadedFile::fake()->image('photo.jpg'),
            ]);

        $response->assertStatus(500);

        $this->assertEmpty(Photo::all());
    }
}
