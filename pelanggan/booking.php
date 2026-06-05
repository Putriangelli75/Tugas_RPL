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

$id_lapangan = $_GET['id'];

$lapangan = $db->query("
SELECT *
FROM lapangan
WHERE id_lapangan = $id_lapangan
")->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['booking'])) {

    $tanggal = $_POST['tanggal'];
    $jam = $_POST['jam'];
    $durasi = $_POST['durasi'];

    $total =
        $lapangan['harga_per_jam']
        * $durasi;

    $kode =
        'BK' . time();

    $namaFile = '';

    if (
        isset($_FILES['bukti']) &&
        $_FILES['bukti']['error'] == 0
    ) {

        $ext =
            pathinfo(
                $_FILES['bukti']['name'],
                PATHINFO_EXTENSION
            );

        $namaFile =
            time() . "." . $ext;

        move_uploaded_file(
            $_FILES['bukti']['tmp_name'],
            "../uploads/" . $namaFile
        );
    }

    $stmt = $db->prepare("
    INSERT INTO booking
    (
        kode_booking,
        id_user,
        id_lapangan,
        tanggal_booking,
        jam_mulai,
        durasi,
        total_bayar,
        bukti_pembayaran
    )
    VALUES
    (
        ?,?,?,?,?,?,?,?
    )
    ");

    $stmt->execute([

        $kode,
        $_SESSION['id_user'],
        $id_lapangan,
        $tanggal,
        $jam,
        $durasi,
        $total,
        $namaFile

    ]);

    $db->exec(
        "
    UPDATE users
    SET poin = poin + 10
    WHERE id_user =
    " . $_SESSION['id_user']
    );

    header(
        "Location: riwayat_booking.php"
    );

    exit;
}

include '../layouts/header.php';
?>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header">
            Booking Lapangan
        </div>

        <div class="card-body">

            <h3>

                <?= $lapangan['nama_lapangan'] ?>

            </h3>

            <form
                method="POST"
                enctype="multipart/form-data">

                <div class="mb-3">

                    <label>Tanggal</label>

                    <input
                        type="date"
                        name="tanggal"
                        class="form-control"
                        required>

                </div>

                <div class="mb-3">

                    <label>Jam Mulai</label>

                    <input
                        type="time"
                        name="jam"
                        class="form-control"
                        required>

                </div>

                <div class="mb-3">

                    <label>Durasi</label>

                    <select
                        name="durasi"
                        class="form-control">

                        <option value="1">1 Jam</option>
                        <option value="2">2 Jam</option>
                        <option value="3">3 Jam</option>

                    </select>

                </div>

                <div class="mb-3">

                    <label>
                        Upload Bukti Pembayaran
                    </label>

                    <input
                        type="file"
                        name="bukti"
                        class="form-control"
                        accept=".jpg,.jpeg,.png"
                        required>

                </div>

                <button
                    name="booking"
                    class="btn btn-success">

                    Booking Sekarang

                </button>

            </form>

        </div>

    </div>

</div>

<?php include '../layouts/footer.php'; ?>