<?php

try {

    $db = new PDO(
        "sqlite:" . __DIR__ . "/../database/splj.db"
    );

    $db->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch(PDOException $e){

    die(
        "Koneksi gagal : "
        . $e->getMessage()
    );

}