<?php

require '../config/koneksi.php';

$id = $_GET['id'];

$stmt = $db->prepare(
    "DELETE FROM lapangan
WHERE id_lapangan=?"
);

$stmt->execute([$id]);

header(
    "Location: lapangan.php"
);

exit;
