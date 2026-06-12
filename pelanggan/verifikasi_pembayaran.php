<?php

session_start();

if(
    !isset($_SESSION['id_user']) ||
    $_SESSION['role'] != 'pelanggan'
){
    header("Location: ../auth/login.php");
    exit;
}

require '../config/koneksi.php';

$id_booking = $_GET['id'];

$stmt = $db->prepare("
SELECT
    b.*,
    l.nama_lapangan,
    l.jenis_olahraga,
    l.gambar
FROM booking b
JOIN lapangan l
ON b.id_lapangan=l.id_lapangan
WHERE b.id_booking=?
");

$stmt->execute([$id_booking]);

$data = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$data){
    die("Data booking tidak ditemukan");
}

if(isset($_POST['upload'])){

    $metode = $_POST['metode'];

    $namaFile = '';

    if(
        isset($_FILES['bukti']) &&
        $_FILES['bukti']['error']==0
    ){

        $ext = pathinfo(
            $_FILES['bukti']['name'],
            PATHINFO_EXTENSION
        );

        $namaFile =
            time().".".$ext;

        move_uploaded_file(
            $_FILES['bukti']['tmp_name'],
            "../uploads/".$namaFile
        );

        $update = $db->prepare("
        UPDATE booking
        SET
            bukti_pembayaran=?,
            metode_pembayaran=?
        WHERE id_booking=?
        ");

        $update->execute([
            $namaFile,
            $metode,
            $id_booking
        ]);

        echo "
        <script>
        alert('Bukti pembayaran berhasil dikirim');
        window.location='riwayat_booking.php';
        </script>
        ";
        exit;
    }
}

include '../layouts/header.php';

?>

<div class="container mt-4">

    <div class="row">

        <!-- DETAIL -->

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header bg-dark text-white">

                    Detail Booking

                </div>

                <div class="card-body">

                    <?php if(!empty($data['gambar'])){ ?>

                        <img
                            src="../uploads/<?= $data['gambar'] ?>"
                            class="img-fluid rounded mb-3">

                    <?php } ?>

                    <table class="table">

                        <tr>
                            <td>Kode Booking</td>
                            <td><?= $data['kode_booking'] ?></td>
                        </tr>

                        <tr>
                            <td>Lapangan</td>
                            <td><?= $data['nama_lapangan'] ?></td>
                        </tr>

                        <tr>
                            <td>Jenis</td>
                            <td><?= $data['jenis_olahraga'] ?></td>
                        </tr>

                        <tr>
                            <td>Tanggal</td>
                            <td><?= $data['tanggal_booking'] ?></td>
                        </tr>

                        <tr>
                            <td>Jam</td>
                            <td><?= $data['jam_mulai'] ?></td>
                        </tr>

                        <tr>
                            <td>Durasi</td>
                            <td><?= $data['durasi'] ?> Jam</td>
                        </tr>

                        <tr>
                            <td>Total Biaya</td>
                            <td>
                                Rp <?= number_format($data['total_bayar']) ?>
                            </td>
                        </tr>

                    </table>

                </div>

            </div>

        </div>

        <!-- FORM UPLOAD -->

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header bg-dark text-white">

                    Upload Bukti Pembayaran DP

                </div>

                <div class="card-body">

                    <form
                        method="POST"
                        enctype="multipart/form-data">

                        <div class="mb-3">

                            <label>
                                Nominal Transfer
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="Rp <?= number_format($data['total_bayar'] * 0.5) ?>"
                                readonly>

                        </div>

                        <div class="mb-3">

                            <label>
                                Metode Pembayaran
                            </label>

                            <select
                                name="metode"
                                class="form-control">

                                <option>
                                    Transfer Bank BCA
                                </option>

                                <option>
                                    Transfer Bank BRI
                                </option>

                                <option>
                                    Transfer Bank Mandiri
                                </option>

                                <option>
                                    Transfer Bank BNI
                                </option>

                            </select>

                        </div>

                        <div class="mb-3">

                            <label>
                                Upload Bukti Transfer
                            </label>

                            <input
                                type="file"
                                name="bukti"
                                class="form-control"
                                accept=".jpg,.jpeg,.png"
                                required>

                        </div>

                        <button
                            name="upload"
                            class="btn btn-success w-100">

                            Kirim Bukti Pembayaran

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include '../layouts/footer.php'; ?>