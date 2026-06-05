<?php

require '../config/koneksi.php';

if (isset($_POST['register'])) {

    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $no_hp = trim($_POST['no_hp']);

    $password = password_hash(
        $_POST['password'],
        PASSWORD_DEFAULT
    );

    $cek = $db->prepare("
        SELECT *
        FROM users
        WHERE email = ?
    ");

    $cek->execute([$email]);

    if ($cek->rowCount() > 0) {

        $error = "Email sudah digunakan";

    } else {

        $stmt = $db->prepare("
            INSERT INTO users
            (
                nama,
                email,
                no_hp,
                password,
                role
            )
            VALUES
            (
                ?,
                ?,
                ?,
                ?,
                ?
            )
        ");

        $stmt->execute([
            $nama,
            $email,
            $no_hp,
            $password,
            'pelanggan'
        ]);

        echo "
        <script>

            alert('Akun berhasil dibuat! Silakan login.');

            window.location='login.php';

        </script>
        ";

        exit;
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Register SPLJ</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-md-5">

                <div class="card shadow mt-5">

                    <div class="card-header text-center">

                        <h3>Register SPLJ</h3>

                    </div>

                    <div class="card-body">

                        <?php if (isset($error)) : ?>

                            <div class="alert alert-danger">

                                <?= $error ?>

                            </div>

                        <?php endif; ?>

                        <form method="POST">

                            <div class="mb-3">

                                <label class="form-label">
                                    Nama Lengkap
                                </label>

                                <input
                                    type="text"
                                    name="nama"
                                    class="form-control"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Nomor HP
                                </label>

                                <input
                                    type="text"
                                    name="no_hp"
                                    class="form-control">

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Password
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    required>

                            </div>

                            <button
                                type="submit"
                                name="register"
                                class="btn btn-success w-100">

                                Daftar

                            </button>

                        </form>

                        <hr>

                        <div class="text-center">

                            <a href="login.php">

                                Sudah punya akun? Login

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>