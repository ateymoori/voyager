<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Author;

class AuthorController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{

    public function getAll(){
       return response()->json(Author::get()) ;
    }
  

    public function getByID($id){
        return response()->json(Author::findOrFail($id)) ;
     }

}
 
