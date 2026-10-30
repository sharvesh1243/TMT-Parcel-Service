<?php
require 'config.php';
$pageTitle = 'Book a Parcel';
$success = ''; $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fields = ['sender_name','sender_phone','receiver_name','receiver_phone','pickup_address','pickup_city','delivery_address','delivery_city','parcel_type'];
  foreach ($fields as $f) { if (trim($_POST[$f] ?? '') === '') { $error = 'Please complete all required fields.'; break; } }
  if (!$error && strtolower(trim($_POST['parcel_type'])) === 'food') $error = 'Food items are not accepted.';
  $accessPin = trim($_POST['access_pin'] ?? '');
  if (!$error && !preg_match('/^[0-9]{6}$/', $accessPin)) $error = 'Choose a 6-digit My Bookings PIN.';
  if (!$error && $accessPin !== trim($_POST['access_pin_confirm'] ?? '')) $error = 'The My Bookings PINs do not match.';
  if (!$error) {
    $ref = 'TMT' . date('ymd') . strtoupper(bin2hex(random_bytes(3)));
    $stmt = $pdo->prepare('INSERT INTO bookings (booking_ref,sender_name,sender_phone,access_pin_hash,receiver_name,receiver_phone,pickup_address,pickup_city,delivery_address,delivery_city,parcel_type,weight_kg,notes) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');
    $weight = ($_POST['weight_kg'] ?? '') !== '' ? (float)$_POST['weight_kg'] : null;
    $stmt->execute([$ref,trim($_POST['sender_name']),trim($_POST['sender_phone']),password_hash($accessPin, PASSWORD_DEFAULT),trim($_POST['receiver_name']),trim($_POST['receiver_phone']),trim($_POST['pickup_address']),trim($_POST['pickup_city']),trim($_POST['delivery_address']),trim($_POST['delivery_city']),trim($_POST['parcel_type']),$weight,trim($_POST['notes'] ?? '')]);
    $success = $ref;
  }
}
if (empty($success) && isset($_GET['preview_ref'])) { $success = $_GET['preview_ref']; }
require 'header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">PARCEL REQUEST</span><h1>Book a <span>parcel.</span></h1><p>Share your shipment details. This form submits a request; the TMT team will contact you to confirm it.</p></div></section>
<section class="section"><div class="container form-layout"><div class="form-main">
<?php if ($success): ?><div class="alert success"><h2>Request received!</h2><p>Your booking reference is:</p><strong class="reference"><?= e($success) ?></strong><p>Save this reference. Your 6-digit My Bookings PIN lets you retrieve your bookings later using your sender phone number.</p><a class="btn btn-primary" href="my-bookings.php">View My Bookings →</a> <a class="btn btn-outline" href="track.php">Track a parcel</a></div>
<?php else: ?>
<?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="form-card">
<h2>Sender details</h2><div class="form-grid">
<label>Sender full name *<input name="sender_name" required value="<?= e($_POST['sender_name'] ?? '') ?>"></label>
<label>Sender phone *<input name="sender_phone" type="tel" required value="<?= e($_POST['sender_phone'] ?? '') ?>"></label>
<label>Choose a 6-digit My Bookings PIN *<input name="access_pin" type="password" inputmode="numeric" pattern="[0-9]{6}" minlength="6" maxlength="6" autocomplete="new-password" required><small>Use this PIN with your sender phone to retrieve bookings. Do not use your ATM PIN.</small></label>
<label>Confirm My Bookings PIN *<input name="access_pin_confirm" type="password" inputmode="numeric" pattern="[0-9]{6}" minlength="6" maxlength="6" autocomplete="new-password" required></label>
<label class="full">Pickup address *<textarea name="pickup_address" required rows="2"><?= e($_POST['pickup_address'] ?? '') ?></textarea></label>
<label>Pickup city / town *<input name="pickup_city" required value="<?= e($_POST['pickup_city'] ?? '') ?>"></label>
</div><h2>Receiver details</h2><div class="form-grid">
<label>Receiver full name *<input name="receiver_name" required value="<?= e($_POST['receiver_name'] ?? '') ?>"></label>
<label>Receiver phone *<input name="receiver_phone" type="tel" required value="<?= e($_POST['receiver_phone'] ?? '') ?>"></label>
<label class="full">Delivery address *<textarea name="delivery_address" required rows="2"><?= e($_POST['delivery_address'] ?? '') ?></textarea></label>
<label>Destination city / town *<input name="delivery_city" required value="<?= e($_POST['delivery_city'] ?? '') ?>"></label>
</div><h2>Parcel details</h2><div class="form-grid">
<label>Goods type *<select name="parcel_type" required><option value="">Select goods type</option><?php foreach (['Clothing / textiles','Documents','Electronics','Household goods','Industrial goods','Spare parts','Other non-food goods'] as $type): ?><option <?= ($_POST['parcel_type'] ?? '') === $type ? 'selected' : '' ?>><?= e($type) ?></option><?php endforeach; ?></select></label>
<label>Approx. weight (kg)<input name="weight_kg" type="number" min="0.1" step="0.1" value="<?= e($_POST['weight_kg'] ?? '') ?>"></label>
<label class="full">Additional notes<textarea name="notes" rows="3" placeholder="Optional handling or parcel notes"><?= e($_POST['notes'] ?? '') ?></textarea></label>
</div><p class="form-note">Food items are not accepted. Please do not submit prohibited or hazardous goods.</p><button class="btn btn-primary" type="submit">Submit booking request →</button>
</form><?php endif; ?></div>
<aside class="side-note"><div class="side-icon">↗</div><h3>What happens next?</h3><ol><li>Submit your parcel request.</li><li>TMT reviews the details and contacts you.</li><li>Once confirmed, you receive a tracking ID.</li><li>Use the ID to check shipment status.</li></ol><div class="contact-mini"><strong>Need help?</strong><a href="tel:9940095393">99400 95393</a></div></aside></div></section>
<?php require 'footer.php'; ?>
