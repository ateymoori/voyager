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
            unset($story['updated_at']);
            unset($story['created_at']);
            unset($story['content']);
            unset($story['mp3_file']);
            unset($story['author']['updated_at']);
            unset($story['author']['created_at']);
            unset($story['author']['description']);
            $story['image'] = url('/')."/storage/".$story['image'];
            $story['author']['image'] = url('/')."/storage/".$story['author']['image'];
        }
        return response()->json($all) ;
     }

     public function getByID($id){
         $story = Story::findOrFail($id) ;
         $story['author']=Author::findOrFail($story['author']);
         $story['image'] = url('/')."/storage/".$story['image'];
         $story['author']['image'] = url('/')."/storage/".$story['author']['image'];
         if(  strlen($story['mp3_file'])>5){
            $mp3= json_decode( $story['mp3_file'],true)  ;
            $story['mp3_file'] =  url('/')."/storage/".$mp3[0]['download_link'];
         }
        return response()->json($story) ;
     }
}
