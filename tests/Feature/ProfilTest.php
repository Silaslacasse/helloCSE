<?php

namespace Tests\Feature;

use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Admin;
use Laravel\Sanctum\Sanctum;

class ProfilTest extends TestCase
{
    use RefreshDatabase;

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

        $this->assertDatabaseHas('profiles', $profileData);
    }

    /** @test */
    public function it_can_get_all_profiles()
    {
        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);

        $profiles = Profile::factory()->count(3)->create();

        $response = $this->get('/api/profiles');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /** @test */
    public function it_can_update_a_profile()
    {
        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);

        $profile = Profile::factory()->create([
            'name' => 'Original Name',
            'firstName' => 'Original FirstName',
            'imagePath' => 'http://example.com/original_image.jpg',
            'status' => 'active',
        ]);
    
        $response = $this->put('/api/profile/' . $profile->id, [
            'name' => 'Updated Name',
            'firstName' => 'Updated FirstName',
            'imagePath' => 'http://example.com/updated_image.jpg',
            'status' => 'inactive',
        ]);
    
        $response->assertStatus(200);
    
        $this->assertDatabaseHas('profiles', [
            'id' => $profile->id,
            'name' => 'Updated Name',
            'firstName' => 'Updated FirstName',
            'imagePath' => 'http://example.com/updated_image.jpg',
            'status' => 'inactive',
        ]);
    
        $response->assertJson([
            'id' => $profile->id,
            'name' => 'Updated Name',
            'firstName' => 'Updated FirstName',
            'imagePath' => 'http://example.com/updated_image.jpg',
            'status' => 'inactive',
        ]);
    }

    /** @test */
    public function it_can_delete_a_profile()
    {
        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);

        $profile = Profile::factory()->create();

        $response = $this->delete('/api/profile/' . $profile->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('profiles', [
            'id' => $profile->id,
        ]);
    }
}
