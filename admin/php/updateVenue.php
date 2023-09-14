<?php

require_once("../../../secure/mlc/db_connect.php");

foreach($_POST as $key=>$value) {
    if ($key != "venueUpdate") {
        $params[$key] = $value;
    }
}

try {
    $query = "UPDATE Venues
    SET name=:name, post_code=:post_code, address_1=:address_1, address_2=:address_2, city=:city, colour=:colour
    WHERE venue_id=:venue_id;";
    $stmt = $db->prepare($query);
    $stmt->execute($params);
}
catch(PDOException $e) {
    echo $e->getmessage();
}
header ('HX-Trigger:clearVenueForm');
header ('HX-Trigger-After-Settle:newVenue');

require_once("../../../secure/mlc/db_disconnect.php");

?>