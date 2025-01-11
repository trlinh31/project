<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    //

    public function create(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        };


        $comment = Comment::create([
            'user_id' => $request->user_id,
            'content' => $request->content,
            'post_id' => $request->post_id,
            'rating' => $request->rating
        ]);



        return response()->json([
            'data' => $comment,
            'user' => $user,
        ], 201);
    }

    public function update(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        };
        $comment = Comment::find($request->id);


        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found',
            ], 404);
        }


        $comment->update([
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Comment updated successfully',
            'data' => $comment,
            'user' => $user,
        ], 200);
    }
    public function delete(Request $request)
    {


        $comment = Comment::find($request->id);


        if (!$comment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Comment not found',
            ], 404);
        }


        $comment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Comment deleted successfully',
        ], 200);
    }

    public function index($id)
    {
        $comments = DB::table('comments')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->select('comments.*', 'users.name', 'users.avt')
            ->where('post_id', $id)
            ->get();
        return response()->json($comments);
    }
}
