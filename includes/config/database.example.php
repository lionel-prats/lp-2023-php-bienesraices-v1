<?php 

function conectarDB():mysqli {
    $db = mysqli_connect('host', 'host_user', 'host_password', 'db_name');
    
    if (!$db) {
        echo "Error: no se pudo conectar";
        exit; 
    } 

    return $db;
}