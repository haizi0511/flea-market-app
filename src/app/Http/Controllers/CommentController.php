<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        $user = auth()->user();
        Comment::create([
            'comment' => $request->comment,
            'user_id' => $user->id,
            'item_id' => $item_id,
            ]);

            return redirect('/');
    }

}