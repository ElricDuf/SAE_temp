<?= $this->extend('layouts/main') ?>
<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/account.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="container">
    <h1>Mes favoris</h1>

    <?php if (empty($favorites)) : ?>
        <div class="empty-state">
            <p>Aucun favori pour le moment.</p>
            <p><a href="<?= site_url('/beats') ?>" class="btn">Découvrir des beats</a></p>
        </div>
    <?php else: ?>
        <div class="favorites-list">
            <?php foreach ($favorites as $b) : ?>
                <div class="favorite-item">
                    <div>
                        <div class="favorite-title">
                            <a href="<?= site_url('/beats/' . (int)$b['id']) ?>">
                                <?= esc($b['title']) ?>
                            </a>
                        </div>
                    </div>
                    <div class="favorite-price">
                        <?= number_format((float)$b['price'], 2, ',', ' ') ?> €
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <a href="<?= site_url('/account') ?>" class="back-link">← Retour Mon compte</a>
</div>

<?= $this->endSection() ?>
