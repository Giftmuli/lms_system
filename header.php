<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPU LMS - Home</title>
    <link rel="stylesheet" href="bootstrap-5.3.7-dist/css/bootstrap.min.css">
    <style>
        .btn-primary { background-color: #800000; border-color: #800000; } /* SPU Color */
        .btn-primary:hover { background-color: #600000; border-color: #600000; }
        .navbar-dark .navbar-nav .nav-link { color: rgba(255,255,255,.8); }
        .navbar-dark .navbar-nav .nav-link:hover { color: #fff; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">SPU LMS</a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="login.php">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="register.php">Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-danger fw-bold" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>