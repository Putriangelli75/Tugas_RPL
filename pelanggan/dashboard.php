<?php

session_start();

if (
    !isset($_SESSION['id_user']) ||
    $_SESSION['role'] != 'pelanggan'
) {
    header("Location: ../auth/login.php");
    exit;
}

require '../config/koneksi.php';

$id_user = $_SESSION['id_user'];

$stmt = $db->prepare("
SELECT *
FROM users
WHERE id_user=?
");

$stmt->execute([$id_user]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

$totalBooking = $db->prepare("
SELECT COUNT(*)
FROM booking
WHERE id_user=?
");

$totalBooking->execute([$id_user]);

$totalBooking = $totalBooking->fetchColumn();

$bookingAktif = $db->prepare("
SELECT COUNT(*)
FROM booking
WHERE id_user=?
AND status IN ('pending','disetujui')
");

$bookingAktif->execute([$id_user]);

$bookingAktif = $bookingAktif->fetchColumn();

$poin = $user['poin'];

$membership = ucfirst(
    $user['membership']
);

include '../layouts/header.php';

?>

<div class="container-fluid">

    <div class="row">

        <?php include '../layouts/sidebar_pelanggan.php'; ?>

        <div class="col-md-10">

            <div class="content">

                <!-- HEADER -->

                <div class="hero-banner mb-4">

                    <div>

                        <h2>
                            Halo,
                            <?= htmlspecialchars($user['nama']) ?>!
                        </h2>

                        <p>
                            Selamat datang kembali di SPLJ
                        </p>

                    </div>

                </div>

                <!-- KARTU STATISTIK -->

                <div class="row mb-4">

                    <div class="col-md-3">

                        <div class="card shadow border-0">

                            <div class="card-body">

                                <h6>Total Booking</h6>

                                <h2>

                                    <?= $totalBooking ?>

                                </h2>

                                <small class="text-muted">
                                    Booking
                                </small>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="card shadow border-0">

                            <div class="card-body">

                                <h6>Booking Aktif</h6>

                                <h2>

                                    <?= $bookingAktif ?>

                                </h2>

                                <small class="text-muted">
                                    Booking
                                </small>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="card shadow border-0">

                            <div class="card-body">

                                <h6>Reward Point</h6>

                                <h2>

                                    <?= $poin ?>

                                </h2>

                                <small class="text-muted">
                                    Point
                                </small>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="card shadow border-0">

                            <div class="card-body">

                                <h6>Member Status</h6>

                                <h3>

                                    <?= $membership ?>

                                </h3>

                                <small class="text-muted">

                                    <?= $membership == 'Premium'
                                        ? 'Member Premium'
                                        : 'Member Regular'
                                    ?>

                                </small>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- JADWAL + PROMO -->

                <div class="row">

                    <!-- JADWAL -->

                    <div class="col-md-6">

                        <div class="card shadow">

                            <div class="card-header">

                                <strong>
                                    Jadwal Terdekat
                                </strong>

                            </div>

                            <div class="card-body">

                                <div class="card-body">

                                    <?php
                                    $jadwal = $db->prepare("
SELECT
    booking.*,
    lapangan.nama_lapangan
FROM booking
JOIN lapangan
ON booking.id_lapangan = lapangan.id_lapangan
WHERE booking.id_user = ?
AND booking.status IN ('pending','disetujui')
AND datetime(
        booking.tanggal_booking || ' ' ||
        booking.jam_mulai
    ) >= datetime('now')
ORDER BY booking.tanggal_booking ASC,
         booking.jam_mulai ASC
LIMIT 3
");

                                    $jadwal->execute([$id_user]);

                                    $dataJadwal =
                                        $jadwal->fetchAll(PDO::FETCH_ASSOC);

                                    if (count($dataJadwal) == 0) {

                                        echo "
    <p class='text-muted'>
        Belum ada booking
    </p>
    ";
                                    } else {

                                        foreach ($dataJadwal as $row) {

                                            $badge = "secondary";

                                            if ($row['status'] == 'disetujui') {
                                                $badge = "success";
                                            }

                                            if ($row['status'] == 'pending') {
                                                $badge = "warning";
                                            }

                                    ?>

                                            <div class="border rounded p-3 mb-3">

                                                <h6>

                                                    <?= htmlspecialchars(
                                                        $row['nama_lapangan']
                                                    ) ?>

                                                </h6>

                                                <small>

                                                    <?= date(
                                                        'd M Y',
                                                        strtotime(
                                                            $row['tanggal_booking']
                                                        )
                                                    ) ?>

                                                </small>

                                                <br>

                                                <small>

                                                    <?= substr(
                                                        $row['jam_mulai'],
                                                        0,
                                                        5
                                                    ) ?>

                                                </small>

                                                <br><br>

                                                <span
                                                    class="badge bg-<?= $badge ?>">

                                                    <?= ucfirst(
                                                        $row['status']
                                                    ) ?>

                                                </span>

                                            </div>

                                    <?php

                                        }
                                    }

                                    ?>

                                </div>


                            </div>

                        </div>

                    </div>

                    <!-- PROMO -->

                    <div class="col-md-6">

                        <div class="card shadow">

                            <div class="card-header">

                                <strong>
                                    Promo Aktif
                                </strong>

                            </div>

                            <div class="card-body">

                                <div
                                    style="
                                    background:#198754;
                                    color:white;
                                    border-radius:15px;
                                    padding:30px;
                                    ">

                                    <h2>

                                        DISKON 20%

                                    </h2>

                                    <p>

                                        Untuk semua lapangan futsal

                                    </p>

                                    <p>

                                        Berlaku untuk member premium

                                    </p>

                                </div>

                                <div class="text-center mt-3">

                                    <a
                                        href="lapangan.php"
                                        class="btn btn-success">

                                        Booking Sekarang

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include '../layouts/footer.php'; ?>