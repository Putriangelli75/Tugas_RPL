<?php

session_start();

require '../config/koneksi.php';

$data = $db->query(
    "SELECT * FROM lapangan
WHERE status='aktif'"
);

include '../layouts/header.php';
?>

<div class="container-fluid">

    <div class="row">

        <?php include '../layouts/sidebar_pelanggan.php'; ?>

        <div class="col-md-10">

            <div class="content">

                <h2 class="mb-4">
                    Booking Lapangan
                </h2>

                <div class="row">

                    <?php
                    while (
                        $row =
                        $data->fetch(PDO::FETCH_ASSOC)
                    ) {
                    ?>

                        <div class="col-md-4">

                            <div class="card shadow mb-4">

                                <img
                                    src="../assets/img/banner.jpg"
                                    height="180"
                                    style="object-fit:cover;">

                                <div class="card-body">

                                    <h5>

                                        <?= $row['nama_lapangan'] ?>

                                    </h5>

                                    <p>

                                        <?= $row['jenis_olahraga'] ?>

                                    </p>

                                    <h4 class="text-success">

                                        Rp
                                        <?= number_format(
                                            $row['harga_per_jam']
                                        ) ?>

                                    </h4>

                                    <a
                                        href="booking.php?id=<?= $row['id_lapangan'] ?>"
                                        class="btn btn-success w-100">

                                        Booking

                                    </a>

                                </div>

                            </div>

                        </div>

                    <?php } ?>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include '../layouts/footer.php'; ?>