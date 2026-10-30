<?php
require 'config.php'; $pageTitle='Contact Us'; $success=false; $error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $name=trim($_POST['name']??''); $phone=trim($_POST['phone']??''); $email=trim($_POST['email']??''); $message=trim($_POST['message']??'');
  if (!$name || !$phone || !$message) $error='Please complete the required fields.';
  else { $stmt=$pdo->prepare('INSERT INTO enquiries (name,phone,email,message) VALUES (?,?,?,?)'); $stmt->execute([$name,$phone,$email ?: null,$message]); $success=true; }
}
require 'header.php'; ?>
<section class="page-hero"><div class="container"><span class="eyebrow">WE'RE HERE TO HELP</span><h1>Contact <span>TMT.</span></h1><p>Have a question about a shipment or service area? Get in touch with our team.</p></div></section>
<section class="section"><div class="container contact-grid"><div class="contact-details"><div class="contact-card"><span class="contact-icon">☎</span><div><small>Call us</small><h3><a href="tel:9940095393">99400 95393</a></h3><p>Phone support for booking and shipment enquiries.</p></div></div><div class="contact-card"><span class="contact-icon">⌖</span><div><small>Office address</small><h3>Coimbatore</h3><p>7C, Indira Gandhi Nagar, New Siddhapudur, Coimbatore. Please verify the postal code before publishing.</p></div></div><div class="contact-card"><span class="contact-icon">⌁</span><div><small>Service coverage</small><h3>Southern Tamil Nadu</h3><p>Contact us to confirm coverage for your pickup and destination.</p></div></div></div>
<div class="form-card"><?php if($success): ?><div class="alert success">Thank you. Your enquiry has been submitted.</div><?php else: ?><?php if($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><h2>Send an enquiry</h2><form method="post" class="stack-form"><label>Your name *<input name="name" required></label><label>Phone number *<input name="phone" type="tel" required></label><label>Email (optional)<input name="email" type="email"></label><label>How can we help? *<textarea name="message" rows="4" required></textarea></label><button class="btn btn-primary">Send enquiry →</button></form><?php endif; ?></div></div></section>
<?php require 'footer.php'; ?>
