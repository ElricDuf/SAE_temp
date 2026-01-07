
<?= $this->extend('layouts/main') ?>

<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/auth.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="auth-card">
    <h1>Bon retour !</h1>
    <p class="subtitle">Connectez-vous pour accéder à vos beats</p>

    <?php if (!empty($error)) : ?>
        <p style="color: #ef4444; text-align: center; font-size: 0.8rem;"><?= esc($error) ?></p>
    <?php endif; ?>

    <form method="post" action="<?= base_url('/login') ?>">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="votre@email.com" required>
        </div>

        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-beatflow">Se connecter</button>
    </form>

    <p class="footer-text">Nouveau sur Tempo ? <a href="<?= base_url('/register') ?>">Créer un compte</a></p>
</div>

<?= $this->endSection() ?>