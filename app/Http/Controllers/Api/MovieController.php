<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Movie;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class MovieController extends Controller
{
    public function getAll()
    {
        $all = Movie::get() ;
        return response()->json($all) ;
    }

    public function fillData()
    {
        $movies = Movie::get() ;
        foreach ($movies as $movie) {
            $movieName  =  $movie->title ;
            $client = new \GuzzleHttp\Client();
            $request = $client->get("https://api.themoviedb.org/3/search/movie?api_key=15d2ea6d0dc1d476efbca3eba2b9bbfb&query=".$movieName."&language=en-US&page=1&include_adult=true");
            $response = json_decode($request->getBody(), true);

            $themoviedb_id = $response['results'][0]['id'] ;
            $vote_average = $response['results'][0]['vote_average'] ;

            $portrait_image = 'https://image.tmdb.org/t/p/original' .  $response['results'][0]['poster_path'];
            $landscape_image = 'https://image.tmdb.org/t/p/original' .  $response['results'][0]['backdrop_path'];
            
            $movie->update([
                'themoviedb_id' =>  $themoviedb_id ,
                'portrait_image' =>  $portrait_image ,
                'landscape_image' =>  $landscape_image ,
                'imdb' =>  $vote_average
            ]);
        }
    }

    public function getPosters($id)
    {
        $movie = Movie::findOrFail($id) ;
        $themoviedb_id = $movie->themoviedb_id ;
        $url = "https://api.themoviedb.org/3/movie/".$themoviedb_id."/images?api_key=15d2ea6d0dc1d476efbca3eba2b9bbfb" ;
        $client = new \GuzzleHttp\Client();
        $request = $client->get($url);
        $response = json_decode($request->getBody(), true);
        $posters = $response['posters'] ;

       
        foreach ($posters as $poster) {
            $poster['file_path'] = 'https://image.tmdb.org/t/p/original'.$poster['file_path'];
            $results[] = $poster ;
        }

        return response()->json($results) ;
    }

    public function getThrillers($id)
    {
        $movie = Movie::findOrFail($id) ;
        $themoviedb_id = $movie->themoviedb_id ;

        //https://img.youtube.com/vi/KceZ8KfUIIE/0.jpg
        $url = "https://api.themoviedb.org/3/movie/".$themoviedb_id."/videos?api_key=15d2ea6d0dc1d476efbca3eba2b9bbfb" ;
        $client = new \GuzzleHttp\Client();
        $request = $client->get($url);
        $response = json_decode($request->getBody(), true);
        $thrillers = $response['results'] ;

        foreach ($thrillers as $thriller) {
            $key = $thriller['key'];
            $results[] = array(
                "thumb" => "https://img.youtube.com/vi/".$key."/0.jpg",
                "video" => $key
            );
        }
        return response()->json($results) ;
    }
}
