<?php

function generateClashfinderVenues($venues) {
    $output = [];
    include ("./includes/colourPalette.php");

    foreach ($venues as $venue) {
        // print_r2($venue);
        // continue;
        $venue_colour_obj = new ColorPalette("#".$venue["colour"]);
        $venue_palette = $venue_colour_obj->createPalette();
        $output[] = ["venue_name"=>$venue["name"],
        "venue_id"=>$venue["venue_id"],
        "shows"=>[],
        "colour"=>"#".$venue["colour"],
        "palette"=>$venue_palette];
    }
    return $output;
}

?>