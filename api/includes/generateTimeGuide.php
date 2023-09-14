<?php

include_once("print_r2.php");

function generateTimeGuide($params_arr, $grid_size = 3) {
    $festival_start = new DateTime($params_arr["festivalStart"]);
    $festival_end = new DateTime($params_arr["festivalEnd"]);

    $festival_length = $festival_start->diff($festival_end)->format("%H");

    $time_guide = [];
    $current_time = clone $festival_start;
    
    for ($i = 0; $i <= $festival_length; $i++) {
        $diff_in_seconds = $current_time->getTimestamp() - $festival_start->getTimestamp();
        $pixel_position = floor($diff_in_seconds / 60) * $grid_size;
        $display_time = $current_time->format("ga");
        if ($i == 0) {
            $time_guide[] = ["time"=>$display_time, "open"=>true, "pixel_position"=>$pixel_position, "margin_position"=>0];
        } else {
            $time_guide[] = ["time"=>$display_time, "pixel_position"=>$pixel_position, "margin_position"=>60 * $grid_size - 1];
        }
        $current_time = $current_time->add(new DateInterval('PT1H'));
    }
    return $time_guide;
}

?>