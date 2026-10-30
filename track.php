<?php
require 'config.php';
$pageTitle = 'Track Parcel';
$booking = null; $searched = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['tracking_id'])) {
  $id = trim($_REQUEST['tracking_id'] ?? '');
  $searched = true; $id = strtoupper(trim($_POST['tracking_id'] ?? ''));
  if ($id !== '') { $stmt = $pdo->prepare('SELECT booking_ref,tracking_id,sender_name,receiver_name,pickup_city,delivery_city,status,created_at,updated_at FROM bookings WHERE tracking_id = ? OR booking_ref = ? LIMIT 1'); $stmt->execute([$id,$id]); $booking = $stmt->fetch() ?: null; }
}
require 'header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">SHIPMENT STATUS</span><h1>Track your <span>parcel.</span></h1><p>Enter the tracking ID provided by TMT after your shipment is confirmed.</p></div></section>
<section class="section"><div class="container tracking-wrap"><form method="post" class="track-form"><label for="tracking_id">Tracking ID or booking reference</label><div class="track-input"><input id="tracking_id" name="tracking_id" placeholder="e.g. TMT260101ABC123" required value="<?= e($_POST['tracking_id'] ?? '') ?>"><button class="btn btn-primary">Track parcel →</button></div><small>Booking references show the request record. Tracking updates begin after a tracking ID is assigned.</small></form>
<?php if ($searched && !$booking): ?><div class="alert error">No matching parcel was found. Check the ID or call TMT at <a href="tel:9940095393">99400 95393</a>.</div><?php elseif ($booking): ?>
<div class="tracking-result"><div class="result-top"><div><span class="eyebrow">SHIPMENT FOUND</span><h2><?= e($booking['tracking_id'] ?: $booking['booking_ref']) ?></h2></div><span class="status-pill"><?= e($booking['status']) ?></span></div><div class="result-details"><div><small>Pickup</small><strong><?= e($booking['pickup_city']) ?></strong></div><div><small>Destination</small><strong><?= e($booking['delivery_city']) ?></strong></div><div><small>Last updated</small><strong><?= e($booking['updated_at']) ?></strong></div></div><p class="form-note">Status is based on the latest update recorded by TMT. For assistance, call 99400 95393.</p></div>
<?php endif; ?></div></section>
<?php require 'footer.php'; ?>
