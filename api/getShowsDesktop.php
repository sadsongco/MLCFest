<?php

require_once("../../secure/mlc/db_connect.php");
include_once("includes/print_r2.php");
include_once("includes/getShows.php");
include_once("includes/getParams.php");
include_once("includes/generateTimeGuide.php");
include_once("includes/generateClashfinderVenues.php");
include_once("includes/addClashfinderVenueShows.php");

require '../lib/mustache.php-main/src/Mustache/Autoloader.php';
Mustache_Autoloader::register();

$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader('../templates'),
    'partials_loader' => new Mustache_Loader_FilesystemLoader('../templates/partials')
));

$output = [];

try {
    $show_arr = getShows($db);
    $params_arr = getParams($db);
} catch (PDOException $e) {
    echo $e->getMessage();
}

$mob = false;

$clash_finder_size = 2;

$output["time_guide"] = generateTimeGuide($params_arr, $clash_finder_size);

$output["venues"] = generateClashfinderVenues($show_arr["venues"]);

addClashfinderVenueShows($output["venues"], $show_arr["shows"], $params_arr, $clash_finder_size);

echo $m->render("clashFinderDesktop", $output);

require_once("../../secure/mlc/db_disconnect.php");

?>