<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>Panier</h1>

<style>
.cart-wrap { display:flex; gap:24px; align-items:flex-start; flex-wrap:wrap; }
.cart-list { flex: 1 1 520px; }
.cart-summary { flex: 0 0 320px; border-radius:12px; padding:14px; }
.cart-item { display:flex; justify-content:space-between; gap:12px; padding:12px; border-radius:12px; margin-bottom:12px; }
.cart-item-left { min-width:0; }
.cart-item-title { font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.cart-item-meta { font-size:0.95rem; opacity:0.85; margin-top:4px; }
.cart-item-actions { display:flex; gap:8px; align-items:center; flex-wrap:wrap; justify-content:flex-end; }
.cart-qty { display:flex; gap:8px; align-items:center; }
.cart-qty span { min-width:24px; text-align:center; display:inline-block; }
.btn { display:inline-block; padding:8px 12px; border-radius:10px; text-decoration:none; cursor:pointer; border:0; }
</style>

<?php if (session()->getFlashdata('error')): ?>
  <p>⚠️ <?= esc(session()->getFlashdata('error')) ?></p>
<?php endif; ?>
<?php if (session()->getFlashdata('success')): ?>
  <p>✅ <?= esc(session()->getFlashdata('success')) ?></p>
<?php endif; ?>

<?php if (empty($items)): ?>
  <p>Ton panier est vide.</p>
  <p><a class="btn" href="<?= site_url('beats') ?>">Voir les beats</a></p>
<?php else: ?>

  <div class="cart-wrap">
    <div class="cart-list">
      <?php foreach ($items as $it): ?>
        <?php
          $qty = (int)$it['quantite'];
          $price = (float)$it['price'];
          $line = $qty * $price;
          $isAvailable = ($it['status'] === 'active' && empty($it['buyer_id']));
        ?>

        <div class="cart-item">
          <div class="cart-item-left">
            <div class="cart-item-title">
              <a href="<?= site_url('beats/'.$it['beat_id']) ?>"><?= esc($it['title']) ?></a>
            </div>
            <div class="cart-item-meta">
              <span><?= number_format($price, 2, ',', ' ') ?> €</span>
            </div>
            <?php if (!$isAvailable): ?>
              <div class="cart-item-meta" style="margin-top:6px;">
                ⚠️ Ce beat n’est plus disponible.
              </div>
            <?php endif; ?>
          </div>

          <div class="cart-item-actions">

            <div style="min-width:120px; text-align:right;">
              <strong><?= number_format($line, 2, ',', ' ') ?> €</strong>
            </div>

            <form method="post" action="<?= site_url('cart/remove-line/'.$it['beat_id']) ?>">
              <?= csrf_field() ?>
              <button class="btn" type="submit">Retirer</button>
            </form>
          </div>
        </div>

      <?php endforeach; ?>
    </div>

    <aside class="cart-summary">
      <h2>Résumé</h2>
      <p>Total : <strong><?= number_format((float)$total, 2, ',', ' ') ?> €</strong></p>

      <?php if (!empty($hasUnavailable)): ?>
        <p>⚠️ Retire les beats indisponibles avant de payer.</p>
      <?php endif; ?>

      <?php if (empty($hasUnavailable)): ?>
        <?php if (!empty($isLoggedIn)): ?>
          <a class="btn" href="<?= site_url('cart/checkout') ?>">Passer commande</a>
        <?php else: ?>
          <a class="btn" href="<?= site_url('login') ?>">Se connecter pour payer</a>
        <?php endif; ?>
      <?php endif; ?>
    </aside>
  </div>

<?php endif; ?>

<?= $this->endSection() ?>
