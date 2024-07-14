<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\IpAddress;
use Generator;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;

class IpAddressControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const API_URI = '/api/ip-addresses';

    /**
     * A basic test for unauthenticated get request
     */
    public function test_unauthenticated_get_request(): void
    {
        // send request
        $response = $this->getJson(self::API_URI);

        // validate HTTP response status
        $response->assertStatus(401);
    }

    /**
     * A basic test for unauthenticated post request
     */
    public function test_unauthenticated_post_request(): void
    {
        // send request
        $response = $this->postJson(self::API_URI);

        // validate HTTP response status
        $response->assertStatus(401);
    }

    /**
     * A basic test for unauthenticated patch request
     */
    public function test_unauthenticated_patch_request(): void
    {
        // send request
        $response = $this->patchJson(self::API_URI . '/1');

        // validate HTTP response status
        $response->assertStatus(401);
    }

    /**
     * A basic test for getting all IP address
     */
    public function test_get_all_ip_addresses() : void
    {
        // setup
        $this->setupAccessAndData();

        // send request
        $response = $this->getJson(self::API_URI);

        // validate HTTP response status
        $response->assertStatus(200);
        // validate HTTP response body
        $response->assertJson( fn (AssertableJson $json) =>
            $json->has('data', 5)->has('data.0', fn (AssertableJson $json) =>
                $json->has('id')
                     ->has('ip_address')
                     ->has('label')
                     ->has('created_by')
                     ->has('updated_by')
                     ->has('created_at')
                     ->has('updated_at')
            )
        );
    }

    /**
     * A basic test for storing IP address with validation error
     */
    public function test_storing_ip_adress_with_validation_error(): void
    {
        // setup
        Sanctum::actingAs(User::factory()->create());
        $testLabel = Str::random(256);
        $testIp = $this->faker()->word();

        // send request
        $response = $this->postJson(self::API_URI, [
            'label' => $testLabel,
            'ip_address' => $testIp,
        ]);

        // validate HTTP response status
        $response->assertStatus(422);
        // validate validation rule
        $response->assertInvalid([
            'ip_address' => 'The ip address field must be a valid IP address.',
            'label' => 'The label field must not be greater than 255 characters.',
        ]);
    }

    /**
     * A basic test for storing IP address
     */
    #[DataProvider('ipAddressDataProvider')]
    public function test_storing_ip_adress($label, $ipAddress) : void
    {
        // setup
        $testUser = User::factory()->create();
        Sanctum::actingAs($testUser);

        // send request
        $response = $this->postJson(self::API_URI, [
            'label' => $label,
            'ip_address' => $ipAddress,
        ]);

        // validate HTTP response status
        $response->assertStatus(201);
        // validate HTTP response body
        $response->assertJson( fn (AssertableJson $json) =>
            $json->has('data', fn (AssertableJson $json) =>
                $json->has('id')
                     ->has('ip_address')
                     ->has('label')
                     ->has('created_by')
                     ->has('updated_by')
                     ->has('created_at')
                     ->has('updated_at')
            )
        );
        $this->assertDatabaseHas('ip_addresses', [
            'label' => $label,
            'ip_address' => $ipAddress,
            'created_by' => $testUser->id,
        ]);
    }

    /**
     * A basic test for updating IP address' label
     */
    #[DataProvider('labelDataProvider')]
    public function test_update_ip_adress($testLabel) : void
    {
        $testUser = User::factory()->create();
        Sanctum::actingAs($testUser);
        $ipAddress = IpAddress::factory()->create();
        $response = $this->patchJson(self::API_URI . '/' . $ipAddress->id, [
            'label' => $testLabel
        ]);

        // validate HTTP response status
        $response->assertStatus(204);
        $this->assertDatabaseHas('ip_addresses', [
            'label' => $testLabel,
            'updated_by' => $testUser->id,
        ]);
    }

    /**
     * A basic test for updating IP address with validation error
     */
    public function test_update_ip_adress_with_validation_error() : void
    {
        $ipAddresses = $this->setupAccessAndData();
        $testId = $ipAddresses->first()->id;
        $testLabel = Str::random(256);
        $response = $this->patchJson(self::API_URI . '/' . $testId, [
            'label' => $testLabel
        ]);

        // validate HTTP response status
        $response->assertStatus(422);
        // validate validation rule
        $response->assertInvalid(['label' => 'The label field must not be greater than 255 characters.']);
    }

    /**
     * A basic test for updating IP address with non existing ID
     */
    public function test_update_ip_adress_with_non_existing_id() : void
    {
        Sanctum::actingAs(User::factory()->create());
        $nonExistsId = 9999;
        $response = $this->patchJson(self::API_URI . '/' . $nonExistsId);

        // validate HTTP response status
        $response->assertStatus(404);
    }

    private function setupAccessAndData(int $dataCount = 5) : Collection
    {
        Sanctum::actingAs(User::factory()->create());

        return IpAddress::factory()->count($dataCount)->create();
    }

    public static function ipAddressDataProvider(): Generator
    {
        yield 'Data with label' => [
            'test', '10.0.0.1'
        ];

        yield 'Data with no label' => [
            null, '10.0.0.1'
        ];
    }

    public static function labelDataProvider(): Generator
    {
        yield 'Data with label' => [
            'test'
        ];

        yield 'Data with no label' => [
            null
        ];
    }
}
