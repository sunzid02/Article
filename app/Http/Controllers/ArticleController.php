<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $articles = Article::paginate(5);

       return ArticleResource::collection($articles);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $article = $request->isMethod('put') ? Article::findOrFail($request->article_id) : new Article;

        $article->id = $request->input('article_id');
        $article->user_id = $request->input('user_id');
        $article->title = $request->input('title');
        $article->body = $request->input('body');

        if($article->save()) {
            return new ArticleResource($article);
        }
        
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $article = $article->id;
        $article = Article::findOrFail($id);
        // dd($article);   

        return new ArticleResource($article);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        // dd($article);   

        if ( $article->delete() ) 
        {
            return new ArticleResource($article);
            # code...
        }
    }
}
