<?= $this->extend('layouts/main') ?>

<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/beats.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <section class="hero">
        <div class="hero-content">
            <h1>Découvrez des sons de créateurs</h1>
            <p>Achetez les productions de créateurs indépendants pour soutenir leur talent et leur savoir-faire.</p>
            <a href="<?= base_url('/beats') ?>" class="btn-primary">En savoir plus</a>
        </div>
    </section>
    
    <section class="beatmakers">
        <div class="blob blob-left"></div>
        <div class="blob blob-right"></div>

        <h2 class = "section-title">Beatmakers incontournables</h2>
        <div class = "divider"></div>
        
        <div class="artist-grid">
            <div class="artist-card">
                <div class="img-container">
                    <img src="<?= base_url('./images/seraph1m.png') ?>" alt="S3R4PH1M">
                </div>
                <h3>S3R4PH1M</h3>
            </div>
            <div class="artist-card">
                <div class="img-container">
                    <img src="<?= base_url('./images/vlad.png') ?>" alt="Vladimir cauchemar">
                </div>
                <h3>Vladimir cauchemar</h3>
            </div>
            <div class="artist-card">
                <div class="img-container">
                    <img src="<?= base_url('./images/perceval.png') ?>" alt="Perceval">
                </div>
                <h3>Perceval</h3>
            </div>
        </div>
    </section>

    <?php $beatsList = $beats ?? ($listings ?? []); ?>

    <div class="latest-beats-section">
        <h2 class="section-title">Derniers beats</h2>
        <div class = "divider"></div>
        <?php if (empty($beatsList)) : ?>
            <div class="home-card" style="text-align:center;">
                <p>Aucun beat pour le moment.</p>
            </div>
        <?php else : ?>
            <div class="beats-grid">
                <?php foreach ($beatsList as $b) : ?>
                    <div class="home-card beat-card-variant">
                        <div class="beat-info">
                            <h3>
                                <a href="<?= base_url('/beats/' . (int)$b['id']) ?>">
                                    <?= esc($b['title']) ?>
                                </a>
                            </h3>
                            <p class="beat-genre"><?= esc($b['category_name'] ?? 'Sans genre') ?></p>
                            <div class="beat-details">
                                <span><?= esc($b['bpm'] ?? '—') ?> BPM</span>
                                <span><?= esc($b['musical_key'] ?? '—') ?></span>
                            </div>
                        </div>
                        <div class="beat-footer">
                            <span class="beat-price"><?= esc($b['price']) ?> €</span>
                            <small>par <strong><?= esc($b['seller_username'] ?? 'N/A') ?></strong></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <h2 class="section-title">Stats</h2>
    <div class = "divider"></div>
    <?php if (!empty($stats)) : ?>
        <div class="home-card stats-container-variant">
            <div class="stat-item"><strong><?= esc($stats['active_beats'] ?? 0) ?></strong> <span>Beats Actifs</span></div>
            <div class="stat-item"><strong><?= esc($stats['total_beats'] ?? 0) ?></strong> <span>Total Beats</span></div>
            <div class="stat-item"><strong><?= esc($stats['sold_beats'] ?? 0) ?></strong> <span>Vendus</span></div>
            <div class="stat-item"><strong><?= esc($stats['total_users'] ?? 0) ?></strong> <span>Utilisateurs</span></div>
        </div>
    <?php endif; ?>
<?= $this->endSection() ?>