<?= $this->extend('layouts/main') ?>
<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/account.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="container">
    <h1>Wallet</h1>

    <?php if (empty($wallet)) : ?>
        <div class="empty-state">
            <p>Aucun wallet pour le moment.</p>
        </div>
    <?php else: ?>
        <div class="balance-card">
            <p>Solde actuel</p>
            <div class="balance-amount">
                <?= number_format(((int)$wallet['balance_cents'])/100, 2, ',', ' ') ?> €
            </div>
            <p>Votre argent est sécurisé et prêt à être utilisé ou retiré.</p>
        </div>
    <?php endif; ?>

    <a href="<?= site_url('/account') ?>" class="back-link">← Retour Mon compte</a>
</div>

<?= $this->endSection() ?>
