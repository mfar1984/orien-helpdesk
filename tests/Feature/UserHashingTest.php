<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\HashLinkService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Feature: laravel-auth-setup
 * Property tests for User model and authentication
 */
class UserHashingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Property 1: Password Hashing with Argon2id
     * For any password string, when stored via the User model, 
     * the resulting hash SHALL be a valid Argon2id hash that can verify the original password.
     * 
     * Validates: Requirements 2.2
     */
    public function test_property_1_password_hashing_with_argon2id(): void
    {
        $passwords = [
            'password',
            'simple123',
            'Complex@Pass#2024!',
            'unicode_пароль_密码',
            str_repeat('a', 100),
        ];

        foreach ($passwords as $password) {
            $user = User::create([
                'name' => 'Test User',
                'username' => 'testuser_' . uniqid(),
                'email' => uniqid() . '@test.com',
                'password' => $password,
            ]);

            // Verify password is hashed with Argon2id
            $this->assertTrue(
                str_starts_with($user->password, '$argon2id$'),
                "Password should be hashed with Argon2id"
            );

            // Verify original password can be verified
            $this->assertTrue(
                Hash::check($password, $user->password),
                "Original password should verify against hash"
            );
        }
    }

    /**
     * Property 2: Hash Link Generation Uniqueness and Length
     * For any newly created User, the system SHALL automatically generate 
     * a hash_link that is at least 64 characters long and unique across all users.
     * 
     * Validates: Requirements 3.2, 3.5
     */
    public function test_property_2_hash_link_generation_uniqueness_and_length(): void
    {
        $hashLinks = [];

        for ($i = 0; $i < 100; $i++) {
            $user = User::create([
                'name' => 'Test User ' . $i,
                'username' => 'testuser_' . $i,
                'email' => "test{$i}@example.com",
                'password' => 'password',
            ]);

            // Verify hash_link is at least 64 characters
            $this->assertGreaterThanOrEqual(
                64,
                strlen($user->hash_link),
                "Hash link should be at least 64 characters"
            );

            // Verify hash_link is unique
            $this->assertNotContains(
                $user->hash_link,
                $hashLinks,
                "Hash link should be unique"
            );

            $hashLinks[] = $user->hash_link;
        }
    }

    /**
     * Property 3: URL Generation Uses Hash Link
     * For any User model instance, when generating a URL route, 
     * the URL SHALL contain the hash_link value and SHALL NOT contain the numeric ID.
     * 
     * Validates: Requirements 3.3
     */
    public function test_property_3_url_generation_uses_hash_link(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $user = User::create([
                'name' => 'Test User ' . $i,
                'username' => 'testuser_url_' . $i,
                'email' => "testurl{$i}@example.com",
                'password' => 'password',
            ]);

            $url = route('users.show', $user);

            // URL should contain hash_link
            $this->assertStringContainsString(
                $user->hash_link,
                $url,
                "URL should contain hash_link"
            );

            // URL should end with hash_link, not numeric ID
            $this->assertStringEndsWith(
                $user->hash_link,
                $url,
                "URL should end with hash_link, not numeric ID"
            );
        }
    }

    /**
     * Property 4: Hash Link Resolution Round Trip
     * For any User with a hash_link, requesting the user resource 
     * by that hash_link SHALL return the same User instance.
     * 
     * Validates: Requirements 3.4
     */
    public function test_property_4_hash_link_resolution_round_trip(): void
    {
        // Create an authenticated user first
        $authUser = User::create([
            'name' => 'Auth User',
            'username' => 'authuser',
            'email' => 'auth@example.com',
            'password' => 'password',
        ]);

        for ($i = 0; $i < 10; $i++) {
            $user = User::create([
                'name' => 'Test User ' . $i,
                'username' => 'testuser_rt_' . $i,
                'email' => "testrt{$i}@example.com",
                'password' => 'password',
            ]);

            // Request user by hash_link (authenticated)
            $response = $this->actingAs($authUser)->get(route('users.show', $user));

            $response->assertStatus(200);

            $responseData = $response->json();

            // Verify returned user matches original
            $this->assertEquals(
                $user->hash_link,
                $responseData['hash_link'],
                "Returned user should have same hash_link"
            );

            $this->assertEquals(
                $user->username,
                $responseData['username'],
                "Returned user should have same username"
            );

            // Verify ID is hidden
            $this->assertArrayNotHasKey(
                'id',
                $responseData,
                "ID should be hidden in response"
            );
        }
    }
}
