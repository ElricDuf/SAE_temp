<?= $this->extend('layouts/main') ?>
<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/account.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="container">
    <h1>Modération</h1>

    <div class="message-info">
        <p>Cette section est prévue pour la modération de votre contenu et la gestion des signalements.</p>
    </div>

    <p>La fonctionnalité complète de modération sera bientôt disponible.</p>

    <a href="<?= site_url('/account') ?>" class="back-link">← Retour Mon compte</a>
</div>

<?= $this->endSection() ?>
