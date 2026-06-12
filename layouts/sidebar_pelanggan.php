<?php
$current = basename($_SERVER['PHP_SELF']);
?>

<div class="col-md-2 p-0">

    <div class="sidebar">

        <!-- LOGO -->

        <div class="sidebar-logo">

            <h2>SPLJ</h2>

            <small>Sistem Pemesanan Lapangan Jakabaring</small>

        </div>

        <!-- MENU -->

        <ul class="sidebar-menu">

            <li>
                <a href="dashboard.php"
                    class="<?= $current == 'dashboard.php' ? 'active' : '' ?>">
                    🏠 Dashboard
                </a>
            </li>

            <li>
                <a href="lapangan.php"
                    class="<?= $current == 'lapangan.php' ? 'active' : '' ?>">
                    ⚽ Booking Lapangan
                </a>
            </li>

            <li>
                <a href="riwayat_booking.php"
                    class="<?= $current == 'riwayat_booking.php' ? 'active' : '' ?>">
                    📋 Riwayat Booking
                </a>
            </li>

            <li class="nav-item">
                <a
                    href="../pelanggan/akun.php"
                     class="<?= $current == 'akun.php' ? 'active' : '' ?>">
                    👤 Akun Saya
                </a>
            </li>

            <li>
                <a href="../auth/logout.php">
                    🚪 Logout
                </a>
            </li>

        </ul>

    </div>

</div>