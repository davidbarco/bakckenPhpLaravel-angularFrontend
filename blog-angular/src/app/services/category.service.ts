import {Injectable} from '@angular/core';
import {HttpClient,HttpHeaders} from '@angular/common/http';
import { Observable } from 'rxjs';
import {Category} from '../models/category';
import {global} from './global';


@Injectable()
export class CategoryService{
    public url: string
    
      
    constructor(
       public _http: HttpClient
    ){
       this.url = global.url;
    }

    /* metodo para crear una nueva categoria */
    create(token, category):Observable<any>{
        let json = JSON.stringify(category);
        let params = 'json=' + json;
        
      let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded')
                                     .set('Authorization',token);

        return this._http.post(this.url + 'category', params, {headers:headers});
    }

    /* metodo para listar todas las categorias de mi base de datos */
    getCategories():Observable<any>{
        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');

        return this._http.get(this.url + 'category', {headers:headers})



    }


    








}



