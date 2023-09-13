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
    $venues = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    
    $query = "SELECT Shows.start_time, Shows.end_time, Shows.notes, Shows.show_id,
                DATE_FORMAT(Shows.start_time, '%H:%i') AS start_disp, DATE_FORMAT(Shows.end_time, '%H:%i') AS end_disp, 
                ROUND(TIME_TO_SEC(TIMEDIFF(end_time, start_time))/60)*3 AS show_length,
                Artists.name AS artist_name,
                Venues.venue_id AS venue_id
                FROM Shows
                LEFT JOIN Artists ON Shows.artist = Artists.artist_id
                LEFT JOIN Venues ON Shows.venue = Venues.venue_id
                ORDER BY start_time ASC
                ;";
    
    $shows = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    $query = "SELECT param_name, param_value FROM Params";
    $params = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}

$params_arr = [];
foreach($params as $param) {
    $params_arr[$param["param_name"]] = $param["param_value"];
}

include ("./includes/colourPalette.php");
$colours = ["#09aa09", "#0959aa", "#aa09aa", "#aa5909"];

$output["venues"] = [];

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

$festival_start_time = new DateTime($params_arr["festivalStart"]);
$festival_end_time = new DateTime($params_arr["festivalEnd"]);

$prev_show["end_time"] = $params_arr["festivalStart"];

foreach ($output["venues"] as &$venue) {
    $venue_col = new ColorPalette($venue["colour"]);
    $show_colours = $venue_col->createPalette();
    foreach ($venue["shows"] as &$show) {
        $show["show_colour"] = array_shift($show_colours);
        $show_colours[] = $show["show_colour"];
        $show_start = new DateTime($show["start_time"]);
        $prev_show_end = new DateTime($prev_show["end_time"]);
        $diff_in_seconds = $show_start->getTimestamp() - $prev_show_end->getTimestamp();
        $show["changeover"] = floor($diff_in_seconds / 60) * 3;
        $prev_show = $show;
    }
}

// echo "<pre>"; print_r($output); echo "</pre>";

$festival_start_hour = $festival_start_time->format('H');
if ($festival_start_hour > 12) $festival_start_hour -= 12;

$festival_end_hour = $festival_end_time->format('H');
if ($festival_end_hour > 12) $festival_end_hour -= 12;

$output["time_guide"] = [];

for ($i = $festival_start_hour; $i <= $festival_end_hour; $i++) {
    $clock_twenty_four = $i + 12;
    $time_since_start = new DateTime("2023-08-30 $clock_twenty_four:00:00");
    $diff_in_seconds = $time_since_start->getTimestamp() - $festival_start_time->getTimestamp();
    $timefactor = floor($diff_in_seconds / 60) * 3;
    if ($i == 4) {
        $output["time_guide"][] = ["time"=>"$i pm", "open"=>true, "timefactor"=>$timefactor];
    } else {
        $output["time_guide"][] = ["time"=>"$i pm", "timefactor"=>$timefactor];
    }
}

$output["shows"] = $shows;

echo $m->render("clashFinderDesktop", $output);

require_once("../../secure/mlc/db_disconnect.php");

?>