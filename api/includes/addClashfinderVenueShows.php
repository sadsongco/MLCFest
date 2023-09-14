<?php

include_once("print_r2.php");

function addClashfinderVenueShows(&$venues, $shows, $params_arr, $clash_finder_size, $admin=false) {
    foreach ($shows as $show) {
        foreach ($venues as &$venue) {
            if ($show["venue_id"] == $venue["venue_id"]) {
                $venue["shows"][] = $show;
                continue;
            }
        }
    }

    foreach ($venues as &$venue) {
        $festival_end = new DateTime($params_arr["festivalEnd"]);
        $venue_col = new ColorPalette($venue["colour"]);
        $show_colours = $venue_col->createPalette();
        $prev_show["end_time"] = $params_arr["festivalStart"];
        foreach ($venue["shows"] as &$show) {
            $prev_show["after_spacer"] = 0;
            $show["show_colour"] = array_shift($show_colours);
            $show_colours[] = $show["show_colour"];
            $show_start = new DateTime($show["start_time"]);
            $show_end = new DateTime($show["end_time"]);
            $show_time_seconds = $show_end->getTimestamp() - $show_start->getTimestamp();
            $show["pixel_size"] = floor($show_time_seconds / 60) * $clash_finder_size;
            $prev_show_end = new DateTime($prev_show["end_time"]);
            $diff_in_seconds = $show_start->getTimestamp() - $prev_show_end->getTimestamp();
            $show["changeover"] = floor($diff_in_seconds / 60) * $clash_finder_size;
            $after_spacer_seconds = $festival_end->getTimestamp() - $show_end->getTimestamp();
            $show["after_spacer"] = floor($after_spacer_seconds / 60) * $clash_finder_size;
            if ($admin == true) $show["admin"] = true;
            $prev_show = &$show;
        }
    }
    // print_r2($venues);
}

?>