<?php

function getShows($db) {    
    try {
        $output = [];
        $query = "SELECT venue_id, name, colour FROM Venues;";
        $output["venues"] = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
        
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
        
        $output["shows"] = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
        return $output;
    } catch (PDOException $e) {
        throw $e;
    }
}

?>