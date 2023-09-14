<?php

function getParams($db) {
    try {
        $query = "SELECT param_name, param_value FROM Params";
        $params = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
        throw $e;
    }
    
    $params_arr = [];
    foreach($params as $param) {
        $params_arr[$param["param_name"]] = $param["param_value"];
    }
    return $params_arr;
}

?>