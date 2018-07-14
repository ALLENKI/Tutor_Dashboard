<?php

namespace Aham\Http\Controllers\Dashboard;

use Validator;
use Input;
use Sentinel;

use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\Comment;

class CommentsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($id)
    {
        $ahamClass = AhamClass::find($id);

        $comments = $ahamClass->comments;

        $user = Sentinel::getUser();

        $std = [];
        foreach ($comments as $comment) {
            $std[] = [
                'id' => $comment->id,
                'parent' => $comment->parent,
                'created' => $comment->created_at->toIso8601String(),
                'modified' => $comment->updated_at->toIso8601String(),
                'content' => $comment->content,
                'fullname' => $comment->user->name,
                'profile_picture_url' => cloudinary_url($user->present()->picture, ['secure' => true]),
                'created_by_current_user' => $comment->user_id == $user->id
            ];
        }

        return response()->json($std);

    }

    public function store($class)
    {
        $ahamClass = AhamClass::find($class);

        $user = Sentinel::getUser();

        // dd(Input::all());

        $comment = new Comment();

        $comment->content = Input::get('content');
        $comment->user_id = Sentinel::getUser()->id;

        $ahamClass->comments()->save($comment);

        $std = [
            'id' => $comment->id,
            'parent' => $comment->parent,
            'created' => $comment->created_at->toIso8601String(),
            'modified' => $comment->updated_at->toIso8601String(),
            'content' => $comment->content,
            'fullname' => $comment->user->name,
            'profile_picture_url' => cloudinary_url($user->present()->picture, ['secure' => true]),
            'created_by_current_user' => $comment->user_id == $user->id
        ];

        return response()->json($std);
    }
}