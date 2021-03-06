<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
use Response;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $articles = Article::orderBy('id', 'desc')->paginate(5);

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
        $article = new Article;

        $article->id = $request->input('article_id');
        $article->user_id = rand(1,5);
        $article->title = $request->input('title');
        $article->body = $request->input('body');

        if($article->save()) {
            return new ArticleResource($article);
        }
        
    }

    public function update(Request $request)
    {
        // $article = Article::findOrFail($request->id);

        // $article->id = $request->id;
        // $article->user_id = $request->user_id;
        // $article->title = $request->input('title');
        // $article->body = $request->input('body');
        // dd( $request->all() );

        // dd($request->all());
        $update = Article::where('id', $request->id)->update([
            'user_id' => rand(1,5),
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return \json_encode(true);
        // dd($request->all());
            
        // if($update == 1) {
        //     $article = Article::findOrFail($request->id);
        //     return new ArticleResource($article);

        //     // return $this->sendResponse($article->toArray(), 'update successfully');

        // }
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
