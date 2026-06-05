<?php

require 'config/koneksi.php';

$total = $db->query(
"SELECT COUNT(*) FROM lapangan"
)->fetchColumn();

echo "Total lapangan : " . $total;