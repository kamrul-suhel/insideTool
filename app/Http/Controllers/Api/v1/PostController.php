<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Post;

class PostController extends Controller
{
    protected $post;

    public function __construct(Post $post)
    {
        $this->middleware('basicAuth');

        $this->post = $post;
    }

    /**
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function validateUrl()
    {
        if(!request()->has('link')) {
            return response(['status' => 'failed', 'message' => 'A link is required'], 200);
        }

        $link = request()->get('link');

        $post = $this->post->where('link', $link)->get();

        if ($post->count() < 1) {
            return response(['status' => 'failed', 'message' => 'Link not found.'], 200);
        }

        return response(['data' => true], 200);

    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getPost()
    {
        if(!request()->has('link')) {
            return response(['status' => 'failed', 'message' => 'No Video Found'], 200);
        }

        $link = request()->get('link');

        $post = $this->post->where('link', $link)->first();

        $data = [
            'likes' => $post->likes,
            'reach' => $post->reach,
            'reactions' => $post->reactions,
            'link_clicks' => $post->link_clicks,
            'comments' => $post->comments,
            'shares' => $post->shares,
        ];

        return response(['data' => $data, 'status' => 'success'], 200);
    }
}
