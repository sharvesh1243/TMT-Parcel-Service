</main>
<?php $rootPath = str_contains($_SERVER['SCRIPT_NAME'] ?? '', '/admin/') ? '../' : ''; ?>
<footer class="site-footer">
  <div class="container footer-grid">
    <div><strong>TMT Parcel Service</strong><p>Excellence in Motion.</p><p>Goods transportation across Southern Tamil Nadu.</p></div>
    <div><strong>Contact</strong><p><a href="tel:9940095393">99400 95393</a></p><p>7C, Indira Gandhi Nagar, New Siddhapudur, Coimbatore</p></div>
    <div><strong>Quick links</strong><p><a href="<?= $rootPath ?>book.php">Book a Parcel</a></p><p><a href="<?= $rootPath ?>track.php">Track a Parcel</a></p><p><a href="<?= $rootPath ?>admin/login.php">Admin Login</a></p></div>
  </div>
  <div class="copyright">© <?= date('Y') ?> TMT Parcel Service. All rights reserved.</div>
</footer>
<script src="<?= $rootPath ?>assets/script.js"></script>
</body>
</html>
