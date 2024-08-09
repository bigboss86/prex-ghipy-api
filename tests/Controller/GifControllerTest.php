<?php

namespace Tests\Controller;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class GifControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public $mockConsoleOutput = false;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:install --no-interaction');

        $this->user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'api@prex.com',
            'password' => '123456',
        ]);
    }

    public function test_gif_search_success()
    {
        $url = '/api/gifs/search?' . http_build_query(['query' => $this->faker->word()]);

        $response = $this->getJson($url, [
            'Authorization' => 'Bearer ' . $this->user->createToken('TestToken')->accessToken,
        ]);

        $response->assertStatus(200);
    }

    public function test_gif_search_without_required_fields()
    {
        $url = '/api/gifs/search';

        $response = $this->getJson($url, [
            'Authorization' => 'Bearer ' . $this->user->createToken('TestToken')->accessToken,
        ]);

        $response->assertStatus(422);
    }

    public function test_gif_search_unauthorized()
    {
        $url = '/api/gifs/search?' . http_build_query(['query' => $this->faker->word()]);

        $response = $this->getJson($url);

        $response->assertStatus(401);

        $response->assertExactJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function test_gif_find_success()
    {
        $url = '/api/gifs/GRSnxyhJnPsaQy9YLn';

        $response = $this->getJson($url, [
            'Authorization' => 'Bearer ' . $this->user->createToken('TestToken')->accessToken,
        ]);

        $response->assertStatus(200);
    }

    public function test_gif_find_not_found()
    {
        $url = '/api/gifs/' . $this->faker->uuid;

        $response = $this->getJson($url, [
            'Authorization' => 'Bearer ' . $this->user->createToken('TestToken')->accessToken,
        ]);

        $response->assertStatus(404);

        $response->assertExactJson([
            'message' => 'Gif not found',
        ]);
    }

    public function test_gif_find_unauthorized()
    {
        $url = '/api/gifs/GRSnxyhJnPsaQy9YLn';

        $response = $this->getJson($url);

        $response->assertStatus(401);

        $response->assertExactJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function test_gif_save_success()
    {
        $url = '/api/gifs/GRSnxyhJnPsaQy9YLn/save';

        $response = $this->postJson($url, ['user_id' => $this->user->id, 'alias' => $this->faker->word], [
            'Authorization' => 'Bearer ' . $this->user->createToken('TestToken')->accessToken,
        ]);

        $response->assertStatus(204);
    }

    public function test_gif_save_without_required_fields()
    {
        $url = '/api/gifs/GRSnxyhJnPsaQy9YLn/save';

        $response = $this->postJson($url, [], [
            'Authorization' => 'Bearer ' . $this->user->createToken('TestToken')->accessToken,
        ]);

        $response->assertStatus(422);
    }

    public function test_gif_save_not_found()
    {
        $url = '/api/gifs/' . $this->faker->uuid . '/save';

        $response = $this->postJson($url, ['user_id' => $this->user->id, 'alias' => $this->faker->word], [
            'Authorization' => 'Bearer ' . $this->user->createToken('TestToken')->accessToken,
        ]);

        $response->assertStatus(404);

        $response->assertExactJson([
            'message' => 'Gif not found',
        ]);
    }

    public function test_gif_save_unauthorized()
    {
        $url = '/api/gifs/GRSnxyhJnPsaQy9YLn/save';

        $response = $this->postJson($url);

        $response->assertStatus(401);

        $response->assertExactJson([
            'message' => 'Unauthenticated.',
        ]);
    }
}
