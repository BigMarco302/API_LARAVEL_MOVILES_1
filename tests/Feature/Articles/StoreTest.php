<?php

namespace Tests\Feature;

use App\Models\Article;
use Closure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\ExpectationFailedException;
use Tests\TestCase;
use PHPUnit\Framework\Assert as PHPUnit;
class StoreTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function StorePost(){
        $response =$this->postJson(route('api.articles.store'),[
                    'title' => 'Nuevo artículo',
                    'slug' => 'nuevo-articulo',
                    'content' => 'Contenido del artículo',
        ]);
        $response->assertCreated();
        $article = Article::first();
        $response->assertHeader(
            'Location',
            route('api.articles.show',$article),
        );
        $response->assertExactJson([
            'data'=>[
                'type'=> 'articles',
                'id'=> (string)$article->getRouteKey(),
                'attributes'=> [
                    'title' => 'Nuevo artículo',
                    'slug' => 'nuevo-articulo',
                    'content' => 'Contenido del artículo',
                ],
                'links'=> [
                    'self'=> route('api.articles.show',$article)
                ]
            ]
        ]);

    }
    #[Test]
    public function title_is_required()
    {
         $this->postJson(route('api.articles.store'), [
                    'slug' => 'nuevo-articulo',
                    'content' => 'Contenido del artículo'
        ])->assertJsonApiValidationErrors('title');
    }

    #[Test]
    public function slug_is_required()
    {
     $this->postJson(route('api.articles.store'),[
                'title'=> 'nuevo-articulo',
                'content'=> 'nuevo-articulo',
        ])->assertJsonApiValidationErrors('slug');

    }
    #[Test]
    public function content_is_required()
    {
    $this->postJson(route('api.articles.store'),[
                'title'=> 'nuevo-articulo',
                'slug'=> 'nuevo-articulo',
        ])->assertJsonApiValidationErrors('content');

    }
    #[Test]
    public function title_is_4_carecters()
    { 
        $this->postJson(route('api.articles.store'),[
                'title'=> 'Nue',
                'slug'=> 'nuevo-articulo',
                'content'=> 'nuevo-articulo',
        ])->assertJsonApiValidationErrors('title');

    }
}
