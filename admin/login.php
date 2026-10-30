<?php
require '../config.php';
if (!empty($_SESSION['admin_id'])) { header('Location: dashboard.php'); exit; }
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $stmt=$pdo->prepare('SELECT id,username,password_hash FROM admins WHERE username=? LIMIT 1'); $stmt->execute([trim($_POST['username']??'')]); $admin=$stmt->fetch();
  if ($admin && password_verify($_POST['password']??'', $admin['password_hash'])) { session_regenerate_id(true); $_SESSION['admin_id']=$admin['id']; $_SESSION['admin_username']=$admin['username']; header('Location: dashboard.php'); exit; }
  $error='Invalid username or password.';
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Login | TMT</title><link rel="stylesheet" href="../assets/style.css"></head><body><main class="section"><div class="container narrow"><div class="form-card"><img class="login-logo" src="../assets/tmt-logo.png" alt="TMT logo"><span class="eyebrow">STAFF ACCESS</span><h1>Admin login</h1><?php if($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><form method="post" class="stack-form"><label>Username<input name="username" required autocomplete="username"></label><label>Password<input type="password" name="password" required autocomplete="current-password"></label><button class="btn btn-primary">Sign in →</button></form><p><a href="../index.php">← Return to website</a></p></div></div></main></body></html>
