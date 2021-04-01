import { Component, OnInit } from '@angular/core';
import { User } from '../../models/user';
import {UserService} from '../../services/user.service';
import{global} from '../../services/global';

@Component({
  selector: 'app-user-edit',
  templateUrl: './user-edit.component.html',
  styleUrls: ['./user-edit.component.css'],
  providers:[UserService],
})
export class UserEditComponent implements OnInit {
  public user: User;
  public identity;
  public token;
  public status;
  public resetVar = true;
  public url;
  


  /* configuracion para subir la imagen desde el formulario de actualizar usuario */
  public afuConfig = {
    multiple: false,
    formatsAllowed: ".jpg,.png, .gif, .jpeg",
    maxSize: "50",
    uploadAPI:  {
      url: global.url+'user/upload',
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
    private _userService: UserService
  ) { 
    this.user= new User(1,'','','ROLE_USER','','','','');
    /* recogo la identidad del usuario */
    this.identity = this._userService.getIdentity();
    this.token= this._userService.getToken();
    
    /* aqui tengo los datos del usuario logueado */
    this.user = new User(this.identity.sub,this.identity.name,this.identity.surname,this.identity.role,this.identity.email,'',this.identity.description,this.identity.image);
    
    this.url = global.url;
  }

  ngOnInit(): void {
    console.log(this.identity)
    
  }
  

  onSubmit(form){
      this._userService.update(this.token,this.user).subscribe(
        response=>{
          if(response && response.status){

            console.log(response,"aqui");
              
              this.status = 'success';

              /* actualizar usuario en sesion */
              if(response.changes.name){
                this.user.name = response.changes.name;
              }
              if(response.changes.surname){
                this.user.surname = response.changes.surname;
              }
              if(response.changes.email){
                this.user.email = response.changes.email;
              }
              if(response.changes.description){
                this.user.description = response.changes.description;
              }

              if(response.changes.image){
                this.user.image = response.changes.image;
              }
             
              
              this.identity = this.user;
              localStorage.setItem('identity', JSON.stringify(this.identity))

              // form.reset();

          }else{
            this.status = 'error';
          }

        },
        error=>{
          this.status = 'error';
          console.log(<any>error)
        }
      );

  }

  avatarUpload(datos){
    let data = datos.body;
    this.user.image = data.image;

  }

}
