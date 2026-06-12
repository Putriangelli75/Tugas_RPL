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

if(isset($_POST['simpan'])){

    $nama  = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $no_hp = trim($_POST['no_hp']);

    $update = $db->prepare("
    UPDATE users
    SET
        nama=?,
        email=?,
        no_hp=?
    WHERE id_user=?
    ");

    $update->execute([
        $nama,
        $email,
        $no_hp,
        $id_user
    ]);

    echo "
    <script>
    alert('Profil berhasil diperbarui');
    window.location='akun.php';
    </script>
    ";
    exit;
}

include '../layouts/header.php';
?>

<div class="container mt-4">

    <div class="card shadow">

        <div class="card-header bg-success text-white">

            Edit Profil

        </div>

        <div class="card-body">

            <form method="POST">

                <div class="mb-3">

                    <label>Nama</label>

                    <input
                        type="text"
                        name="nama"
                        class="form-control"
                        value="<?= htmlspecialchars($user['nama']) ?>"
                        required>

                </div>

                <div class="mb-3">

                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($user['email']) ?>"
                        required>

                </div>

                <div class="mb-3">

                    <label>No HP</label>

                    <input
                        type="text"
                        name="no_hp"
                        class="form-control"
                        value="<?= htmlspecialchars($user['no_hp']) ?>">

                </div>

                <button
                    type="submit"
                    name="simpan"
                    class="btn btn-success">

                    Simpan

                </button>

                <a
                    href="akun.php"
                    class="btn btn-secondary">

                    Kembali

                </a>

            </form>

        </div>

    </div>

</div>

<?php include '../layouts/footer.php'; ?>