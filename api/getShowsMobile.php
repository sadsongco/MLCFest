<?php

require_once("../../secure/mlc/db_connect.php");

require '../lib/mustache.php-main/src/Mustache/Autoloader.php';
Mustache_Autoloader::register();

$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader('../templates'),
    'partials_loader' => new Mustache_Loader_FilesystemLoader('../templates/partials')
));

$output = [];

try {
    $query = "SELECT venue_id, name FROM Venues;";
    $venues_res = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    
    $query = "SELECT Shows.start_time, Shows.end_time, Shows.notes, Shows.show_id,
                DATE_FORMAT(Shows.start_time, '%H:%i') AS start_disp, DATE_FORMAT(Shows.end_time, '%H:%i') AS end_disp, 
                ROUND(TIME_TO_SEC(TIMEDIFF(end_time, start_time))/60) AS show_length,
                Artists.name AS artist_name,
                Venues.venue_id AS venue_id, Venues.name AS venue_name
                FROM Shows
                LEFT JOIN Artists ON Shows.artist = Artists.artist_id
                LEFT JOIN Venues ON Shows.venue = Venues.venue_id
                ORDER BY start_time ASC
                ;";
    
    $shows = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}


include ("./includes/colourPalette.php");
$colours = ["#09aa09", "#0959aa", "#aa09aa", "#aa5909"];

$venues = [];

foreach ($venues_res as $venue) {
    $venue["colour"] = array_shift($colours);
    $pallette_col = new ColorPalette($venue["colour"]);
    $venue["pallette"] = $pallette_col->createPalette();
    $venues[$venue["venue_id"]] = $venue;
    $colours[] = $venue["colour"];
}

$festival_start_time = new DateTime("2023-08-30 16:00:00");

// $prev_show["end_time"] = $festival_start_time;

foreach ($shows as &$show) {
    $show["colour"] = array_shift($venues[$show["venue_id"]]["pallette"]);
    $venues[$show["venue_id"]]["pallette"][] = $show["colour"];
    $show_start = new DateTime($show["start_time"]);
    $diff_in_seconds = $show_start->getTimestamp() - $festival_start_time->getTimestamp();
    $show["pixel_position"] = floor($diff_in_seconds / 60) * 3;
    $prev_show = $show;
}

// echo "<pre>"; print_r($shows); echo "</pre>";

$output["time_guide"] = [];

for ($i = 4; $i < 12; $i++) {
    if ($i == 4) {
        $output["time_guide"][] = ["time"=>"$i pm", "open"=>true];
    } else {
        $output["time_guide"][] = ["time"=>"$i pm"];
    }
}

$output["shows"] = $shows;

echo $m->render("clashFinder", $output);

require_once("../../secure/mlc/db_disconnect.php");

?>