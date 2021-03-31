import { Component, OnInit } from '@angular/core';
import{PostService} from '../../services/post.service';
import{Post} from '../../models/post';
import{global} from '../../services/global';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css'],
  providers:[PostService]
})
export class HomeComponent implements OnInit {
  public page_title:string
  public url;
  public posts;



  constructor(
    public _postService: PostService

  ) {
    
    this.page_title="Inicio";
    this.url = global.url;
   }

  ngOnInit(): void {
    this.getPosts();
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
