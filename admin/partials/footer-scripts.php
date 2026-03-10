<?php
// Shared admin JS: Bootstrap bundle + minimal inline enhancements
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var alertEls = document.querySelectorAll('.alert');
    alertEls.forEach(function (el) {
      setTimeout(function () {
        el.style.transition = 'opacity 0.25s ease';
        el.style.opacity = '0';
      }, 3500);
    });
  });
</script>
