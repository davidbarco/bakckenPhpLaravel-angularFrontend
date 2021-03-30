import {Injectable} from '@angular/core';
import {HttpClient,HttpHeaders} from '@angular/common/http';
import { Observable } from 'rxjs';
import {User} from '../models/user';
import {global} from './global';


@Injectable()
export class UserService{
    public url: string
    public identity: string;
    public token: string;
      
    constructor(
        public _http: HttpClient
    ){
       this.url = global.url;
    }

    /* metodo de registro de un usuario */
    register(user): Observable<any> {
        let json = JSON.stringify(user);
        let params = 'json=' + json;
        
        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
        return this._http.post(this.url + 'register', params, {headers:headers});
    }

    /* metodo para loguear a un usuario */
    signup(user, gettoken=null):Observable<any>{
           
        if(gettoken != null){
            user.gettoken = 'true';   
        }

        let json = JSON.stringify(user);
        let params = 'json=' + json;
        
        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
        return this._http.post(this.url + 'login', params, {headers:headers});

    }

    /* metodo para actualizar un usuario */
    update(token,user): Observable<any>{
        let json = JSON.stringify(user);
        let params = 'json=' + json;
        
        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded')
                                       .set('Authorization',token);

        return this._http.put(this.url + 'user/update', params, {headers:headers});
    }

    /* metodo para sacar la identidad del usuario ya logueado */
    getIdentity(){
        let identity = JSON.parse(localStorage.getItem('identity'));

        if(identity && identity != "undefined"){
           
            this.identity = identity
        }else{
            this.identity = null;
        }

        return this.identity;


    }

    /* meotodo para sacar el token del usuario ya logueado */
    getToken(){
        
        let token = localStorage.getItem('token');

        if(token && token != "undefined"){
           
            this.token = token;
        }else{
            this.token = null;
        }

        return this.token;

    }

    








}



