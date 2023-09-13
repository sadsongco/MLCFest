<?php

require_once("../../../secure/mlc/db_connect.php");


foreach($_POST as $key=>$value) {
    if ($key != "submit") {
        $params[] = [$key, $value, $value];
    }
}

// echo "<pre>"; print_r($params);echo "</pre>";
foreach ($params as $param) {
    try {
        $query = "INSERT INTO Params VALUES (?, ?)
            ON DUPLICATE KEY UPDATE param_value = ?;";
        $stmt = $db->prepare($query);
        $stmt->execute($param);
    }
    catch(PDOException $e) {
        echo $e->getmessage();
        exit;
    }
}
header ('HX-Trigger:updateParamsForm');

require_once("../../../secure/mlc/db_disconnect.php");

?>