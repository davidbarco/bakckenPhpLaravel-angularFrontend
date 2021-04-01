import { Component, OnInit } from '@angular/core';
import{Router, ActivatedRoute, Params} from '@angular/router';
import {CategoryService} from '../../services/category.service';
import{UserService} from '../../services/user.service';
import{Post} from '../../models/post';
import{global} from '../../services/global';
import{PostService} from '../../services/post.service';
import Swal from 'sweetalert2'


@Component({
  selector: 'app-post-new',
  templateUrl: './post-new.component.html',
  styleUrls: ['./post-new.component.css'],
  providers:[UserService,CategoryService,PostService]
})
export class PostNewComponent implements OnInit {

  public post: Post;
  public identity;
  public token;
  public categories;
  public url;
  public resetVar = true;
  public status;

  /* configuracion para subir la imagen desde el formulario de actualizar usuario */
  public afuConfig = {
    multiple: false,
    formatsAllowed: ".jpg,.png, .gif, .jpeg",
    maxSize: "50",
    uploadAPI:  {
      url: global.url+'post/upload',
      method:"POST",
      headers: {
     "Authorization": this._userService.getToken() 
      },
    },
    theme: "attachPin",
    hideProgressBar: false,
    hideResetBtn: true,
    hideSelectBtn: false,
    replaceTexts: {
      attachPinBtn: 'Subir imagen...',
    }
};

  constructor(
    private _route: ActivatedRoute,
    private _router: Router,
    private _userService: UserService,
    private _categoryService: CategoryService,
    private _postService: PostService,


  ) { 
    this.identity= this._userService.getIdentity();
    this.token   = this._userService.getToken();
    this.url = global.url;


    }

  ngOnInit(): void {

        this.getCategories();

        let idUser =this.identity.sub;
      
        if(idUser==null){
          idUser =this.identity.id;
        }
        this.post = new Post(1,idUser,1,'','',null,null);
      
        console.log(this.post);

        
  }


  /* metodo para sacar las categorias desde mi servicio */
  getCategories(){
    this._categoryService.getCategories().subscribe(
      response=>{
        if(response.status= 'success'){
          
          /* aqui estan guardadas todas las categorias,para mostrar en el html con un ciclo for */
          this.categories = response.categories;

          console.log(this.categories);
       }
      },
      error=>{
        console.log(<any>error);
      }
    )
  }

  imageUpload(datos){
    console.log(datos.body);
    let data = datos.body;
    this.post.image = data.image;

    //console.log(this.user.image)
  }

  /* metodo para al darle click al formulario, se va al servicio y guardar la informacion en la base de datos */
  onSubmit(form){
    this._postService.create(this.token,this.post).subscribe(
      response=>{
        if(response.status == "success"){
          this.post = response.post;
          this.status = 'success';
          Swal.fire(  'Creado!',  'Post creada con exito!',  'success')
          this._router.navigate(['/inicio']);
          console.log(response);

        }else{
           this.status= "error";
        }  
      },
      error=>{
        this.status= "error";
         console.log(<any>error)
      }       

   );

  }


}
