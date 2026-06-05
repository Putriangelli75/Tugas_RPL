<?php

require 'koneksi.php';

$cek = $db->query(
"SELECT COUNT(*) FROM lapangan"
)->fetchColumn();

if($cek == 0){

    $db->exec("
    INSERT INTO lapangan
    (nama_lapangan,jenis_olahraga,harga_per_jam,status)
    VALUES
    ('Futsal A','Futsal',150000,'aktif'),
    ('Futsal B','Futsal',180000,'aktif'),
    ('Badminton 1','Badminton',50000,'aktif'),
    ('Badminton 2','Badminton',50000,'aktif'),
    ('Basket Indoor','Basket',250000,'aktif')
    ");

    echo "Seeder berhasil";

}else{

    echo "Data lapangan sudah ada";

}