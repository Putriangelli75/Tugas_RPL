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

$stmt = $db->prepare("
SELECT *
FROM lapangan
WHERE id_lapangan = ?
");

$stmt->execute([$id_lapangan]);

$lapangan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lapangan) {
    die("Lapangan tidak ditemukan");
}

if (isset($_POST['booking'])) {

    $tanggal = $_POST['tanggal'];
    $jam     = $_POST['jam'];
    $durasi  = $_POST['durasi'];

    $total = $lapangan['harga_per_jam'] * $durasi;

    $kode = 'BK' . time();

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
        status
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
        'pending'

    ]);

    $db->exec("
        UPDATE users
        SET poin = poin + 10
        WHERE id_user = " . $_SESSION['id_user']
    );

    header("Location: riwayat_booking.php");
    exit;
}

include '../layouts/header.php';

?>

<div class="container mt-5">

    <div class="card shadow mb-4">

        <div class="card-header bg-success text-white">

            Booking Lapangan

        </div>

        <div class="card-body">

            <h3 class="mb-4">

                <?= htmlspecialchars($lapangan['nama_lapangan']) ?>

            </h3>

            <form method="POST">

                <div class="mb-3">

                    <label class="form-label">

                        Tanggal Booking

                    </label>

                    <input
                        type="date"
                        name="tanggal"
                        class="form-control"
                        required>

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Jam Mulai

                    </label>

                    <input
                        type="time"
                        name="jam"
                        class="form-control"
                        required>

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Durasi

                    </label>

                    <select
                        name="durasi"
                        class="form-control">

                        <option value="1">1 Jam</option>
                        <option value="2">2 Jam</option>
                        <option value="3">3 Jam</option>

                    </select>

                </div>

                <div class="alert alert-info">

                    <strong>Informasi:</strong><br>

                    Setelah booking berhasil dibuat,
                    silakan lakukan pembayaran DP melalui menu
                    <b>Riwayat Booking</b> dengan menekan tombol
                    <b>DP Sekarang</b>.

                </div>

                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        name="booking"
                        class="btn btn-success">

                        Booking Sekarang

                    </button>

                    <a
                        href="lapangan.php"
                        class="btn btn-secondary">

                        Kembali

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

<?php include '../layouts/footer.php'; ?>