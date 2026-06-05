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

$pesan = '';

if (isset($_POST['simpan'])) {

    $nama_lapangan   = trim($_POST['nama_lapangan']);
    $jenis_olahraga  = trim($_POST['jenis_olahraga']);
    $harga_per_jam   = trim($_POST['harga_per_jam']);
    $status          = $_POST['status'];

    // Validasi
    if (
        empty($nama_lapangan) ||
        empty($jenis_olahraga) ||
        empty($harga_per_jam)
    ) {

        $pesan = "
        <div class='alert alert-danger'>
            Semua field wajib diisi!
        </div>";

    } elseif (!is_numeric($harga_per_jam) || $harga_per_jam <= 0) {

        $pesan = "
        <div class='alert alert-danger'>
            Harga per jam harus berupa angka dan lebih dari 0.
        </div>";

    } else {

        $stmt = $db->prepare("
            INSERT INTO lapangan
            (
                nama_lapangan,
                jenis_olahraga,
                harga_per_jam,
                status
            )
            VALUES
            (
                ?, ?, ?, ?
            )
        ");

        $stmt->execute([
            $nama_lapangan,
            $jenis_olahraga,
            $harga_per_jam,
            $status
        ]);

        header("Location: lapangan.php");
        exit;
    }
}

include '../layouts/header.php';
?>

<div class="container-fluid">

    <div class="row">

        <?php include '../layouts/sidebar_admin.php'; ?>

        <div class="col-md-10">

            <div class="content p-4">

                <div class="card shadow">

                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">
                            Tambah Lapangan
                        </h4>
                    </div>

                    <div class="card-body">

                        <?= $pesan ?>

                        <form method="POST">

                            <div class="mb-3">

                                <label class="form-label">
                                    Nama Lapangan
                                </label>

                                <input
                                    type="text"
                                    name="nama_lapangan"
                                    class="form-control"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Jenis Olahraga
                                </label>

                                <input
                                    type="text"
                                    name="jenis_olahraga"
                                    class="form-control"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Harga Per Jam
                                </label>

                                <input
                                    type="number"
                                    name="harga_per_jam"
                                    class="form-control"
                                    min="1"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Status
                                </label>

                                <select
                                    name="status"
                                    class="form-control">

                                    <option value="aktif">
                                        Aktif
                                    </option>

                                    <option value="nonaktif">
                                        Non Aktif
                                    </option>

                                </select>

                            </div>

                            <div class="mt-4">

                                <a
                                    href="lapangan.php"
                                    class="btn btn-secondary">

                                    Kembali

                                </a>

                                <button
                                    type="submit"
                                    name="simpan"
                                    class="btn btn-success">

                                    Simpan Lapangan

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include '../layouts/footer.php'; ?>