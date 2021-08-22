<?php
set_time_limit(1000);
function getHTTPResponseStatusCode($url)
{
    $status = null;
    $headers = @get_headers($url, 1);
    if (is_array($headers)) {
        $status = substr($headers[0], 9);
    }
    return $status;
}

function file_get_contents_curl($url)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return $response;
}

$API_KEY = "KEY"; //API Key here.

for ($i = 1; $i <= 1000; $i++) {
    @$JSON = file_get_contents_curl('https://api.themoviedb.org/3/movie/' . $i . '?api_key=' . $API_KEY);

    $URL = "https://api.themoviedb.org/3/movie/" . $i . "?api_key=" . $API_KEY;

    $Kod = getHTTPResponseStatusCode($URL);

    $Item = json_decode($JSON);

    if ($Kod != "404 Not Found") {
        $Baslik = $Item->title; //Title
        $OrjBaslik = $Item->original_title; //Original Title
        $Tarih = $Item->release_date; //Release Date
        $Ozet = $Item->overview; //Overview
        $Poster = "https://image.tmdb.org/t/p/w500" . $Item->poster_path; //Poster Path
        $Dil = $Item->original_language; //Original Language

        @$JSON2 = file_get_contents_curl('https://api.themoviedb.org/3/movie/' . $i . '/credits?api_key=f121c3aff0efc3d4fd2b9d3edc8e221a&language=tr-TR');

        $URL2 = "https://api.themoviedb.org/3/movie/" . $i . "/credits?api_key=f121c3aff0efc3d4fd2b9d3edc8e221a&language=tr-TR";
        $Kod2 = getHTTPResponseStatusCode($URL);

        $Item2 = json_decode($JSON2, true);

        $Oyuncular = "";

        for ($j = 0; $j <= 15; $j++) {
            $Oyuncular = $Oyuncular . $Item2['cast'][$j]['name'] . ", ";
            if ($j == 15) {
                $Oyuncular = rtrim($Oyuncular, ", "); //First 15 Cast
            }
        }

        for ($k = 0; $k < count($Item2['crew']); $k++) {
            if ($Item2['crew'][$k]['job'] == "Writer") {
                $Senarist = $Item2['crew'][$k]['name']; //Writer
            }
            if ($Item2['crew'][$k]['job'] == "Director") {
                $Yonetmen = $Item2['crew'][$k]['name']; //Director
            }
        }

    }


}


?>