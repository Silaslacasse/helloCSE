<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ProfileService;
use App\Models\Profile;
use App\Services\ProfileLogger;
use App\DTO\ProfileDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class ProfileServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ProfileService $profileService;
    protected ProfileLogger $profileLoggerMock;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->profileLoggerMock = Mockery::mock(ProfileLogger::class);
        
        $this->profileService = new ProfileService($this->profileLoggerMock);
    }

    /** @test */
    public function it_can_create_a_profile()
    {
        $data = [
            'name' => 'John',
            'firstName' => 'Doe',
            'imagePath' => 'http://example.com/image.jpg',
            'status' => 'active',
        ];

        $profileDTO = new ProfileDTO($data['name'], $data['firstName'], $data['imagePath'], $data['status']);

        $this->profileLoggerMock->shouldReceive('logCreation')
            ->once()
            ->with(Mockery::type(Profile::class));

        $this->profileService->createProfile($profileDTO);

        $this->assertDatabaseHas('profiles', ['name' => 'John', 'firstName' => 'Doe']);
    }

    /** @test */
    public function it_can_update_a_profile()
    {
        $profile = Profile::factory()->create(['name' => 'John', 'firstName' => 'Doe']);

        $data = [
            'name' => 'Jane',
            'firstName' => 'Smith',
            'imagePath' => 'http://example.com/image_updated.jpg',
            'status' => 'inactive',
        ];

        $profileDTO = new ProfileDTO($data['name'], $data['firstName'], $data['imagePath'], $data['status']);

        $this->profileLoggerMock->shouldReceive('logUpdate')
            ->once()
            ->with(Mockery::type(Profile::class));

        $updatedProfile = $this->profileService->updateProfile($profileDTO, $profile->id);

        $this->assertEquals('Jane', $updatedProfile->name);
        $this->assertDatabaseHas('profiles', ['id' => $profile->id, 'firstName' => 'Smith']);
    }

    /** @test */
    public function it_can_delete_a_profile()
    {
        $profile = Profile::factory()->create();

        $this->profileLoggerMock->shouldReceive('logDeletion')
            ->once()
            ->with(Mockery::type(Profile::class));

        $this->profileService->deleteProfile($profile->id);

        $this->assertDatabaseMissing('profiles', ['id' => $profile->id]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
