<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>SPLJ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }

        /* HERO BANNER */

        .hero-banner {

            background:
                linear-gradient(rgba(0, 0, 0, .45),
                    rgba(0, 0, 0, .45)),
                url('../assets/img/banner.jpg');

            background-size: cover;
            background-position: center;

            min-height: 220px;

            border-radius: 20px;

            display: flex;

            align-items: center;

            padding: 40px;

            color: white;

            box-shadow:
                0 5px 20px rgba(0, 0, 0, .15);
        }

        .hero-banner h1,
        .hero-banner h2 {

            font-weight: 700;
            margin-bottom: 10px;

        }

        /* SIDEBAR */

        .sidebar {

            min-height: 100vh;

            background: #198754;

            color: white;

            padding-top: 30px;

            position: sticky;

            top: 0;
        }

        .sidebar h2 {

            text-align: center;

            margin-bottom: 30px;

            font-weight: bold;

        }

        .sidebar a {

            display: block;

            color: white;

            text-decoration: none;

            padding: 12px 20px;

            border-radius: 10px;

            margin: 5px 10px;

            transition: .3s;
        }

        .sidebar a:hover {

            background:
                rgba(255, 255, 255, .15);

        }

        /* CONTENT */

        .content {

            padding: 25px;

        }

        /* CARD */

        .card {

            border: none;

            border-radius: 18px;

            overflow: hidden;

        }

        .card:hover {

            transform:
                translateY(-3px);

            transition: .3s;
        }

        .card-stat {

            background: white;
        }

        .card-stat h1 {

            font-size: 40px;

            font-weight: 700;

            color: #198754;
        }

        .card-stat h2 {

            font-weight: 700;
        }

        /* TABLE */

        .table {

            vertical-align: middle;
        }

        /* BADGE */

        .badge {

            padding: 8px 12px;

            font-size: 12px;
        }

        /* BUTTON */

        .btn {

            border-radius: 10px;
        }

        /* FORM */

        .form-control,
        .form-select {

            border-radius: 10px;
        }

        /* RESPONSIVE */

        @media(max-width:768px) {

            .sidebar {

                min-height: auto;
            }

            .hero-banner {

                min-height: 180px;

                text-align: center;

                justify-content: center;
            }

        }

        /* SIDEBAR */

        .sidebar {
            background: #198754;
            min-height: 100vh;
            color: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, .1);
        }

        .sidebar-logo {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, .2);
            text-align: center;
        }

        .sidebar-logo h2 {
            margin: 0;
            font-weight: bold;
        }

        .sidebar-logo small {
            color: #d9f7e4;
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            transition: .3s;
            border-left: 4px solid transparent;
        }

        .sidebar-menu a:hover {
            background: rgba(255, 255, 255, .12);
        }

        .sidebar-menu a.active {
            background: white;
            color: #198754;
            font-weight: 600;
            border-left: 4px solid #0d6efd;
            border-radius: 0 30px 30px 0;
        }



        .booking-card {
            border-radius: 20px;
            overflow: hidden;
        }

        .booking-card .card-body {
            min-height: 300px;
        }

        .booking-card img {
            height: 220px !important;
            width: 100%;
            object-fit: cover;
            border-radius: 15px;
        }
    </style>

</head>

<body>