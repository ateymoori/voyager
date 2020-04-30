<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class ImageController extends Controller
{
    public function getAll()
    {
        $client = new \GuzzleHttp\Client();
        $client = new \GuzzleHttp\Client();
        $request = $client->get("https://pixabay.com/api/?key=14649220-5ae78e4612f86b869152790a4&image_type=photo&per_page=200&orientation=vertical&q=horror");
        $response = json_decode($request->getBody(), true);
        $images = $response['hits'];
       
        foreach ($images as $image) {
            unset($image['pageURL']);
            unset($image['type']);
            unset($image['previewWidth']);
            unset($image['previewHeight']);
            unset($image['previewHeight']);
            unset($image['webformatWidth']);
            unset($image['webformatHeight']);
            unset($image['imageHeight']);
            unset($image['webformatURL']);
            unset($image['downloads']);
            unset($image['comments']);
            unset($image['imageWidth']);
            unset($image['user_id']);
            $outPut[] = $image ;
        }
        
        return response()->json($outPut) ;
    }

    public function fillData()
    {
        $movies = Movie::get() ;
        foreach ($movies as $movie) {
            $movieName  =  $movie->title ;
            $client1 = new \GuzzleHttp\Client();
            $client2 = new \GuzzleHttp\Client();
            $request_a = $client1->get("https://api.themoviedb.org/3/search/movie?api_key=15d2ea6d0dc1d476efbca3eba2b9bbfb&query=".$movieName."&language=en-US&page=1&include_adult=true");
            $response_a = json_decode($request_a->getBody(), true);

            $request_b = $client2->get("http://www.omdbapi.com/?apikey=70ad462a&t=" . $movieName);
            $response_b = json_decode($request_b->getBody(), true);


            try {
                $year = $response_b['Year'];
                $rotten_tomato = $response_b['Ratings'][1]['Value'];
            } catch (\Throwable $th) {
                dd($movieName);
            }
            $runtime = $response_b['Runtime'];
            $genres = $response_b['Genre'];
            $imdbRating = $response_b['imdbRating'];
            $awards = $response_b['Awards'];
            $vote_average = $imdbRating ;


            $themoviedb_id = $response_a['results'][0]['id'] ;
            $release_date = $response_a['results'][0]['release_date'] ;
            $overview = $response_a['results'][0]['overview'] ;

            $portrait_image = 'https://image.tmdb.org/t/p/original' .  $response_a['results'][0]['poster_path'];
            $landscape_image = 'https://image.tmdb.org/t/p/original' .  $response_a['results'][0]['backdrop_path'];
            
            $movie->update([
                'themoviedb_id' =>  $themoviedb_id ,
                'portrait_image' =>  $portrait_image ,
                'landscape_image' =>  $landscape_image ,
                'release_date' =>  $release_date ,
                'overview' =>  $overview ,
                'imdb' =>  $vote_average,
                'runetime' =>  $runtime,
                'genres' =>  $genres,
                'rotten_tomato' =>  $rotten_tomato,
                'awards' =>  $awards
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
        $backdrops = $response['backdrops'] ;
        $merged = array_merge($backdrops, $posters);
       
        foreach ($merged as $image) {
            $image['file_path'] = 'https://image.tmdb.org/t/p/original'.$image['file_path'];
            $results[] = $image ;
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
