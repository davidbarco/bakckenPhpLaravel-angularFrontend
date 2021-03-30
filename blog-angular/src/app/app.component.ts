import { Component, OnInit, DoCheck } from '@angular/core';
import{UserService} from './services/user.service';
import{global} from './services/global';


@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  providers:[UserService]
})
export class AppComponent implements OnInit, DoCheck {
  title = 'blog-angular';
  public identity;
  public token;
  public url;


  constructor(
    /* aqui dentro defino mi servicio */
    public _userService: UserService,
    
  ) { 
    this.loadUser();
    this.url = global.url;

  }

  ngOnInit(): void {
    console.log('webapp cargada correctamente')
  }

  ngDoCheck(){
       this.loadUser();
  }

  loadUser(){
    this.identity= this._userService.getIdentity();
    this.token= this._userService.getToken();
  }

  




}
