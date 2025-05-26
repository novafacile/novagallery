<footer class="container my-5 text-muted small text-center">
  <?php $addons->dispatch('templateFooterBegin') ?>
  <div>
    <?= $config->get('footerText'); ?>    
  </div>
  <?php $addons->dispatch('templateFooterEnd') ?>
</footer>

<script  src="<?= THEME_URL ?>/assets/simple-lightbox.min.js"></script>
<script  src="<?= THEME_URL ?>/assets/lazyload.min.js"></script>
<script  src="<?= THEME_URL ?>/assets/novagallery.js"></script>
<?php $addons->dispatch('templateBodyEnd'); ?>

</body>
</html>