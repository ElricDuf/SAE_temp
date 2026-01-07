<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>Checkout</h1>

<?php if (!empty($hasUnavailable)): ?>
  <p>⚠️ Ton panier contient des beats indisponibles. Retour au panier pour les retirer.</p>
  <p><a href="<?= site_url('cart') ?>">Retour au panier</a></p>
<?php else: ?>
  <p>Total : <strong><?= number_format((float)$total, 2, ',', ' ') ?> €</strong></p>

  <?php if (session()->getFlashdata('error')): ?>
    <p>⚠️ <?= esc(session()->getFlashdata('error')) ?></p>
  <?php endif; ?>

  <form method="post" action="<?= site_url('cart/checkout') ?>">
    <?= csrf_field() ?>
    <button type="submit">Confirmer et payer (simulation)</button>
  </form>

  <p style="margin-top:12px;"><a href="<?= site_url('cart') ?>">Retour au panier</a></p>
<?php endif; ?>

<?= $this->endSection() ?>
