<?php

require_once("../../../secure/mlc/db_connect.php");

foreach($_POST as $key=>$value) {
    if ($key != "submit") {
        $params[$key] = $value;
    }
}

try {
    $query = "INSERT INTO Venues VALUES (0, :name, :post_code, :address_1, :address_2, :city, :colour);";
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