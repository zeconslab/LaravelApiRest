<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class CommentsController extends Controller
{

    /**
     * Funcion para obtener el comentario del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request) {

        $rules =[
            'id' => 'required',
        ];

        $messages = [
            'id.required' => 'El :attribute es un campo requerido',
        ];

        $attributes = [
            'id' => 'Idetificador',
        ];

        $validator = Validator::make($request->all(), $rules , $messages, $attributes);
        
        if ($validator->fails()) {
            return response()->json([
                $validator->errors(),
            ],422);
        }

        $commentsRead = Comment::where('post_id', $request->id)->get();

        foreach($commentsRead as $comment){
            $comment->user;
        }

        if ($commentsRead->count() <= 0) {
            return response()->json([
                'status' => 400,
                'message' => "No existen comentarios"
            ],400);
        }
        else {
            return response()->json([
                'status' => 200,
                'comments' => $commentsRead
            ])->setStatusCode(Response::HTTP_ACCEPTED);
        }
    }

    public function create(Request $request){

        $rules =[
            'comment' => 'required|string|min:10',
        ];

        $messages = [
            'comment.required' => 'El :attribute es un campo requerido',
        ];

        $attributes = [
            'comment' => 'Comentario',
        ];

        $validator = Validator::make($request->all(), $rules , $messages, $attributes);
        
        if ($validator->fails()) {
            return response()->json([
                $validator->errors(),
            ],422);
        }

        $commentCreate = new Comment();
        $commentCreate->user_id = Auth::user()->id;
        $commentCreate->post_id = $request->id;
        $commentCreate->comment = $request->comment;
        $commentCreate->save();

        return response()->json([
            'status' => true,
            'message' => "¡Comentado agregado exitosamente!",
            'comment' => $commentCreate
        ])->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(Request $request){
        
        $rules =[
            'comment' => 'required|string|min:10',
            'id' => 'required'
        ];

        $messages = [
            'id.required' => 'El :attribute es un campo requerido',
            'comment.required' => 'El :attribute es un campo requerido',
        ];

        $attributes = [
            'id' => 'Identificador',
            'comment' => 'Comentario',
        ];

        $validator = Validator::make($request->all(), $rules , $messages, $attributes);
        
        if ($validator->fails()) {
            return response()->json([
                $validator->errors(),
            ],422);
        }

        $commentUpdate = Comment::find($request->id);

        if ($commentUpdate->id != Auth::user()->id) {
            return response()->json([
                'code' => 403,
                'status' => false,
                'message' => "No puedes editar este comentario!"
            ],403);
        }

        $commentUpdate->comment = $request->comment;
        $commentUpdate->update();
        return response()->json([
            'status' => true,
            'message' => "¡Comentario editado!"
        ]);
    }

    
    public function delete(Request $request) {

        $rules =[
            'id' => 'required',
        ];

        $messages = [
            'id.required' => 'El :attribute es un campo requerido',
        ];

        $attributes = [
            'id' => 'Idetificador',
        ];

        $validator = Validator::make($request->all(), $rules , $messages, $attributes);
        
        if ($validator->fails()) {
            return response()->json([
                $validator->errors(),
            ],422);
        }

        $commentDelete = Comment::find($request->id);

        if ($commentDelete->id != Auth::user()->id) {
            return response()->json([
                'code' => 403,
                'status' => false,
                'message' => "No puedes eliminar este comentario!"
            ],403);
        }
        $commentDelete->delete();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => "¡Comentario eliminado!"
        ],200);
    }
}
