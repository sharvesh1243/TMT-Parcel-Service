<?php
require 'config.php';
$pageTitle = 'My Bookings';
$matches = [];
$error = '';
$searched = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' || (isset($_GET['sender_phone']) && isset($_GET['access_pin']))) {
    $phoneInput = trim($_REQUEST['sender_phone'] ?? '');
    $pin = trim($_REQUEST['access_pin'] ?? '');
    $searched = true;
    // phoneInput read above
    // pin read above
    $phone = preg_replace('/\D+/', '', $phoneInput);
    if (strlen($phone) < 10 || strlen($phone) > 15 || !preg_match('/^[0-9]{6}$/', $pin)) {
        $error = 'Enter the sender phone number used for booking and your 6-digit My Bookings PIN.';
    } else {
        // Compare normalized phone numbers in PHP to allow spaces, +91, and punctuation.
        // Limit rows to keep lookup bounded; never return full addresses or phone numbers.
        $stmt = $pdo->query("SELECT id, booking_ref, tracking_id, sender_phone, access_pin_hash, pickup_city, delivery_city, parcel_type, status, created_at, updated_at FROM bookings ORDER BY created_at DESC LIMIT 500");
        foreach ($stmt->fetchAll() as $row) {
            $storedPhone = preg_replace('/\D+/', '', $row['sender_phone'] ?? '');
            $phoneMatches = ($storedPhone === $phone) || (strlen($storedPhone) >= 10 && strlen($phone) >= 10 && substr($storedPhone, -10) === substr($phone, -10));
            if ($phoneMatches && !empty($row['access_pin_hash']) && password_verify($pin, $row['access_pin_hash'])) {
                $matches[] = $row;
            }
        }
        if (!$matches) {
            $error = 'No bookings matched those details. Check the sender phone and PIN. Older bookings made before My Bookings was added may not have a PIN yet; ask the TMT administrator to retrieve those.';
        }
    }
}
require 'header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">CUSTOMER PORTAL</span><h1>My <span>Bookings.</span></h1><p>Retrieve your parcel requests using the sender phone number and the 6-digit PIN you created when booking.</p></div></section>
<section class="section"><div class="container tracking-wrap">
  <form method="post" class="track-form">
    <label for="sender_phone">Sender phone number</label>
    <input id="sender_phone" name="sender_phone" type="tel" required autocomplete="tel" placeholder="Phone used when booking" value="<?= e($_POST['sender_phone'] ?? '') ?>">
    <label for="access_pin" style="margin-top:16px">My Bookings PIN</label>
    <input id="access_pin" name="access_pin" type="password" inputmode="numeric" pattern="[0-9]{6}" minlength="6" maxlength="6" required autocomplete="current-password" placeholder="6-digit PIN">
    <button class="btn btn-primary" type="submit" style="margin-top:16px">Find my bookings →</button>
    <small>Your PIN is stored as a password hash. This page does not reveal full addresses or phone numbers.</small>
  </form>
  <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
  <?php if ($matches): ?>
    <div class="table-card"><div class="table-heading"><h2>Your bookings</h2><span><?= count($matches) ?> found</span></div><div class="table-scroll"><table><thead><tr><th>Booking reference</th><th>Tracking ID</th><th>Route</th><th>Goods</th><th>Status</th><th>Last updated</th></tr></thead><tbody>
    <?php foreach ($matches as $b): ?><tr><td><strong><?= e($b['booking_ref']) ?></strong><small>Created <?= e($b['created_at']) ?></small></td><td><?= e($b['tracking_id'] ?: 'Not assigned yet') ?></td><td><?= e($b['pickup_city']) ?> → <?= e($b['delivery_city']) ?></td><td><?= e($b['parcel_type']) ?></td><td><span class="status-pill"><?= e($b['status']) ?></span></td><td><?= e($b['updated_at']) ?></td></tr><?php endforeach; ?>
    </tbody></table></div></div>
    <p class="form-note">For tracking, use the tracking ID shown above. If one has not been assigned, the TMT team has not yet confirmed the booking.</p>
  <?php endif; ?>
</div></section>
<?php require 'footer.php'; ?>
