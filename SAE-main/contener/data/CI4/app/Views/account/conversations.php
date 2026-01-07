<?= $this->extend('layouts/main') ?>
<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/account.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="container">
    <h1>Mes conversations</h1>

    <?php if (empty($conversations)) : ?>
        <div class="empty-state">
            <p>Aucune conversation pour le moment.</p>
        </div>
    <?php else: ?>
        <div class="conversations-list">
            <?php foreach ($conversations as $c) : ?>
                <?php
                    $other = ((int)$c['buyer_id'] === (int)$userId) ? ($c['seller_username'] ?? '—') : ($c['buyer_username'] ?? '—');
                ?>
                <div class="conversation-item">
                    <div class="conversation-title">
                        <a href="<?= site_url('/conversations/' . (int)$c['id']) ?>">
                            <?= esc($c['beat_title'] ?? 'Beat') ?> — avec <strong><?= esc($other) ?></strong>
                        </a>
                    </div>
                    <?php if (!empty($c['last_message'])) : ?>
                        <div class="conversation-last-message">
                            Dernier message : <?= esc($c['last_message']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <a href="<?= site_url('/account') ?>" class="back-link">← Retour Mon compte</a>
</div>

<?= $this->endSection() ?>
