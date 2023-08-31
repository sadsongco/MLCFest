<?php

require_once("../../../secure/mlc/db_connect.php");

$id = $_GET['id'];

try {
    $query = "DELETE FROM Shows WHERE venue = ?;";
    $stmt = $db->prepare($query);
    $stmt->execute([$id]);
    $query = "DELETE FROM Venues WHERE venue_id = ?;";
    $stmt = $db->prepare($query);
    $stmt->execute([$id]);
}
catch(PDOException $e) {
    echo $e->getmessage();
}

header ('HX-Trigger-After-Settle:newVenue');

require_once("../../../secure/mlc/db_disconnect.php");

?>