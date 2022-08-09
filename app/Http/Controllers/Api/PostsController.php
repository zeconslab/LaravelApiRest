<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PostsController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('id', 'desc')->get();
        foreach ($posts as $post) {
            $post->user;
            $post['commentsCount'] = count($post->comments);
            $post['likesCount'] = count($post->likes);

            $post['selfLike'] = false;
            foreach ($post->likes as $like) {
                if ($like->user_id == Auth::user()->id) {
                    $post['selfLike'] = true;
                }
            }
        }


        if ($posts->count() <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'No existen registros'
            ],401);
        }
        else {
            return response()->json([
                'status' => true,
                'posts' => $posts
            ], 200);
        }

    }


    /**
     * Metodo para Crear nuevo Post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return response
     */
    public function create(Request $request)
    {
        $postCreate = new Post();
        $postCreate->user_id = Auth::user()->id;
        $postCreate->desc = $request->desc;

        if($request->photo != '')
        {
            $photo = time().'jpg';
            file_put_contents('storage/posts/'. $photo, base64_decode($request->photo));
            $postCreate->photo = $photo;
        }

        $postCreate->save();
        $postCreate->user;
        return response()->json([
            'status' => true,
            'message' => '¡Publicacion creada con exito!',
            'post' => $postCreate
        ]);
    }


    /**
     * Metodo para Actualizar un Post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return response
     */
    public function update(Request $request)
    {
        $postUpdate = Post::find($request->id);
        if(Auth::user()->id != $request->id){
            return response()->json([
                'status' => false,
                'message' => 'No puedes realizar esta accion'
            ], 401);
        }

        $postUpdate->desc = $request->desc;
        $postUpdate->update();
        return response()->json([
            'status' => true,
            'message' => '¡Publicacion actualizada con exito!'
        ]);
    }

    /**
     * Metodo para Eliminar un Post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return response
     */
    public function delete(Request $request)
    {
        $postDelete = Post::find($request->id);
        if(Auth::user()->id != $request->id){
            return response()->json([
                'status' => false,
                'message' => 'No puedes realizar esta accion'
            ], 401);
        }

        if ($postDelete->photo != '') {
            Storage::delete('public/posts/' . $postDelete->photo);
        }

        $postDelete->delete();
        $postDelete->update();
        return response()->json([
            'status' => true,
            'message' => '¡Publicacion eliminada con exito!'
        ]);

    }
}
