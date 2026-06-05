<?php

require 'config/koneksi.php';

$data = $db->query("SELECT * FROM lapangan");

echo "<h2>Data Lapangan</h2>";

while($row = $data->fetch(PDO::FETCH_ASSOC)){

    echo $row['id_lapangan']
    ." | ".
    $row['nama_lapangan']
    ." | ".
    $row['jenis_olahraga']
    ." | Rp ".
    number_format($row['harga_per_jam']);

    echo "<br>";
}