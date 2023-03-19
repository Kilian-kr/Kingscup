<?php


function connect_to_sql(){
    $user = "";
    $password = "";
    $database = "";
    $host = "";
    $sql_con = mysqli_connect($host, $user, $password, $database);
    return $sql_con;

} 
?>
