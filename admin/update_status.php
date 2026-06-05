<?php

session_start();

if (
    !isset($_SESSION['id_user']) ||
    $_SESSION['role'] != 'admin'
) {
    header("Location: ../auth/login.php");
    exit;
}

require '../config/koneksi.php';

$id = $_GET['id'];
$status = $_GET['status'];

$stmt = $db->prepare("
UPDATE booking
SET status=?
WHERE id_booking=?
");

$stmt->execute([
    $status,
    $id
]);

header(
    "Location: booking.php"
);

exit;
