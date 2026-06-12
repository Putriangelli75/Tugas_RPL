<?php
$current = basename($_SERVER['PHP_SELF']);
?>

<div class="col-md-2 p-0">

    <div class="sidebar">

        <!-- LOGO -->

        <div class="sidebar-logo">

            <h2>SPLJ</h2>

            <small>
                Administrator Panel
            </small>

        </div>

        <!-- MENU -->

        <ul class="sidebar-menu">

            <li>
                <a href="dashboard.php"
                   class="<?= $current == 'dashboard.php' ? 'active' : '' ?>">
                    📊 Dashboard
                </a>
            </li>

            <li>
                <a href="lapangan.php"
                   class="<?= in_array($current,[
                        'lapangan.php',
                        'tambah_lapangan.php',
                        'edit_lapangan.php'
                    ]) ? 'active' : '' ?>">
                    ⚽ Kelola Lapangan
                </a>
            </li>

            <li>
                <a href="booking.php"
                   class="<?= $current == 'booking.php' ? 'active' : '' ?>">
                    📅 Kelola Booking
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