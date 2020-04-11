<?php


$bData = file_get_contents("https://api.backendless.com/C1A6E4E6-FAE9-9C53-FFC4-070083CDDB00/00B121F3-BA9F-4A3E-8B31-46033E7141EC/data/stories?sortBy=created%20desc&pageSize=99") ;

$bData = json_decode($bData , true) ;


$DB_DATABASE='laravel';
$DB_USERNAME='root';
$DB_PASSWORD='2240225';

 
$conn = new mysqli('localhost', $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

foreach ($bData as $story) {
    $title = $story['title'];
    $content = str_replace("'","",file_get_contents($story['content_file']) );
    $author = $story['author']['author_id'] ;
    $reading_mintues = 10;

    $sql = "INSERT INTO stories (title, content, author,reading_mintues)
    VALUES ('$title', '$content', $author , 10 )";

 

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo  "<br>" . $conn->error;
    }
    

}

 
?>