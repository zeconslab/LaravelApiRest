<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LikesController extends Controller
{
    public function index(Request $request){
        $like = Like::where('post_id', $request->id)->where('user_id', Auth::user()->id)->get();

        if(count($like) > 0){
            $like[0]->delete();
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'dislike'
            ]);
        }


        $like = new Like();
        $like->user_id = Auth::user()->id;
        $like->post_id = $request->id;
        $like->save();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'like'
        ]);
    }
}
