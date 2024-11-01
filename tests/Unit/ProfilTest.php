<?php

namespace Tests\Unit;

use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Admin;

class ProfilTest extends TestCase
{

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = Admin::factory()->create([
            'password' => bcrypt('your_password'),
        ]);

        $this->token = $this->admin->createToken('TestToken')->plainTextToken;
    }

    /** @test */
    public function testCreateProfile()
    {
        $profileData = Profile::factory()->make()->toArray();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                    ->postJson('/api/profile', $profileData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('profile', $profileData);
    }

    /** @test */
    // public function it_can_get_all_profiles()
    // {
    //     $profiles = Profile::factory()->count(5)->create();

    //     $response = $this->get('/api/profiles');

    //     $response->assertStatus(200);
    //     $response->assertJsonCount(5);
    // }

    // /** @test */
    // public function it_can_get_a_profile_by_id()
    // {
    //     $profile = Profile::factory()->create();

    //     $response = $this->get('/api/profile/' . $profile->id);

    //     $response->assertStatus(200);
    //     $response->assertJson([
    //         'id' => $profile->id,
    //         'name' => $profile->name,
    //         'firstName' => $profile->firstName,
    //         'imagePath' => $profile->imagePath,
    //     ]);
    // }

    // /** @test */
    // public function it_can_update_a_profile()
    // {
    //     $profile = Profile::factory()->create();

    //     $this->actingAs($this->admin);

    //     $response = $this->put('/api/profile/' . $profile->id, [
    //         'name' => 'Jane',
    //         'firstName' => 'Doe',
    //         'imagePath' => 'http://example.com/image_updated.jpg',
    //         'status' => 'inactive',
    //     ]);

    //     $response->assertStatus(200);
    //     $this->assertDatabaseHas('profile', [
    //         'id' => $profile->id,
    //         'name' => 'Jane',
    //         'firstName' => 'Doe',
    //         'imagePath' => 'http://example.com/image_updated.jpg',
    //         'status' => 'inactive',
    //     ]);
    // }

    // /** @test */
    // public function it_can_delete_a_profile()
    // {
    //     $profile = Profile::factory()->create();

    //     $this->actingAs($this->admin);

    //     $response = $this->delete('/api/profile/' . $profile->id);

    //     $response->assertStatus(200);
    //     $this->assertDatabaseMissing('profile', [
    //         'id' => $profile->id,
    //     ]);
    // }

    // /** @test */
    // public function it_validates_profile_fields_when_storing()
    // {
    //     $this->actingAs($this->admin);

    //     $response = $this->post('/api/profile', [
    //         'name' => '',
    //         'firstName' => '',
    //         'imagePath' => 'invalid-url',
    //         'status' => 'invalid-status',
    //     ]);

    //     $response->assertStatus(422);
    //     $this->assertArrayHasKey('errors', $response->json());
    // }
}
