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

if(isset($_POST['ubah'])){

    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi    = $_POST['konfirmasi'];

    if(
        !password_verify(
            $password_lama,
            $user['password']
        )
    ){
        $error = "Password lama salah";
    }
    elseif(
        $password_baru != $konfirmasi
    ){
        $error = "Konfirmasi password tidak cocok";
    }
    else{

        $hash = password_hash(
            $password_baru,
            PASSWORD_DEFAULT
        );

        $update = $db->prepare("
        UPDATE users
        SET password=?
        WHERE id_user=?
        ");

        $update->execute([
            $hash,
            $id_user
        ]);

        echo "
        <script>
        alert('Password berhasil diubah');
        window.location='akun.php';
        </script>
        ";
        exit;
    }
}

include '../layouts/header.php';
?>

<div class="container mt-4">

    <div class="card shadow">

        <div class="card-header bg-warning">

            Ubah Password

        </div>

        <div class="card-body">

            <?php if(isset($error)){ ?>

                <div class="alert alert-danger">

                    <?= $error ?>

                </div>

            <?php } ?>

            <form method="POST">

                <div class="mb-3">

                    <label>Password Lama</label>

                    <input
                        type="password"
                        name="password_lama"
                        class="form-control"
                        required>

                </div>

                <div class="mb-3">

                    <label>Password Baru</label>

                    <input
                        type="password"
                        name="password_baru"
                        class="form-control"
                        required>

                </div>

                <div class="mb-3">

                    <label>Konfirmasi Password Baru</label>

                    <input
                        type="password"
                        name="konfirmasi"
                        class="form-control"
                        required>

                </div>

                <button
                    type="submit"
                    name="ubah"
                    class="btn btn-warning">

                    Ubah Password

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