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

$data = $db->query("
SELECT
    b.*,
    l.nama_lapangan,
    l.jenis_olahraga,
    l.gambar
FROM booking b
JOIN lapangan l
ON b.id_lapangan = l.id_lapangan
WHERE b.id_user = $id_user
AND b.status != 'dibatalkan'
ORDER BY b.id_booking DESC
");

include '../layouts/header.php';

?>

<div class="container-fluid">

   <?php

if (
    !isset($_SESSION['id_user']) ||
    $_SESSION['role'] != 'pelanggan'
) {
    header("Location: ../auth/login.php");
    exit;
}

require '../config/koneksi.php';

$id_user = $_SESSION['id_user'];

$data = $db->query("
SELECT
    b.*,
    l.nama_lapangan,
    l.jenis_olahraga,
    l.gambar
FROM booking b
JOIN lapangan l
ON b.id_lapangan = l.id_lapangan
WHERE b.id_user = $id_user
AND b.status != 'dibatalkan'
ORDER BY b.id_booking DESC
");

include '../layouts/header.php';

?>

<style>

.booking-card{
    border:none;
    border-radius:20px;
    overflow:hidden;
}

.booking-img{
    width:100%;
    height:220px;
    object-fit:cover;
    border-radius:15px;
}

.booking-card table td{
    border:none;
    padding:6px 0;
}

.status-badge{
    padding:8px 15px;
    font-size:14px;
}

</style>

<div class="row">

    <?php include '../layouts/sidebar_pelanggan.php'; ?>

    <div class="col-md-10">

        <div class="content p-4">

            <h2 class="mb-4">
                Riwayat Booking
            </h2>

            <?php while($row = $data->fetch(PDO::FETCH_ASSOC)) { ?>

            <div class="card shadow-sm booking-card mb-4">

                <div class="card-body p-4">

                    <div class="row align-items-center">

                        <!-- FOTO -->

                        <div class="col-md-3">

                            <?php if(!empty($row['gambar'])) { ?>

                                <img
                                    src="../uploads/<?= $row['gambar'] ?>"
                                    class="booking-img">

                            <?php } else { ?>

                                <img
                                    src="../assets/img/banner.jpg"
                                    class="booking-img">

                            <?php } ?>

                        </div>

                        <!-- DETAIL -->

                        <div class="col-md-9">

                            <div class="d-flex justify-content-between align-items-center">

                                <h4 class="mb-0">
                                    Detail Booking
                                </h4>

                                <?php

                                if($row['status']=='disetujui'){
                                    echo "<span class='badge bg-success status-badge'>Confirmed</span>";
                                }
                                elseif($row['status']=='pending'){
                                    echo "<span class='badge bg-warning text-dark status-badge'>Pending</span>";
                                }
                                elseif($row['status']=='ditolak'){
                                    echo "<span class='badge bg-danger status-badge'>Ditolak</span>";
                                }
                                elseif($row['status']=='selesai'){
                                    echo "<span class='badge bg-primary status-badge'>Selesai</span>";
                                }

                                ?>

                            </div>

                            <hr>

                            <table class="table table-borderless">

                                <tr>
                                    <td width="180"><b>Kode Booking</b></td>
                                    <td><?= $row['kode_booking'] ?></td>
                                </tr>

                                <tr>
                                    <td><b>Lapangan</b></td>
                                    <td><?= $row['nama_lapangan'] ?></td>
                                </tr>

                                <tr>
                                    <td><b>Jenis Olahraga</b></td>
                                    <td><?= $row['jenis_olahraga'] ?></td>
                                </tr>

                                <tr>
                                    <td><b>Tanggal</b></td>
                                    <td>
                                        <?= date(
                                            'd M Y',
                                            strtotime($row['tanggal_booking'])
                                        ) ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td><b>Jam Mulai</b></td>
                                    <td><?= $row['jam_mulai'] ?></td>
                                </tr>

                                <tr>
                                    <td><b>Durasi</b></td>
                                    <td><?= $row['durasi'] ?> Jam</td>
                                </tr>

                                <tr>
                                    <td><b>Total Biaya</b></td>
                                    <td>
                                        Rp <?= number_format(
                                            $row['total_bayar']
                                        ) ?>
                                    </td>
                                </tr>

                            </table>

                            <?php if($row['status']=='pending'){ ?>

                                <div class="mt-3">

                                    <a
                                        href="verifikasi_pembayaran.php?id=<?= $row['id_booking'] ?>"
                                        class="btn btn-success">

                                        DP Sekarang

                                    </a>

                                    <a
                                        href="batal_booking.php?id=<?= $row['id_booking'] ?>"
                                        class="btn btn-outline-danger"
                                        onclick="return confirm('Batalkan booking ini?')">

                                        Batalkan Booking

                                    </a>

                                </div>

                            <?php } ?>

                        </div>

                    </div>

                </div>

            </div>

         <?php } ?>

        </div>

    </div>

</div>

<?php include '../layouts/footer.php'; ?>