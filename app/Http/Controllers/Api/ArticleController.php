<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():ArticleCollection
    {
        // $articles = Article::all();
        // return ArticleResource::collection($articles);
         return ArticleCollection::make(Article::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $request->validate([
            'data.attributes.title' => ['required', 'min:4'],
            'data.attributes.slug' => ['required'],
            'data.attributes.content' => ['required']
        ]);
          $article =   Article::create([
                'title'=>$request->input('data.attributes.title'),
                'slug'=>$request->input('data.attributes.slug'),
                'content'=>$request->input('data.attributes.content'),
            ]);
        return ArticleResource::make($article);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article): ArticleResource
    {   

        return ArticleResource::make($article);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $request->validate([
            'data.attributes.title' => ['required', 'min:4'],
            'data.attributes.slug' => ['required'],
            'data.attributes.content' => ['required'],
        ]);

        $article->update([
            'title' => $request->input('data.attributes.title'),
            'slug' => $request->input('data.attributes.slug'),
            'content' => $request->input('data.attributes.content'),
        ]);

        return ArticleResource::make($article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
