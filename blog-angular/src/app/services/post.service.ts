import {Injectable} from '@angular/core';
import {HttpClient,HttpHeaders} from '@angular/common/http';
import { Observable } from 'rxjs';
import {Post} from '../models/post';
import {global} from './global';


@Injectable()
export class PostService{
    public url: string
    
      
    constructor(
       public _http: HttpClient
    ){
       this.url = global.url;
    }

    /* metodo para crear una nuevo post */
    create(token, post):Observable<any>{
        let json = JSON.stringify(post);
        let params = 'json=' + json;
        
      let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded')
                                     .set('Authorization',token);

        return this._http.post(this.url + 'post', params, {headers:headers});
    }

    /* metodo para listar todas los posts de mi base de datos */
    getPosts():Observable<any>{
        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');

        return this._http.get(this.url + 'post', {headers:headers})

    }
    /* metodo para sacar una sola entrada, es decir la pagina de detalle de esa entrada. */
    getPost(id):Observable<any>{
        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');

        return this._http.get(this.url + 'post/' + id, {headers:headers})

    }

    /* metodo para actualizar un post en especifico */
    update(token,post,id):Observable<any>{
        let json= JSON.stringify(post);
        let params= "json="+json;

        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded')
                                       .set('Authorization', token);


        return this._http.put(this.url + 'post/' + id, params, {headers:headers})

    }

    /* metodo para borrar un post */
    delete(token,id):Observable<any>{
        
        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded')
                                       .set('Authorization', token);

        return this._http.delete(this.url + 'post/' + id,  {headers:headers});

    }


    








}