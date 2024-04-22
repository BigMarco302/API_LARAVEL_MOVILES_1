<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;
    #[Test]
    public function Show()
    {
        $this->withoutExceptionHandling();
        $article = Article::factory()->create();
        $response = $this->getJson(route('api.articles.show',$article));
        $response->assertExactJson([
            'data'=>[
                'type'=> 'articles',
                'id'=> (string)$article->getRouteKey(),
                'attributes'=> [
                    'title'=> $article->title,
                    'slug'=> $article->slug,
                    'content'=> $article->content,
                ],
                'links'=> [
                    'self'=> route('api.articles.show',$article)
                ]
            ]
        ]);

    }
}
