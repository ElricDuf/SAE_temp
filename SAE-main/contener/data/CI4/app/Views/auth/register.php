<?= $this->extend('layouts/main') ?>

<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/auth.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>


<div class="auth-card">
    <h1>Créer un compte</h1>
    <p class="subtitle">Rejoignez la communauté Tempo</p>

    <?php if (!empty($error)) : ?>
        <p style="color: #ef4444; text-align: center; font-size: 0.8rem;"><?= esc($error) ?></p>
    <?php endif; ?>

    <form method="post" action="<?= base_url('/register') ?>">
        <?= csrf_field() ?>

        <label style="display:block; margin-bottom:8px; font-size:0.9rem;">Je suis un :</label>
        <div class="role-selector">
            <label class="role-option active" id="label-client">
                <input type="radio" name="role" value="client" checked onclick="selectRole('client')">
                <strong>Client</strong>
            </label>
            <label class="role-option" id="label-producer">
                <input type="radio" name="role" value="producer" onclick="selectRole('producer')">
                <strong>Producteur</strong>
            </label>
        </div>

        <div class="form-group">
            <label>Pseudo</label>
            <input type="text" name="username" placeholder="Pseudo ou nom d'artiste" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="votre@email.com" required>
        </div>

        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password" placeholder="Entrez votre mot de passe" required>
        </div>

        <div class="form-group">
            <label>Confirmer le mot de passe</label>
            <input type="password" name="confirm_password" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-beatflow">Créer mon compte</button>
    </form>

    <p class="footer-text">Déjà inscrit ? <a href="<?= base_url('/login') ?>">Se connecter</a></p>
</div>

<script>
    function selectRole(role) {
        document.getElementById('label-client').classList.remove('active');
        document.getElementById('label-producer').classList.remove('active');
        document.getElementById('label-' + role).classList.add('active');
    }
</script>

<?= $this->endSection() ?>