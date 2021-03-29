<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('api.auth', ['except'=> ['index','show','getImage','getPostsByCategory','getPostsByUser']]);
    }
    
    //metodo de prueba.
    public function pruebas(Request $request){
        return "Accion de pruebas de POST-CONTROLLER";
    }

    /* metodo para sacar todos los posts */
    public function index(){
       
        $posts = Post::all()->load('category');

        return response()->json([

            'code' => 200,
            'status' => 'success',
            'posts'=> $posts,
        ], 200);
 


    }

    /* para mostrar una post en espefico */
    public function show($id){

        $post = Post::find($id)->load('category');
        
        if(is_object($post)){
           
            $data = [
                'code' => 200,
                'status' => 'success',
                'post'=> $post
            ];

             
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message'=> 'no existe el post'
            ];

        }

        return response()->json($data, $data['code']);
        // var_dump($id); die();


    }

    /* metodo para guardar un post en mi base de datos. */
    public function store(Request $request){
        
        /* recoger los datos por post */
         $json = $request->input('json', null);
         $params = json_decode($json);
         $params_array = json_decode($json, true);

         
        
         if(!empty($params_array)){
            /* conseguir el usuario identificado, debo cargar mi helpers de jwt */
            $user = $this->getIdentity($request);
            
            /*validar los datos*/
            $validate = Validator::make($params_array, [

                'title'    => 'required',
                'content'  => 'required',
                'category_id' => 'required',
                'image'=> 'required',
                
            ]);

            if($validate->fails()){
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message'=> 'no se ha guardado el post, faltan datos'
                ];
    
            }else{
                /* guardar el post */
                $post = new Post();
    
                /* le doy el valor al campo name de la base de datos */
                $post->user_id = $user->sub;
                $post->category_id = $params->category_id;
                $post->title = $params_array['title'];
                $post->content = $params_array['content'];
                $post->image = $params_array['image'];
                

    
                /* para guardar en la base de datos y hacer el insert */
                $post->save();
    
                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'post'=> $post,
                ];
    
            }
            
        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'message'=> 'no has enviado ningun post'
            ];
        }

        /* devolver la respuesta */

         /* devolver resultado */
         return response()->json($data, $data['code']);
               
    }


    /* metodo para actualizar una post */
    public function update($id, Request $request){

        /* recoger datos por post */
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        /* datos para devolver */
        $data = [
           'code'=> 400,
           'status' => 'error',
           'message'=> 'datos enviados incorrecto'
        ]; 
        
        if(!empty($params_array)){
            
            
            /* validar los datos */
            $validate = Validator::make($params_array, [
                'title'    => 'required',
                'content'  => 'required',
                'category_id' => 'required',
                
                
            ]);
            
            if($validate->fails()){
                
               $data['errors']= $validate->errors();
               return response()->json($data, $data['code']);
    
            }

            
                /* eliminar lo que no queremos actualizar */         
                unset($params_array['id']);
                unset($params_array['user_id']);
                unset($params_array['created_at']);
                unset($params_array['user']);

                /* consigo el usuario identificado */
                $user = $this->getIdentity($request);

                
                
                 
                 /* buscar el registro a actualizar */
                $post = Post::where('id', $id)
                              ->where('user_id', $user->sub)
                              ->first();

                
                
                if(!empty($post) && is_object($post)){

                   

                    /* actualizar el registro(post) */
                    $post->update($params_array);

                    
     
                    /* devolver respuesta */
                       $data = [
                      'code' => 200,
                      'status' => 'success',
                      'post'=> $post,
                      'changes'=> $params_array,
                      ];
                };
            
                  
        }

        return response()->json($data, $data['code']);

    }

    /* metodo para eliminar un post */
    public function destroy($id, Request $request){

        /* consigo el usuario identificado */
        $user = $this->getIdentity($request);
         
        /*conseguir el post a borrar, consulta sql*/
        $post = Post::where('id', $id)->where('user_id', $user->sub)->first();

        if(!empty($post)){
        /* borrarlo */
        $post->delete();

        /* devolver post borrado */
        $data = [
            'code' => 200,
            'status' => 'success',
            'message'=> 'post borrado',
            'post'=>$post
        ];
         }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message'=> 'el post no existe o ya ha sido borrado',
                
            ];
         }

         /* devolver resultado */
         return response()->json($data, $data['code']);





    }


    /* metodo solo para sacar la identidad del usuario */
    private function getIdentity(Request $request){
          /* solo quiero borrar el post si soy el dueño del post, consigo el usuario identificado*/
        $jwtAuth = new JwtAuth();
        $token = $request->header('Authorization',null);
        $user = $jwtAuth->checkToken($token, true);

        return $user;
    }


    /* metodo para subir una imagen del post, ya configuramos el middleware de la ruta. */
    public function upload(Request $request)
    {

        /* recoger datos de la peticion, en este caso la imagen */
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
            Storage::disk('images')->put($image_name, \File::get($image));

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
         
        /* comprobar si existe el fichero */
        $isset = Storage::disk('images')->exists($filename);
        if($isset){
           
            /* saco la imagen guardada en mi storage. */
            $file = Storage::disk('images')->get($filename);
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

    /* metodo para sacar post por categoria */
    public function getPostsByCategory($id){
        
        $posts = Post::where('category_id', $id)->get();

        return response()->json([
             'status'=> 'success',
             'posts'=> $posts,
        ], 200);



    }

    public function getPostsByUser($id){
        
        $posts = Post::where('user_id', $id)->get();
        
    
            return response()->json([
                 'status'=> 'success',
                 'posts'=> $posts,
            ], 200);
            
        



    }
    



}
