<?php

require_once("../../../secure/mlc/db_connect.php");

$query = "SELECT Shows.start_time, Shows.end_time, Shows.notes, Artists.name AS artist_name, Venues.name AS venue_name FROM Shows
            LEFT JOIN Artists ON Shows.artist = Artists.artist_id
            LEFT JOIN Venues ON Shows.venue = Venues.venue_id
            ORDER BY start_time ASC
            ;";

$result = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $row) {
    echo "<div>";
    print_r($row);
    echo "</div>";
}

require_once("../../../secure/mlc/db_disconnect.php");

?>