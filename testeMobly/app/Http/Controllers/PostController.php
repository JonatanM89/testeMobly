<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\User;

class PostController extends Controller
{
    public function index()
    {
        $users   = User::all();
        return view('welcome',array('users' => $users));
    }


    public function getAll($id){
        $posts   = $id == 0 ? Post::all() : Post::where('userId', '=', $id)->get();

        $feedposts = Array();

        foreach ($posts as $post) {
            $feed = Array();
            $feed['title'] = $post->title;
            $feed['body'] = $post->body;

            $usuario = User::where('apiId', '=', $post->userId)->get();
            if(count($usuario) > 0)
                $feed['usuario'] = $usuario[0]->name;
            else
                $feed['usuario'] = 'Não encontrado';

            array_push($feedposts, $feed);
        }

        return Response()->json($feedposts,201);
    }

    public function importarAPI(){
        $posts = json_decode(file_get_contents('http://jsonplaceholder.typicode.com/posts'), true);

        foreach ($posts as $post) {
            $findPost = Post::where('apiId', '=', $post['id'])->get();

            if( count($findPost) == 0 ){
                $nv_post            = new Post();
                $nv_post->title     = $post['title'];
                $nv_post->body      = $post['body'];
                $nv_post->userId    = $post['userId'];
                $nv_post->apiId     = $post['id'];
                $nv_post->save();
            }
        }

        return Response()->json("ok",201);
    }

    public function getByUser($id){
        return $this->getAll($id);
    }
}
