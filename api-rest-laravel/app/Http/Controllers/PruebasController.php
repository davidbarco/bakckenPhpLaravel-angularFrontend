<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;

class PruebasController extends Controller
{
    //
    public function index(){
        $titulo= 'animales';
        $animales= ['gato','perro','elefante'];

        return view('pruebas.index',array(
            'titulo'=> $titulo,
            'animales'=> $animales,
        ));
    }

    public function testOrm(){
       $posts = Post::all();

       foreach($posts as $post){
           echo '<h1>'.$post->title.'<h1/>';
           echo "<span style='color: purple;'>{$post->user->name} - {$post->category->name}</span>";
           echo '<p>'.$post->content.'<p/>';
           echo "<hr style='color: green;'>";
       }

       $categories = Category::all();
       foreach($categories as $category){
        echo '<h1>'.$category->name.'<h1/>';

        foreach($category->posts as $post){
            echo '<h1>'.$post->title.'<h1/>';
            echo "<h3 style='color: purple;'>{$post->user->name} - {$post->category->name}</h3>";
            echo '<p>'.$post->content.'<p/>';
            
        }
        
        echo '<hr>';
    }


        die();
    }
    







}
