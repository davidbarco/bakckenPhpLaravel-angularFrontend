import { Component, OnInit } from '@angular/core';
import{PostService} from '../../services/post.service';
import{Post} from '../../models/post';
import{global} from '../../services/global';
import {UserService} from '../../services/user.service'

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css'],
  providers:[PostService,UserService]
})
export class HomeComponent implements OnInit {
  public page_title:string
  public url;
  public posts;
  public identity;
  public token;



  constructor(
    private _postService: PostService,
    private _userServide: UserService,

  ) {
    
    this.page_title="Inicio";
    this.url = global.url;
    this.identity= this._userServide.getIdentity();
    this.token = this._userServide.getToken();
   }

  ngOnInit(): void {
    this.getPosts();
    console.log(this.identity)
  }

  /* metodo para sacar los pots que ya estan en el motodo se mi servicio de post */
  getPosts(){
    this._postService.getPosts().subscribe(
      response=>{
        if(response.status == 'success'){
           this.posts = response.posts;
           console.log(this.posts)
           
        }  
      },
      error=>{
         console.log(<any>error);
      }
    )
  }

}
