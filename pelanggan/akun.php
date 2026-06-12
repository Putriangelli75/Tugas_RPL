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

$stmt = $db->prepare("
SELECT *
FROM users
WHERE id_user=?
");

$stmt->execute([
    $_SESSION['id_user']
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

include '../layouts/header.php';

?>

<div class="container-fluid">

    <div class="row">

        <?php include '../layouts/sidebar_pelanggan.php'; ?>

        <div class="col-md-10">

            <div class="content p-4">

                <div class="card shadow">

                    <div class="card-header bg-success text-white">

                        Akun Saya

                    </div>

                    <div class="card-body">

                        <div class="d-flex align-items-center mb-4">

                            <form
                                action="upload_foto.php"
                                method="POST"
                                enctype="multipart/form-data">

                                <label
                                    for="foto"
                                    style="cursor:pointer;">

                                    <?php if (!empty($user['foto'])) { ?>

                                        <img
                                            src="../uploads/<?= $user['foto'] ?>"
                                            style="
                    width:90px;
                    height:90px;
                    border-radius:50%;
                    object-fit:cover;
                    border:3px solid #198754;
                ">

                                    <?php } else { ?>

                                        <div
                                            style="
                    width:90px;
                    height:90px;
                    border-radius:50%;
                    background:#198754;
                    color:white;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    font-size:40px;
                ">

                                            👤

                                        </div>

                                    <?php } ?>

                                </label>

                                <input
                                    type="file"
                                    id="foto"
                                    name="foto"
                                    accept=".jpg,.jpeg,.png"
                                    style="display:none;"
                                    onchange="this.form.submit();">

                            </form>

                            <div>

                                <h4 class="mb-1">
                                    <?= $user['nama'] ?>
                                </h4>

                                <small class="text-muted">
                                    <?= $user['email'] ?>
                                </small>

                            </div>

                        </div>

                        <table class="table">

                            <tr>
                                <td width="200">No HP</td>
                                <td><?= $user['no_hp'] ?></td>
                            </tr>

                            <tr>
                                <td>Membership</td>
                                <td><?= ucfirst($user['membership']) ?></td>
                            </tr>

                            <tr>
                                <td>Reward Point</td>
                                <td><?= $user['poin'] ?></td>
                            </tr>

                        </table>

                        <a
                            href="edit_akun.php"
                            class="btn btn-success">

                            Edit Profil

                        </a>

                        <a
                            href="ubah_password.php"
                            class="btn btn-warning">

                            Ubah Password

                        </a>

                    </div>

                    <?php include '../layouts/footer.php'; ?>