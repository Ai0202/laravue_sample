<?php

namespace Tests\Feature;

use App\Photo;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LikeApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        factory(Photo::class)->create();
        $this->photo = Photo::first();
    }

    /**
     * @test
     */
    public function beAbleToAddLike()
    {
        $response = $this->actingAs($this->user)
            ->json('PUT', route('photo.like', [
                'photo' => $this->photo->id,
            ]));
        
        $response->assertStatus(200)
            ->assertJsonFragment([
                'photo-id' => $this->photo->id
            ]);
        
        $this->assertEquals(1, $this->photo->likes()->route());
    }

    /**
     * @test
     */
    public function DontAddLikeIfLikeTwice()
    {
        $param = ['id' => $this->photo->id];
        $this->actingAs($this->user)->json('PUT', route('photo.like', $param));
        $this->actingAs($this->user)->json('PUT', route('photo.like', $param));

        $this->assertEquals(1, $this->photo->likes()->count());
    }

    /**
     * @test
     */
    public function beAbleToRemoveLike()
    {
        $this->photo->likes()->attach($this->user->id);

        $response = $this->actingAs($this->user)
            ->json('DELETE', route('photo.like', [
                'photo' => $this->photo->id,
            ]));
        
        $response->assertStatus(200)
            ->assertJsonFragment([
                'photo_id' => $this->photo->id,
            ]);
        
        $this->assertEquals(0, $this->photo->likes()->count());
    }
}
