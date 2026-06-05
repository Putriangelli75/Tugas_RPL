<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1">

    <title>SPLJ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        /* SIDEBAR */

        .sidebar {
            min-height: 100vh;
            background: #198754;
        }

        .sidebar-logo {

            font-size: 28px;
            font-weight: bold;
            color: white;

            text-align: center;

            padding: 25px;
        }

        .sidebar a {

            color: white;

            display: block;

            text-decoration: none;

            padding: 14px 20px;

            transition: .3s;
        }

        .sidebar a:hover {

            background: #157347;

            padding-left: 30px;

        }

        /* CONTENT */

        .content {
            padding: 25px;
        }

        /* CARD */

        .card-stat {

            border: none;

            border-radius: 18px;

            transition: .3s;
        }

        .card-stat:hover {

            transform: translateY(-5px);

        }

        /* HERO */

        .hero-banner {

            height: 250px;

            border-radius: 20px;

            background:
                linear-gradient(rgba(0, 0, 0, .45),
                    rgba(0, 0, 0, .45)),
                url('../assets/img/banner.jpg');

            background-size: cover;
            background-position: center;

            display: flex;
            align-items: center;

            padding: 35px;

            color: white;
        }

        .table {
            background: white;
        }
    </style>

</head>

<body>