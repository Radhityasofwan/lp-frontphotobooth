<?php
// footer.php
?>

<footer class="text-center p-4 bg-dark text-white-50">
    <p class="mb-0"><?= h(get_setting('footer_copyright', '© ' . date('Y') . ' Front Photobooth. All Rights Reserved.')) ?></p>
</footer>

<!-- Bootstrap JS -->
<script src="<?= asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') ?>"></script>

<!-- Anda bisa membuat file JS kustom di sini -->
<script src="<?= asset('assets/js/main.js') ?>"></script>

</body>
</html>