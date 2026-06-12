<?php

$db = new PDO(
    "sqlite:database/splj.db"
);

$db->exec("
ALTER TABLE booking
ADD COLUMN bukti_pembayaran TEXT
");

$db->exec("
ALTER TABLE booking
ADD COLUMN metode_pembayaran TEXT
");

echo "Berhasil";