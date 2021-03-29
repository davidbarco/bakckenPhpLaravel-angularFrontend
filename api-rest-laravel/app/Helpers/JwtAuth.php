<?php 

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class JwtAuth{

    public $key;

    public function __construct(){
        $this->key= 'esto_es_una_clave';
    }
    

    public function signup($email, $password, $getToken = null){
         
        /* buscar si existe el usuario con sus credenciales */
        $user = User::where([
               'email'=>$email,
               'password'=>$password

        ])->first();

        /* comprobar si son correctas(objeto) */
        $signup = false;
        if(is_object($user)){
            $signup=true;
        }
        
        /* generar el token con los datos del usuario identificado */
        if($signup){
            $token = [
              'sub'=> $user->id,
              'email'=> $user->email,
              'name' => $user->name,
              'surname' => $user->surname,
              'iat' => time(),
              'exp' => time() + (7*24*60*60)  //este token me expira en una semana + (7*24*60*60)
            ];

            /* guardo los datos en el token */
            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decode = JWT::decode($jwt, $this->key, ['HS256']);

            /* devolver los datos decodificados del token, en funcion de un parametro */
            if(is_null($getToken)){
                $data =  $jwt;
            }else{
               $data =  $decode;
            }

        }else{
            $data = [
                'status' => 'error',
                'message'=>'login incorrecto',
            ];
        }
        
        

        return $data;


    }

    /* metodo para revisar el token */
    public function checkToken($jwt, $getIdentity= false){
          
        $auth = false;

        try{
            $jwt = str_replace('"','', $jwt);
            
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);

        }catch(\UnexpectedValueException $e){
           $auth = false;
        }catch(\DomainException $e){
           $auth = false;
        }

        if(!empty($decoded) && is_object($decoded) && isset($decoded->sub)){
           
            $auth = true;
        }else{
            $auth = false;
        }

        if($getIdentity){
           return $decoded;
        }

        return $auth;

    }


}





?>