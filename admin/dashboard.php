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

$totalUser = $db->query(
    "SELECT COUNT(*) FROM users"
)->fetchColumn();

$totalLapangan = $db->query(
    "SELECT COUNT(*) FROM lapangan"
)->fetchColumn();

$totalBooking = $db->query(
    "SELECT COUNT(*) FROM booking"
)->fetchColumn();

include '../layouts/header.php';

?>

<div class="container-fluid">

    <div class="row">

        <?php
        include '../layouts/sidebar_admin.php';
        ?>

        <div class="col-md-10">

            <div class="content">

                <div class="hero-banner mb-4">

                    <div>

                        <h1>Dashboard Admin</h1>

                        <p>
                            Kelola seluruh aktivitas booking lapangan
                            dengan mudah.
                        </p>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4">

                        <div class="card card-stat shadow">

                            <div class="card-body text-center">

                                <h5>Total User</h5>

                                <h1>

                                    <?= $totalUser ?>

                                </h1>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="card card-stat shadow">

                            <div class="card-body text-center">

                                <h5>Total Lapangan</h5>

                                <h1>

                                    <?= $totalLapangan ?>

                                </h1>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="card card-stat shadow">

                            <div class="card-body text-center">

                                <h5>Total Booking</h5>

                                <h1>

                                    <?= $totalBooking ?>

                                </h1>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="card mt-4 shadow">

                    <div class="card-header bg-success text-white">

                        Data Lapangan Terbaru

                    </div>

                    <div class="card-body">

                        <table class="table table-striped">

                            <thead>

                                <tr>

                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    <th>Harga</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php

                                $data = $db->query(
                                    "SELECT *
FROM lapangan
ORDER BY id_lapangan DESC
LIMIT 5"
                                );

                                while (
                                    $row =
                                    $data->fetch(PDO::FETCH_ASSOC)
                                ) {

                                ?>

                                    <tr>

                                        <td>
                                            <?= $row['id_lapangan'] ?>
                                        </td>

                                        <td>
                                            <?= $row['nama_lapangan'] ?>
                                        </td>

                                        <td>
                                            <?= $row['jenis_olahraga'] ?>
                                        </td>

                                        <td>

                                            Rp
                                            <?= number_format(
                                                $row['harga_per_jam']
                                            ) ?>

                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php
include '../layouts/footer.php';
?>