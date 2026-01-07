<?= $this->extend('layouts/main') ?>
<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/account.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="container">
    <h1>Mon profil</h1>

    <?php if (!empty($error)) : ?>
        <div class="message-error"><?= esc($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)) : ?>
        <div class="message-success"><?= esc($success) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" action="<?= site_url('/account/profile') ?>">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="artist_genre">Genre musical (optionnel)</label>
            <input type="text" id="artist_genre" name="artist_genre" 
                   value="<?= esc($user['artist_genre'] ?? '') ?>"
                   placeholder="Ex: Hip-Hop, EDM, Trap...">
        </div>

        <div class="form-group">
            <label for="avatar">Avatar (optionnel)</label>
            <input type="file" id="avatar" name="avatar" accept="image/*">
            <small>Formats acceptés: JPG, PNG. Taille max: 5 Mo</small>
        </div>

        <button type="submit" class="btn">Enregistrer les modifications</button>
    </form>

    <a href="<?= site_url('/account') ?>" class="back-link">← Retour Mon compte</a>
</div>

<?= $this->endSection() ?>
