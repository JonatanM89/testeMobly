<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\PostController;

class UserController extends Controller
{
    public function index()
    {
        return view('usuarios');
    }

    public function getUsers(){
        $users   = User::all();
        return Response()->json($users,201);
    }

    public function get($id){
        $users   = User::find($id);
        return Response()->json($users,201);

    }

    public function delete($id){
        $user = User::find($id);
        $user->delete();

        return Response('ok');
    }

    public function save(Request $data){
        if($data->input('modal_id') == '0'){
            $nv_user = new User();
            $nv_user->name      = $data->input('modal_name');
            $nv_user->email     = $data->input('modal_email');
            $nv_user->password  = 'asass';
            $nv_user->username  = $data->input('modal_username');

            $nv_user->save();

        }
        else
        {
            $nv_user            = User::find($data->input('modal_id'));
            if($nv_user){
                $nv_user->name      = $data->input('modal_name');
                $nv_user->email     = $data->input('modal_email');
                $nv_user->password  = 'asass';
                $nv_user->username  = $data->input('modal_username');
                $nv_user->save();
            } else {
                echo "Usuario não encontrado";
            }
        }
    }

    public function importarAPI(){
        try {
            $usuarios = json_decode(file_get_contents('http://jsonplaceholder.typicode.com/users'), true);

            foreach ($usuarios as $user) {
                $users = User::where('apiId', '=', $user['id'])->get();

                if( count($users) == 0 ){
                    $nv_user = new User();
                    $nv_user->name      = $user['name'];
                    $nv_user->email     = $user['email'];
                    $nv_user->password  = 'asass';
                    $nv_user->username  = $user['username'];
                    $nv_user->apiId     = $user['id'];

                    $nv_user->save();
                }
            }

            $post = new PostController();
            $post->importarAPI();

            return Response()->json("ok",201);
        } catch (Exception $e) {
            return Response()->json($e,201);
        }


    }
}
