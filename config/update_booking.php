<?php

require 'koneksi.php';

try{

    $db->exec("
    ALTER TABLE booking
    ADD COLUMN bukti_pembayaran TEXT
    ");

    echo "Kolom berhasil ditambahkan";

}catch(Exception $e){

    echo $e->getMessage();

}