<?php

require_once("../../../secure/mlc/db_connect.php");

$id = $_GET['id'];

try {
    $query = "DELETE FROM Shows WHERE show_id = ?;";
    $stmt = $db->prepare($query);
    $stmt->execute([$id]);
}
catch(PDOException $e) {
    echo $e->getmessage();
}

header ('HX-Trigger-After-Settle:newShow');

require_once("../../../secure/mlc/db_disconnect.php");

?>