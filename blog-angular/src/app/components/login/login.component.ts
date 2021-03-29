import { Component, OnInit } from '@angular/core';
import{Router, ActivatedRoute,Params} from "@angular/router";
import {User} from '../../models/user';
import {UserService} from "../../services/user.service";


@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
  providers:[UserService],
})
export class LoginComponent implements OnInit {
  public status: string;
  public user:User;
  public token: string;
  public identity: string
  constructor(
    private _userService: UserService,
    private _router: Router,
    private _route: ActivatedRoute,
  )
   {
    this.user= new User(1,'','','ROLE_USER','','','','');
   }

  ngOnInit(): void {
    /* se ejecuta siempre que yo cargue este componente, y cierra sesion solo cuando le llega el parametro sure por la url */
    this.logout();
  }
  
  /* Metodo para logear al usuario */
  onSubmit(form){
    this._userService.signup(this.user).subscribe(
      response=>{
           //token
           if(response.status != 'error'){
             this.status = 'success';
             this.token= response;
             /* objeto usuario identificado */
                    this._userService.signup(this.user, true).subscribe(
                      response=>{
                            
                            this.identity= response;
                            
                            /* token e identidad del usuario */
                            console.log(this.token);
                            console.log(this.identity);

                            /* guardo en localstorage los datos del toquen y el identity
                            para persistir la informacion en mi aplicacion */
                            localStorage.setItem('token',this.token);
                            localStorage.setItem('identity', JSON.stringify(this.identity));
                             /* redirecciono */
                             this._router.navigate(['/inicio']);
                      },
                      error=>{
                        this.status = 'error';
                        console.log(<any>error)
                      }
                );

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

  logout(){
    this._route.params.subscribe(params=>{
       
      let logout = +params['sure'];

      if(logout == 1){
        localStorage.removeItem('identity');
        localStorage.removeItem('token');

        this.identity = null
        this.token= null

        /* redirecciono */
        this._router.navigate(['/inicio']);
      }

    });

  }

}
