<?php

session_start();

require '../config/koneksi.php';

$id_booking = $_GET['id'];
$id_user = $_SESSION['id_user'];

$stmt = $db->prepare("
DELETE FROM booking
WHERE id_booking=?
AND id_user=?
");

$stmt->execute([
    $id_booking,
    $id_user
]);

header("Location: riwayat_booking.php");
exit;