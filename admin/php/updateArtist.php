<?php

require_once("../../../secure/mlc/db_connect.php");

foreach($_POST as $key=>$value) {
    if ($key != "artistUpdate") {
        $params[$key] = $value;
    }
}

try {
    $query = "UPDATE Artists
    SET name=:name, bio=:bio, instagram=:instagram, twitter=:twitter, threads=:threads, facebook=:facebook
    WHERE artist_id=:artist_id;";
    $stmt = $db->prepare($query);
    $stmt->execute($params);
}
catch(PDOException $e) {
    echo $e->getmessage();
}
header ('HX-Trigger:clearVenueForm');
header ('HX-Trigger-After-Settle:newArtist');

require_once("../../../secure/mlc/db_disconnect.php");

?>