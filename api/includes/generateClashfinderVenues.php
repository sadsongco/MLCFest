<?php

function generateClashfinderVenues($venues) {
    $output = [];
    include ("./includes/colourPalette.php");
    $colours = ["#09aa09", "#0959aa", "#aa09aa", "#aa5909"];

    foreach ($venues as $venue) {
        $venue_colour = array_shift($colours);
        $colours[] = $venue_colour;
        $venue_colour_obj = new ColorPalette($venue_colour);
        $venue_palette = $venue_colour_obj->createPalette();
        $output[] = ["venue_name"=>$venue["name"],
        "venue_id"=>$venue["venue_id"],
        "shows"=>[],
        "colour"=>$venue_colour,
        "palette"=>$venue_palette];
    }
    return $output;
}

?>