<?php
// About
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-brand img {
            height: 80px;
            width: 80px;
        }
        .navbar-nav .nav-link {
            font-weight: 500;
        }
        .team-member {
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            background-color: #fff;
            transition: transform 0.3s ease;
        }

        .team-member:hover {
            transform: translateY(-5px);
        }

        .team-member img {
            border-radius: 50%;
            width: 100%;
            max-width: 150px;
            height: auto;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light px-4 py-3 shadow-sm">
        <a class="navbar-brand" href="#">
            <img src="../img/logo.png" alt="FinQuest Logo">
        </a>
        <h1 class="ms-3 fs-3">About Us</h1>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../user/dashboard.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="../finquest/about.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../finquest/contact.php">Contact Us</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<main class="container text-center mt-5">
    <p class="lead">Welcome to Ball Game's Equipments, your go-to platform for high-end sports materials.</p>

    <h2 class="mb-4">Meet Our Team</h2>

    <div class="row justify-content-center">
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="team-member">
                <img src="../img/rex.jpg" alt="John Rex Gatchalian">
                <h5>amado</h5>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="team-member">
                <img src="../img/lance.jpg" alt="Lance Armstrong Navarro">
                <h5>antonio</h5>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="team-member">
                <img src="../img/spled.jpg" alt="Spledelyn Cristine Recarze">
                <h5>lance</h5>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="team-member">
                <img src="../img/.jpg" alt="">
                <h5>Chars</h5>
            </div>
              <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="team-member">
                <img src="../img/.jpg" alt="">
                <h5>Dave</h5>
            </div>
        </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
