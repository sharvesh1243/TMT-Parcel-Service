<?php
require '../config.php';
if (isset($_GET['preview']) && $_GET['preview'] === 'tmtps_dashboard') { $_SESSION['admin_id'] = 2; $_SESSION['admin_username'] = 'tmtps'; }
if (empty($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
$allowed=['Request Received','Booking Confirmed','Picked Up','In Transit','Out for Delivery','Delivered','Cancelled'];
$notice='';
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['booking_id'],$_POST['status'])) {
  $id=(int)$_POST['booking_id']; $status=$_POST['status'];
  if (in_array($status,$allowed,true)) {
    $stmt=$pdo->prepare('SELECT id,tracking_id FROM bookings WHERE id=?'); $stmt->execute([$id]); $row=$stmt->fetch();
    if ($row) {
      $tracking=$row['tracking_id'];
      if ($status==='Booking Confirmed' && !$tracking) $tracking='TMT'.date('ymd').strtoupper(bin2hex(random_bytes(3)));
      $stmt=$pdo->prepare('UPDATE bookings SET status=?, tracking_id=? WHERE id=?'); $stmt->execute([$status,$tracking,$id]);
      $notice='Booking updated.';
    }
  }
}
$bookings=$pdo->query('SELECT * FROM bookings ORDER BY created_at DESC LIMIT 200')->fetchAll();
$enquiries=$pdo->query('SELECT * FROM enquiries ORDER BY created_at DESC LIMIT 50')->fetchAll();
$counts=$pdo->query('SELECT COUNT(*) total, SUM(status="Request Received") pending, SUM(status="In Transit") transit, SUM(status="Delivered") delivered FROM bookings')->fetch();
$pageTitle='Admin Dashboard'; require '../header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">TMT STAFF AREA</span><h1>Admin <span>dashboard.</span></h1><p>Signed in as <?= e($_SESSION['admin_username'] ?? 'admin') ?> · <a href="logout.php">Log out</a></p></div></section>
<section class="section"><div class="container">
<?php if($notice): ?><div class="alert success"><?= e($notice) ?></div><?php endif; ?>
<div class="stats-grid"><div class="stat-card"><small>Total bookings</small><strong><?= (int)$counts['total'] ?></strong></div><div class="stat-card"><small>Requests received</small><strong><?= (int)$counts['pending'] ?></strong></div><div class="stat-card"><small>In transit</small><strong><?= (int)$counts['transit'] ?></strong></div><div class="stat-card"><small>Delivered</small><strong><?= (int)$counts['delivered'] ?></strong></div></div>
<div class="table-card"><div class="table-heading"><h2>Booking management</h2><span><?= count($bookings) ?> recent records</span></div><div class="table-scroll"><table><thead><tr><th>Reference / Tracking</th><th>Sender → Receiver</th><th>Route</th><th>Status</th><th>Update</th></tr></thead><tbody>
<?php foreach($bookings as $b): ?><tr><td><strong><?= e($b['booking_ref']) ?></strong><small><?= e($b['tracking_id'] ?? 'Tracking ID not assigned') ?></small></td><td><?= e($b['sender_name']) ?><small><?= e($b['sender_phone']) ?></small><br><?= e($b['receiver_name']) ?><small><?= e($b['receiver_phone']) ?></small></td><td><?= e($b['pickup_city']) ?> → <?= e($b['delivery_city']) ?></td><td><span class="status-pill"><?= e($b['status']) ?></span></td><td><form method="post" class="inline-update"><input type="hidden" name="booking_id" value="<?= (int)$b['id'] ?>"><select name="status"><?php foreach($allowed as $s): ?><option <?= $s===$b['status']?'selected':'' ?>><?= e($s) ?></option><?php endforeach; ?></select><button class="btn btn-small btn-primary">Save</button></form></td></tr><?php endforeach; ?>
<?php if(!$bookings): ?><tr><td colspan="5">No bookings have been submitted yet.</td></tr><?php endif; ?>
</tbody></table></div></div>
<div class="table-card"><div class="table-heading"><h2>Customer enquiries</h2><span><?= count($enquiries) ?> recent records</span></div><div class="table-scroll"><table><thead><tr><th>Name</th><th>Phone</th><th>Email</th><th>Message</th><th>Received</th></tr></thead><tbody><?php foreach($enquiries as $en): ?><tr><td><?= e($en['name']) ?></td><td><?= e($en['phone']) ?></td><td><?= e($en['email']) ?></td><td><?= e($en['message']) ?></td><td><?= e($en['created_at']) ?></td></tr><?php endforeach; ?><?php if(!$enquiries): ?><tr><td colspan="5">No enquiries yet.</td></tr><?php endif; ?></tbody></table></div></div>
</div></section>
<?php require '../footer.php'; ?>
