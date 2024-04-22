<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ShowCollectionTest extends TestCase
{
    use RefreshDatabase;
    #[Test]
    public function ShowCollection()
    {
    $this->withoutExceptionHandling();
    $article = Article::factory()->count(3)->create();
    $response = $this->getJson(route('api.articles.index'));

    $response ->assertExactJson([
        'data'=>[
            [
                'type'=> 'articles',
                'id'=> (string)$article[0]->getRouteKey(),
                'attributes'=> [
                    'title'=> $article[0]->title,
                    'slug'=> $article[0]->slug,
                    'content'=> $article[0]->content,
                ],
                'links'=> [
                    'self'=> route('api.articles.show',$article[0])
                ],
            ],
            [
                'type'=> 'articles',
                'id'=> (string)$article[1]->getRouteKey(),
                'attributes'=> [
                    'title'=> $article[1]->title,
                    'slug'=> $article[1]->slug,
                    'content'=> $article[1]->content,
                ],
                'links'=> [
                    'self'=> route('api.articles.show',$article[1])
                ],
            ],
            [
                'type'=> 'articles',
                'id'=> (string)$article[2]->getRouteKey(),
                'attributes'=> [
                    'title'=> $article[2]->title,
                    'slug'=> $article[2]->slug,
                    'content'=> $article[2]->content,
                ],
                'links'=> [
                    'self'=> route('api.articles.show',$article[2])
                ],
            ],
        ],'links'=> [
            'self'=> route('api.articles.index')
        ]
    ]);

    }
}
