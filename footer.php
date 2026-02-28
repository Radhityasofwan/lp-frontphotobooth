<!-- FOOTER -->
<footer class="py-5 bg-white text-center border-top mt-5">
    <div class="container">
        <h4 class="text-dark mb-2 fw-bold" style="font-family:'Outfit', sans-serif;">
            <?= h(get_setting('footer_title', 'FRONT PHOTOBOOTH')) ?></h4>
        <div class="d-flex justify-content-center gap-3 mb-4">
            <a href="index.php" class="text-muted text-decoration-none small hover-orange">Home</a>
            <a href="gallery.php" class="text-muted text-decoration-none small hover-orange">Gallery</a>
            <a href="templates.php" class="text-muted text-decoration-none small hover-orange">Templates</a>
            <a href="pricelist.php" class="text-muted text-decoration-none small hover-orange">Pricelist</a>
        </div>
        <p class="text-muted small fw-medium mb-0">&copy;
            <?= date('Y') ?>
            <?= h(get_setting('footer_copyright', 'Bikin Eventmu Jadi Legenda. All Rights Reserved.')) ?>
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= asset('assets/js/frontphotobooth.js') ?>"></script>

</body>

</html>