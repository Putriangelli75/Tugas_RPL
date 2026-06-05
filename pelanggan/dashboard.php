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

$user = $db->query("
SELECT *
FROM users
WHERE id_user = $id_user
")->fetch(PDO::FETCH_ASSOC);

$totalBooking = $db->query("
SELECT COUNT(*)
FROM booking
WHERE id_user = $id_user
")->fetchColumn();

include '../layouts/header.php';
?>

<div class="container-fluid">

    <div class="row">

        <?php include '../layouts/sidebar_pelanggan.php'; ?>

        <div class="col-md-10">

            <div class="content">

                <div class="hero-banner mb-4">

                    <div>

                        <h1>
                            Halo,
                            <?= $user['nama']; ?>
                        </h1>

                        <p>
                            Selamat datang di Sistem Pemesanan
                            Lapangan Olahraga
                        </p>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4">

                        <div class="card card-stat shadow">

                            <div class="card-body text-center">

                                <h5>Total Booking</h5>

                                <h1><?= $totalBooking ?></h1>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="card card-stat shadow">

                            <div class="card-body text-center">

                                <h5>Membership</h5>

                                <h3>

                                    <?= ucfirst(
                                        $user['membership']
                                    ) ?>

                                </h3>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="card card-stat shadow">

                            <div class="card-body text-center">

                                <h5>Reward Point</h5>

                                <h1>

                                    <?= $user['poin'] ?>

                                </h1>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include '../layouts/footer.php'; ?>