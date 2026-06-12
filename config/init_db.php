<?php

try {

    $db = new PDO(
        "sqlite:" . __DIR__ . "/../database/splj.db"
    );

    $db->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    /* =========================
       TABEL USERS
    ========================= */

    $db->exec("
    CREATE TABLE IF NOT EXISTS users(

        id_user INTEGER PRIMARY KEY AUTOINCREMENT,

        nama TEXT NOT NULL,

        email TEXT NOT NULL UNIQUE,

        no_hp TEXT,

        password TEXT NOT NULL,

        role TEXT DEFAULT 'pelanggan',

        membership TEXT DEFAULT 'regular',

        poin INTEGER DEFAULT 0,

        created_at DATETIME
        DEFAULT CURRENT_TIMESTAMP

    )
    ");

    /* =========================
       TABEL LAPANGAN
    ========================= */

    $db->exec("
    CREATE TABLE IF NOT EXISTS lapangan(

        id_lapangan INTEGER PRIMARY KEY AUTOINCREMENT,

        nama_lapangan TEXT NOT NULL,

        jenis_olahraga TEXT NOT NULL,

        harga_per_jam REAL NOT NULL,

        gambar TEXT,

        status TEXT DEFAULT 'aktif'

    )
    ");

    /* =========================
       TABEL BOOKING
    ========================= */

    $db->exec("
    CREATE TABLE IF NOT EXISTS booking(

        id_booking INTEGER PRIMARY KEY AUTOINCREMENT,

        kode_booking TEXT,

        id_user INTEGER,

        id_lapangan INTEGER,

        tanggal_booking DATE,

        jam_mulai TIME,

        durasi INTEGER,

        total_bayar REAL,

        status TEXT DEFAULT 'pending',

        bukti_pembayaran TEXT,

        metode_pembayaran TEXT,

        created_at DATETIME
        DEFAULT CURRENT_TIMESTAMP

    )
    ");

    /* =========================
       SEED DATA LAPANGAN
    ========================= */

    $cekLapangan = $db->query(
        "SELECT COUNT(*) FROM lapangan"
    )->fetchColumn();

    if ($cekLapangan == 0) {

        $db->exec("
        INSERT INTO lapangan
        (
            nama_lapangan,
            jenis_olahraga,
            harga_per_jam,
            status
        )
        VALUES
        ('Futsal A','Futsal',150000,'aktif'),
        ('Futsal B','Futsal',180000,'aktif'),
        ('Badminton 1','Badminton',50000,'aktif'),
        ('Badminton 2','Badminton',50000,'aktif'),
        ('Basket Indoor','Basket',250000,'aktif')
        ");
    }

    /* =========================
       ADMIN DEFAULT
    ========================= */

    $cekAdmin = $db->query(
        "SELECT COUNT(*) FROM users
        WHERE role='admin'"
    )->fetchColumn();

    if ($cekAdmin == 0) {

        $password = password_hash(
            "admin123",
            PASSWORD_DEFAULT
        );

        $stmt = $db->prepare("
        INSERT INTO users
        (
            nama,
            email,
            password,
            role
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?
        )
        ");

        $stmt->execute([
            "Administrator",
            "admin@splj.com",
            $password,
            "admin"
        ]);
    }

    echo "
    <h2>Database berhasil dibuat</h2>

    <hr>

    <h4>Login Admin Default</h4>

    Email : admin@splj.com <br>
    Password : admin123
    ";

} catch (PDOException $e) {

    die(
        "Error Database : "
        . $e->getMessage()
    );

}