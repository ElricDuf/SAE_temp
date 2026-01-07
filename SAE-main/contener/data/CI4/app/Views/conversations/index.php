<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>Conversations</h1>

<?php if (empty($conversations)) : ?>
    <p>Aucune conversation.</p>
<?php else : ?>
    <ul>
        <?php foreach ($conversations as $c) : ?>
            <li>
                <a href="<?= base_url('/conversations/' . (int)$c['id']) ?>">
                    <?= esc($c['other_username'] ?? 'Utilisateur') ?>
                </a>
                — annonce : <?= esc($c['listing_title'] ?? 'N/A') ?>
                <?php if (isset($c['listing_price'])) : ?>
                    (<?= esc((string)$c['listing_price']) ?> €)
                <?php endif; ?>
                <small>— <?= esc($c['created_at']) ?></small>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?= $this->endSection() ?>
