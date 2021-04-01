<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    //
    /* metodo de prueba */
    public function pruebas(Request $request)
    {
        return "Accion de pruebas de USER-CONTROLLER";
    }


    /* metodo para registrar un usuario */
    public function register(Request $request)
    {

        /* recoger los datos del usuario por post */
        $json = $request->input('json', null);
        $params = json_decode($json);  //tener un objeto de esos datos.
        $params_array = json_decode($json, true);  //tener un array de esos datos.

        /* sino esta vacio mi params hago la validacion */
        if (!empty($params_array) && !empty($params)) {
            /* limpiar los datos del array, para que no me tenga en cuenta los espacios al digitar un dato */
            $params_array = array_map('trim', $params_array);
            /* validar datos */
            $validate = Validator::make($params_array, [
                'name'    => 'required|alpha',
                'surname' => 'required|alpha',
                'email'   => 'email|required|unique:users', //que el email sea unico , en la tabla de usuarios, en este caso tabla: users
                'password' => 'required'
            ]);
            /* compruebo si no hay fallos en la validacion */
            if ($validate->fails()) {
                $data = [
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'el usuario no se ha creado',
                    'errors' => $validate->errors()

                ];
            } else {
                /* sino hay fallos, de la validacion , me devuelve los datos. */


                /* cifrar contraseña */
                $pwd = hash('sha256', $params->password);


                /* crear el usuario */
                $user = new user();
                $user->name = $params_array['name'];
                $user->surname = $params_array['surname'];
                $user->email = $params_array['email'];
                $user->password = $pwd;
                /* puedo setear el rol u otro dato */
                $user->role = 'ROLE_USER';

                /* guardar el usuario en la base de datos */
                $user->save();

                $data = [
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'el usuario se ha creado',
                    'user' => $user,
                ];
            }
        } else {
            /* si está vacio mis datos json */
            $data = [
                'status' => 'error',
                'code' => 404,
                'message' => 'los datos enviados no son correctos',
            ];
        }

        return response()->json($data, $data['code']);
    }

    /* metodo para logear un usuario */
    public function login(Request $request)
    {

        $jwtAuth = new JwtAuth();

        /* recibir los datos por post */
        $json = $request->input('json', null);
        $params = json_decode($json);  //tener un objeto de esos datos.
        $params_array = json_decode($json, true);

        /* validar esos datos */
        $validate = Validator::make($params_array, [

            'email'   => 'email|required', //que el email sea unico , en la tabla de usuarios, en este caso tabla: users
            'password' => 'required'
        ]);
        /* compruebo si no hay fallos en la validacion */
        if ($validate->fails()) {
            $signup = [
                'status' => 'error',
                'code' => 404,
                'message' => 'el usuario no se ha podido loguear',
                'errors' => $validate->errors()

            ];
        } else {
            /* cifrar la password */
            $pwd = hash('sha256', $params->password);

            /* devolver token o datos */
            $signup = $jwtAuth->signup($params->email, $pwd);

            if (!empty($params->gettoken)) {

                $signup = $jwtAuth->signup($params->email, $pwd, true);
            }
        }

        return response()->json($signup, 200);
    }

    /* metodo para actualizar los datos del usuario */
    public function update(Request $request)
    {

        /* comprobar si el usuario está identificado */
        $token = $request->header('Authorization');
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        /* recoger los datos por post */
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);  //tener un array de esos datos.

        if ($checkToken && !empty($params_array)) {


            /* sacar usuario identificado */
            $user = $jwtAuth->checkToken($token, true);



            /* validar datos */

            $validate = Validator::make($params_array, [
                'name'    => 'required|alpha',
                'surname' => 'required|alpha',
                'email'   => 'email|required|unique:users' . $user->sub, //que el email sea unico , en la tabla de usuarios, en este caso tabla: users

            ]);

            /* quitar los campos que no quiero actualizar */
            //unset($params_array['id']);
            unset($params_array['role']);
            unset($params_array['password']);
            unset($params_array['created_at']);
            unset($params_array['remember_token']);


            /* actualizar usuario en la base de datos */
            $user_update = User::where('id', $user->sub)->update($params_array);

            /* devolver array con resultado */
            $data = [
                'code' => 200,
                'status' => 'success',
                'user' => $user,
                'changes' => $params_array
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'El usuario no está identificado.'
            ];
        }

        return response()->json($data, $data['code']);
    }



    /* metodo para subir una imagen de usuario, ya configuramos el middleware de la ruta. */
    public function upload(Request $request)
    {

        /* recoger datos de la peticion */
        $image = $request->file('file0');
        
        
        /* validar los datos de la imagen */
        $validate = Validator::make($request->all(), [
            'file0'=> 'required|image|mimes:jpg,jpeg,png,gif',
               

        ]);

        /* guardar imagen */
        if (!$image || $validate->fails()){
            
             /* devolver el resultado */
             $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Error al subir la imagen, la imagen no existe o la extension no es valida'
            ];

        } else {
            
            $image_name = time() . $image->getClientOriginalName();
            Storage::disk('users')->put($image_name, \File::get($image));

            $data = [
                'code' => 200,
                'status' => 'success',
                'image' => $image_name,
            ];
           
        }


        return response()->json($data, $data['code']);
    }


    /* metodo para sacar la imagen guardada del usuario */
    public function getImage($filename){

        $isset = Storage::disk('users')->exists($filename);
        if($isset){
           
            /* saco la imagen guardada en mi storage. */
            $file = Storage::disk('users')->get($filename);
            return new Response($file, 200);

        }else{
            /* devolver el resultado */
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'la imagen no existe'
            ];

            return response()->json($data, $data['code']);


        }

    }

    /* metodo que me saque la informacion de un usuario especifico, esto seria para el perfil del usuario*/
    public function detail($id){

        $user = User::find($id);
        if(is_object($user)){
            $data = [
                'code' => 200,
                'status' => 'success',
                'user'=> $user,
            ];
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message'=> 'usuario no existe'
            ];
        }
        return response()->json($data, $data['code']);

    }



}
