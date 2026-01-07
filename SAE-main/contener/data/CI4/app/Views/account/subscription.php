<?= $this->extend('layouts/main') ?>
<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/account.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="container">
    <h1>Abonnement</h1>

    <div class="message-info">
        <strong>Attention :</strong> L'achat d'un abonnement n'est pas remboursable !
    </div>

    <?php if ($msg = session()->getFlashdata('success')) : ?>
        <div class="message-success"><?= esc($msg) ?></div>
    <?php endif; ?>
    <?php if ($msg = session()->getFlashdata('error')) : ?>
        <div class="message-error"><?= esc($msg) ?></div>
    <?php endif; ?>

    <h2>📋 Statut actuel</h2>
    <?php if (empty($subscription)) : ?>
        <div class="empty-state">
            <p><strong>Aucun abonnement actif.</strong></p>
        </div>
    <?php else: ?>
        <div class="stat-card">
            <p><strong>Type :</strong> <?= esc($subscription['type'] ?? '—') ?></p>
            <p><strong>Début :</strong> <?= esc($subscription['started_at'] ?? '—') ?></p>
            <p><strong>Fin :</strong> <?= esc($subscription['ends_at'] ?? '—') ?></p>
        </div>
    <?php endif; ?>

    <h2>💳 Acheter un abonnement</h2>
    <p>Choisissez une offre pour activer ou prolonger votre abonnement (simulation sans paiement réel).</p>

    <form method="post" action="<?= site_url('/account/subscription/buy') ?>">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="type">Offre :</label>
            <select name="type" id="type">
                <option value="premium">Premium (30 jours)</option>
                <option value="pro">Pro (30 jours)</option>
            </select>
        </div>

        <button type="submit" class="btn">Activer / Prolonger</button>
    </form>

    <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-color);">
        <small>Pour annuler un abonnement, merci de nous contacter.</small>
    </p>

    <a href="<?= site_url('/account') ?>" class="back-link">← Retour Mon compte</a>
</div>

<?= $this->endSection() ?>
