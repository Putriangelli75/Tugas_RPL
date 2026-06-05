<?php

require 'koneksi.php';

$db->exec("DELETE FROM lapangan");

echo "Data lapangan dihapus";