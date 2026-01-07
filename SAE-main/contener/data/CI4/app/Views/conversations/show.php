<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>Messages</h1>

<?php if (!empty($error)) : ?>
    <p><?= esc($error) ?></p>
<?php endif; ?>

<div style="border:1px solid #ddd; padding:10px; margin-bottom:15px;">
    <?php if (empty($messages)) : ?>
        <p>Aucun message pour l'instant.</p>
    <?php else : ?>
        <?php foreach ($messages as $m) : ?>
            <p>
                <strong><?= esc($m['username'] ?? 'user') ?></strong>
                <small>(<?= esc($m['created_at']) ?>)</small><br>
                <?= nl2br(esc($m['content'])) ?>
            </p>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<form method="post" action="<?= base_url('/conversations/' . (int)$conversation['id'] . '/message') ?>">
    <?= csrf_field() ?>
    <label>Nouveau message</label><br>
    <textarea name="content" rows="4" cols="70" required></textarea><br><br>
    <button type="submit">Envoyer</button>
</form>

<p><a href="<?= base_url('/conversations') ?>">← Retour</a></p>

<?= $this->endSection() ?>
