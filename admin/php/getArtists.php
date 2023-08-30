<?php

require_once("../../../secure/mlc/db_connect.php");

$query = "SELECT * FROM Artists;";

$result = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $row) {
    echo "<div>";
    print_r($row);
    echo "</div>";
}

require_once("../../../secure/mlc/db_disconnect.php");

?>