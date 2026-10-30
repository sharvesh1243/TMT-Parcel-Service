<?php
if (!isset($pageTitle)) $pageTitle = 'TMT Parcel Service';
$isAdminPage = str_contains($_SERVER['SCRIPT_NAME'] ?? '', '/admin/');
$rootPath = $isAdminPage ? '../' : '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?> | TMT Parcel Service</title>
  <meta name="description" content="Parcel booking and shipment tracking with TMT Parcel Service in Southern Tamil Nadu.">
  <link rel="stylesheet" href="<?= $rootPath ?>assets/style.css">
</head>
<body>
<header class="site-header">
  <div class="container nav-wrap">
    <a class="brand" href="<?= $rootPath ?>index.php"><img src="<?= $rootPath ?>assets/tmt-logo.png" alt="TMT Parcel Service logo"></a>
    <button class="menu-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false">☰</button>
    <nav class="nav-links">
      <a href="<?= $rootPath ?>index.php">Home</a>
      <a href="<?= $rootPath ?>index.php#services">Services</a>
      <a href="<?= $rootPath ?>book.php">Book a Parcel</a>
      <a href="<?= $rootPath ?>track.php">Track Parcel</a>
      <a href="<?= $rootPath ?>my-bookings.php">My Bookings</a>
      <a href="<?= $rootPath ?>contact.php">Contact</a>
      <a class="nav-cta" href="<?= $rootPath ?>book.php">Book Now</a>
    </nav>
  </div>
</header>
<main>
