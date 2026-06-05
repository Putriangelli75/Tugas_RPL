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

$data = $db->query("

SELECT

b.*,
u.nama,
l.nama_lapangan

FROM booking b

LEFT JOIN users u
ON b.id_user = u.id_user

LEFT JOIN lapangan l
ON b.id_lapangan = l.id_lapangan

ORDER BY b.id_booking DESC

");

include '../layouts/header.php';

?>

<div class="container-fluid">

    <div class="row">

        <?php include '../layouts/sidebar_admin.php'; ?>

        <div class="col-md-10">

            <div class="content p-4">

                <h2>Kelola Booking</h2>

                <hr>

                <div class="card shadow">

                    <div class="card-body">

                        <table
                            class="table table-bordered table-striped">

                            <thead class="table-dark">

                                <tr>

                                    <th>Kode</th>

                                    <th>Pelanggan</th>

                                    <th>Lapangan</th>

                                    <th>Tanggal</th>

                                    <th>Jam</th>

                                    <th>Total</th>

                                    <th>Bukti</th>

                                    <th>Status</th>

                                    <th width="220">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php while ($row = $data->fetch(PDO::FETCH_ASSOC)) { ?>

                                    <tr>

                                        <td>

                                            <?= $row['kode_booking'] ?>

                                        </td>

                                        <td>

                                            <?= htmlspecialchars(
                                                $row['nama']
                                            ) ?>

                                        </td>

                                        <td>

                                            <?= htmlspecialchars(
                                                $row['nama_lapangan']
                                            ) ?>

                                        </td>

                                        <td>

                                            <?= $row['tanggal_booking'] ?>

                                        </td>

                                        <td>

                                            <?= $row['jam_mulai'] ?>

                                        </td>

                                        <td>

                                            Rp
                                            <?= number_format(
                                                $row['total_bayar']
                                            ) ?>

                                        </td>

                                        <td>

                                            <?php

                                            if (
                                                !empty($row['bukti_pembayaran'])
                                            ) {

                                            ?>

                                                <a
                                                    href="../uploads/<?= $row['bukti_pembayaran'] ?>"
                                                    target="_blank">

                                                    <img
                                                        src="../uploads/<?= $row['bukti_pembayaran'] ?>"
                                                        width="80"
                                                        class="img-thumbnail">

                                                </a>

                                            <?php

                                            } else {

                                                echo "-";
                                            }

                                            ?>

                                        </td>

                                        <td>

                                            <?php

                                            $status =
                                                strtolower(
                                                    $row['status']
                                                );

                                            if (
                                                $status == 'pending'
                                            ) {

                                                echo "
<span class='badge bg-warning'>
Pending
</span>
";
                                            } elseif (
                                                $status == 'disetujui'
                                            ) {

                                                echo "
<span class='badge bg-success'>
Disetujui
</span>
";
                                            } elseif (
                                                $status == 'ditolak'
                                            ) {

                                                echo "
<span class='badge bg-danger'>
Ditolak
</span>
";
                                            } elseif (
                                                $status == 'selesai'
                                            ) {

                                                echo "
<span class='badge bg-primary'>
Selesai
</span>
";
                                            } else {

                                                echo "
<span class='badge bg-secondary'>
-
</span>
";
                                            }

                                            ?>

                                        </td>

                                        <td>

                                            <a
                                                href="update_status.php?id=<?= $row['id_booking'] ?>&status=disetujui"
                                                class="btn btn-success btn-sm mb-1">

                                                Approve

                                            </a>

                                            <a
                                                href="update_status.php?id=<?= $row['id_booking'] ?>&status=ditolak"
                                                class="btn btn-danger btn-sm mb-1">

                                                Tolak

                                            </a>

                                            <a
                                                href="update_status.php?id=<?= $row['id_booking'] ?>&status=selesai"
                                                class="btn btn-primary btn-sm">

                                                Selesai

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

    </div>

</div>

<?php include '../layouts/footer.php'; ?>