<?php

session_start();

require '../config/koneksi.php';

include '../layouts/header.php';

?>

<div class="container-fluid">

    <div class="row">

        <?php
        include '../layouts/sidebar_admin.php';
        ?>

        <div class="col-md-10">

            <div class="content">

                <div class="d-flex justify-content-between">

                    <h2>Kelola Lapangan</h2>

                    <a
                        href="tambah_lapangan.php"
                        class="btn btn-success">

                        Tambah Lapangan

                    </a>

                </div>

                <hr>

                <table
                    class="table table-bordered table-striped">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Nama Lapangan</th>
                            <th>Jenis</th>
                            <th>Harga/Jam</th>
                            <th>Status</th>
                            <th>Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        $data = $db->query(
                            "SELECT * FROM lapangan"
                        );

                        while (
                            $row =
                            $data->fetch(PDO::FETCH_ASSOC)
                        ) {

                        ?>

                            <tr>

                                <td>
                                    <?= $row['id_lapangan'] ?>
                                </td>

                                <td>
                                    <?= $row['nama_lapangan'] ?>
                                </td>

                                <td>
                                    <?= $row['jenis_olahraga'] ?>
                                </td>

                                <td>

                                    <?php
                                    $harga = is_numeric($row['harga_per_jam'])
                                        ? number_format($row['harga_per_jam'])
                                        : 0;
                                    ?>

                                    Rp <?= $harga ?>

                                </td>

                                <td>

                                    <?= $row['status'] ?>

                                </td>

                                <td>

                                    <a
                                        href="edit_lapangan.php?id=<?= $row['id_lapangan'] ?>"
                                        class="btn btn-warning btn-sm">

                                        Edit

                                    </a>

                                    <a
                                        href="hapus_lapangan.php?id=<?= $row['id_lapangan'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus?')">

                                        Hapus

                                    </a>

                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?php
include '../layouts/footer.php';
?>