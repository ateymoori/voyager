<?php
//php artisan make:controller Voyager/StoryController
namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Story;
use App\Author;

class StoryController extends Controller
{
    
    
    public function getAll(){
        $all = Story::get() ;
        foreach ($all as $story) {
            $story['author']=Author::findOrFail($story['author']);
        }
        return response()->json($all) ;
     }

     public function getByID($id){
         $story = Story::findOrFail($id) ;
         $story['author']=Author::findOrFail($story['author']);
        return response()->json($story) ;
     }
}
