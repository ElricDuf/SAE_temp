<?= $this->extend('layouts/main') ?>
<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/account.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="container">
    <h1>Mon compte</h1>

    <?php if (!empty($user)) : ?>
        <div class="profile-header">
            <strong><?= esc($user['username']) ?></strong>
            <p><?= esc($user['email']) ?></p>
            <?php if (!empty($user['artist_genre'])) : ?>
                <p>Genre : <?= esc($user['artist_genre']) ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <h2>Statistiques</h2>
    <div class="stats-container">
        <div class="stat-card">
            <strong><?= (int)($stats['beats_total'] ?? 0) ?></strong>
            <p>Beats publiés</p>
            <small><?= (int)($stats['beats_active'] ?? 0) ?> actifs • <?= (int)($stats['beats_sold'] ?? 0) ?> vendus</small>
        </div>
        <div class="stat-card">
            <strong><?= (int)($stats['favorites_count'] ?? 0) ?></strong>
            <p>Beats en favori</p>
        </div>
        <div class="stat-card">
            <strong><?= (int)($stats['conversations_count'] ?? 0) ?></strong>
            <p>Conversations</p>
        </div>
    </div>

    <?php if (!empty($wallet)) : ?>
        <div class="balance-card">
            <p>Solde wallet :</p>
            <div class="balance-amount">
                <?= number_format(((int)$wallet['balance_cents'])/100, 2, ',', ' ') ?> €
            </div>
        </div>
    <?php endif; ?>

    <h2>Navigation</h2>
    <ul>
        <li><a href="<?= site_url('/account/profile') ?>">Gestion du profil</a></li>
        <li><a href="<?= site_url('/account/beats') ?>">Mes beats</a></li>
        <li><a href="<?= site_url('/account/favorites') ?>">Mes favoris</a></li>
        <li><a href="<?= site_url('/account/conversations') ?>">Mes conversations</a></li>
        <li><a href="<?= site_url('/account/wallet') ?>">Wallet</a></li>
        <li><a href="<?= site_url('/account/subscription') ?>">Abonnement</a></li>
        <li><a href="<?= site_url('/account/moderation') ?>">Modération</a></li>
    </ul>
</div>

<?= $this->endSection() ?>
