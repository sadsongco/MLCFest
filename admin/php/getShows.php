<?php

require_once("../../../secure/mlc/db_connect.php");

require '../../lib/mustache.php-main/src/Mustache/Autoloader.php';
Mustache_Autoloader::register();

$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader('../templates'),
    'partials_loader' => new Mustache_Loader_FilesystemLoader('../templates/partials')
));

$output = [];

try {
    $query = "SELECT venue_id, name FROM Venues;";
    $venues = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    
    $query = "SELECT Shows.start_time, Shows.end_time, Shows.notes, Shows.show_id,
                ROUND(TIME_TO_SEC(TIMEDIFF(end_time, start_time))/60) AS show_length,
                Artists.name AS artist_name,
                Venues.venue_id AS venue_id
                FROM Shows
                LEFT JOIN Artists ON Shows.artist = Artists.artist_id
                LEFT JOIN Venues ON Shows.venue = Venues.venue_id
                ORDER BY start_time ASC
                ;";
    
    $shows = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}

$output["time_guide"] = [];

for ($i = 4; $i < 12; $i++) {
    if ($i == 4) {
        $output["time_guide"][] = ["time"=>"$i pm", "open"=>true];
    } else {
        $output["time_guide"][] = ["time"=>"$i pm"];
    }
}

$output["venues"] = [];
include ("./includes/colourPalette.php");
$colours = ["#09aa09", "#0959aa", "#aa09aa", "#aa5909"];

foreach ($venues as $venue) {
    $venue_colour = array_shift($colours);
    $colours[] = $venue_colour;
    $output["venues"][] = ["venue_name"=>$venue["name"], "venue_id"=>$venue["venue_id"], "shows"=>[], "colour"=>$venue_colour];
}


foreach ($shows as $show) {
    foreach ($output["venues"] as &$venue) {
        if ($show["venue_id"] == $venue["venue_id"]) {
            $venue["shows"][] = $show;
            continue;
        }
    }
}

foreach ($output["venues"] as &$venue) {
    $venue_col = new ColorPalette($venue["colour"]);
    $show_colours = $venue_col->createPalette();
    $prev_show["end_time"] = "2023-08-30 16:00:00";
    foreach ($venue["shows"] as &$show) {
        $show["show_colour"] = array_shift($show_colours);
        $show_colours[] = $show["show_colour"];
        $show_start = new DateTime($show["start_time"]);
        $prev_show_end = new DateTime($prev_show["end_time"]);
        $diff_in_seconds = $show_start->getTimestamp() - $prev_show_end->getTimestamp();
        $show["changeover"] = floor($diff_in_seconds / 60);
        $prev_show = $show;
    }
}

echo $m->render("clashFinder", $output);

require_once("../../../secure/mlc/db_disconnect.php");

?>