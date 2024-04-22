<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateArticleTest extends TestCase
{
    use RefreshDatabase;
    #[Test]
    public function can_update_articles()
    {
        $article = Article::factory()->create();

        $response = $this->patchJson(route('api.articles.update', $article), [
            'title' => 'Updated Article',
            'slug' => 'updated-article',
            'content' => 'Updated content'
        ])->assertOk();

        $response->assertHeader(
            'Location',
            route('api.articles.show', $article)
        );
        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => 'Updated Article',
                    'slug' => 'updated-article',
                    'content' => 'Updated content'
                ],
                'links' => [
                    'self' => route('api.articles.show', $article)
                ]
            ]
        ]);
    }

    #[Test]
    public function title_is_required()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.articles.update', $article), [
            'slug' => 'updated-article',
            'content' => 'Article content'
        ])->assertJsonApiValidationErrors('title');
    }

    #[Test]
    public function title_must_be_at_least_4_characters()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.articles.update', $article), [
            'title' => 'Nue',
            'slug' => 'updated-article',
            'content' => 'Article content'
        ])->assertJsonApiValidationErrors('title');
    }

    #[Test]
    public function slug_is_required()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.articles.update', $article), [
            'title' => 'Updated article',
            'content' => 'Article content'
        ])->assertJsonApiValidationErrors('slug');
    }

    #[Test]
    public function content_is_required()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.articles.update', $article), [
            'title' => 'Updated article',
            'slug' => 'updated-article'
        ])->assertJsonApiValidationErrors('content');
    }
}