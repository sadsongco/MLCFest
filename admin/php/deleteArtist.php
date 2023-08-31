<?php

require_once("../../../secure/mlc/db_connect.php");

$id = $_GET['id'];

try {
    $query = "DELETE FROM Shows WHERE artist = ?;";
    $stmt = $db->prepare($query);
    $stmt->execute([$id]);
    $query = "DELETE FROM Artists WHERE artist_id = ?;";
    $stmt = $db->prepare($query);
    $stmt->execute([$id]);
}
catch(PDOException $e) {
    echo $e->getmessage();
}

header ('HX-Trigger-After-Settle:newArtist');

require_once("../../../secure/mlc/db_disconnect.php");

?>