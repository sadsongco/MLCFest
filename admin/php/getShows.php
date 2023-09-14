<?php

require_once("../../../secure/mlc/db_connect.php");
include_once("../../api/includes/getParams.php");
include_once("../../api/includes/generateTimeGuide.php");
include_once("../../api/includes/generateClashfinderVenues.php");
include_once("../../api/includes/addClashfinderVenueShows.php");

require '../../lib/mustache.php-main/src/Mustache/Autoloader.php';
Mustache_Autoloader::register();

$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader('../../templates'),
    'partials_loader' => new Mustache_Loader_FilesystemLoader('../../templates/partials')
));

$output = [];

try {
    $query = "SELECT venue_id, name, colour FROM Venues;";
    $venues = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    
    $query = "SELECT Shows.start_time, Shows.end_time, Shows.notes, Shows.show_id,
                DATE_FORMAT(Shows.start_time, '%H:%i') AS start_disp, DATE_FORMAT(Shows.end_time, '%H:%i') AS end_disp, 
                ROUND(TIME_TO_SEC(TIMEDIFF(end_time, start_time))/60) AS show_length,
                Artists.name AS artist_name,
                Venues.venue_id AS venue_id
                FROM Shows
                LEFT JOIN Artists ON Shows.artist = Artists.artist_id
                LEFT JOIN Venues ON Shows.venue = Venues.venue_id
                ORDER BY start_time ASC
                ;";
    
    $shows = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    $params_arr = getParams($db);
} catch (PDOException $e) {
    echo $e->getMessage();
}

$clash_finder_size = 2;

if (count($shows) > 0) $output["time_guide"] = generateTimeGuide($params_arr, $clash_finder_size);

$output["venues"] = generateClashfinderVenues($venues);

addClashfinderVenueShows($output["venues"], $shows, $params_arr, $clash_finder_size, true);

echo $m->render("clashFinderDesktop", $output);

require_once("../../../secure/mlc/db_disconnect.php");

?>