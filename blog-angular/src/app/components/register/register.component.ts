import { Component, OnInit } from '@angular/core';
import {User} from '../../models/user';
import {UserService} from "../../services/user.service";

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css'],
  providers:[UserService],
})
export class RegisterComponent implements OnInit {
  public user:User;
  public status: string;
  
    
  constructor(
    /* aqui dentro defino mi servicio */
    private _userService: UserService
  ) { 
    this.user= new User(1,'','','ROLE_USER','','','','');
  }

  ngOnInit(): void {
    
  }

  /* metodo para registrar usuario */
  onSubmit(form){
    this._userService.register(this.user).subscribe(
         response=>{
          if(response.status == 'success'){
             this.status = response.status;
             form.reset();

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

}
