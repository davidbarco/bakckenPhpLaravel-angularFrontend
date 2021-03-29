<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('api.auth', ['except'=> ['index','show']]);
    }
    //
    public function pruebas(Request $request){
        return "Accion de pruebas de CATEGORY-CONTROLLER";
    }


    /* metodo para sacar todas las categorias */
    public function index(){
       
        $categories = Category::all();

        return response()->json([

            'code' => 200,
            'status' => 'success',
            'categories'=> $categories,
        ]);
 


    }

    /* para mostrar una categoria en espefico */
    public function show($id){

        $category = Category::find($id);
        
        if(is_object($category)){
           
            $data = [
                'code' => 200,
                'status' => 'success',
                'category'=> $category
            ];

             
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message'=> 'no existe la categoria'
            ];

        }

        return response()->json($data, $data['code']);
        // var_dump($id); die();


    }

    /* metodo para guardar una categoria en mi base de datos. */
    public function store(Request $request){
        
        /* recoger los datos por post */
         $json = $request->input('json', null);
         $params_array = json_decode($json, true);
         
         
        if(!empty($params_array)){

        /* validar los datos */
        $validate = \Validator::make($params_array, [
            'name'    => 'required',
            
        ]);

        if($validate->fails()){
            $data = [
                'code' => 400,
                'status' => 'error',
                'message'=> 'no se ha guardado la categoria'
            ];

        }else{
            /* guardar la categoria */
            $category = new Category();

            /* le doy el valor al campo name de la base de datos */
            $category->name = $params_array['name'];

            /* para guardar en la base de datos y hacer el insert */
            $category->save();

            $data = [
                'code' => 200,
                'status' => 'success',
                'category'=> $category,
            ];

        }
        }else{

        $data = [
            'code' => 404,
            'status' => 'error',
            'message'=>'no has enviado ninguna categoria'
        ];

        }
        /* devolver resultado */
        return response()->json($data, $data['code']);
    }

    /* metodo para actualizar una categoria */
    public function update($id, Request $request){

        /* recoger datos por post */
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if(!empty($params_array)){
            
            /* validar los datos */
            $validate = Validator::make($params_array, [
                'name'    => 'required',
                
            ]);
            
           /* quitar lo que no quiero actualizar */
           unset($params_array['id']);
           unset($params_array['created_at']);
            
           /* actualizar el registro(categoria) */
           $category = Category::where('id', $id)->update($params_array);

           /* devolver respuesta */
           $data = [
            'code' => 200,
            'status' => 'success',
            'category'=> $params_array,
            


           ];

        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'message'=> 'no has enviado ninguna categoria'
            ];
        }

        return response()->json($data, $data['code']);


    }




}
