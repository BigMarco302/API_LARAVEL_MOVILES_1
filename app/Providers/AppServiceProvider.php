<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Article;
use Closure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\ExpectationFailedException;
use Tests\TestCase;
use PHPUnit\Framework\Assert as PHPUnit;
use Illuminate\Support\Str;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        TestResponse::macro('assertJsonApiValidationErrors', function ($attribute) {
            /** @var TestResponse $this */
            $pointer = Str::of($attribute)->startsWith('data')
             ? "/".str_replace('.','/',$attribute):
             "/data/attributes/{$attribute}";
            try {
                $this->assertJsonFragment([
                    'source' => ['pointer' => $pointer]
                ]);
            } catch (ExpectationFailedException $e) {
                PHPUnit::fail(
                    "Failed to find a JSON:API validation error for key: '{$attribute}'"
                    . PHP_EOL . PHP_EOL .
                    $e->getMessage()
                );
            }
    
            try {
                $this->assertJsonStructure([
                    'errors' => [
                        ['title', 'detail', 'source' => ['pointer']]
                    ]
                ]);
            } catch (ExpectationFailedException $e) {
                PHPUnit::fail(
                    "Failed to find a valid JSON:API error response"
                    . PHP_EOL . PHP_EOL .
                    $e->getMessage()
                );
            }
    
            $this->assertHeader(
                'content-type', 'application/vnd.api+json'
            )->assertStatus(422);
        });
    }
}
