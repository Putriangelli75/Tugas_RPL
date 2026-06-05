<?php

session_start();

require '../config/koneksi.php';

if (isset($_POST['login'])) {

    $email = $_POST['email'];

    $password = $_POST['password'];

    $stmt = $db->prepare("
    SELECT *
    FROM users
    WHERE email=?
    ");

    $stmt->execute([
        $email
    ]);

    $user =
        $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        if (
            password_verify(
                $password,
                $user['password']
            )
        ) {

            $_SESSION['id_user']
                = $user['id_user'];

            $_SESSION['nama']
                = $user['nama'];

            $_SESSION['role']
                = $user['role'];

            if (
                $user['role']
                == 'admin'
            ) {

                header(
                    "Location: ../admin/dashboard.php"
                );
            } else {

                header(
                    "Location: ../pelanggan/dashboard.php"
                );
            }

            exit;
        } else {

            $error =
                "Password salah";
        }
    } else {

        $error =
            "Email tidak ditemukan";
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Login SPLJ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body>

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-md-4">

                <div class="card shadow mt-5">

                    <div class="card-header">

                        <h3>Login SPLJ</h3>

                    </div>

                    <div class="card-body">

                        <?php

                        if (isset($error)) {

                            echo "
<div class='alert alert-danger'>
$error
</div>
";
                        }

                        ?>

                        <form method="POST">

                            <div class="mb-3">

                                <label>Email</label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label>Password</label>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    required>

                            </div>

                            <button
                                name="login"
                                class="btn btn-success w-100">

                                Login

                            </button>

                        </form>

                        <br>

                        <a href="register.php">

                            Belum punya akun?

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>