<?= $this->extend('layouts/main') ?>

<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/artists.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h1>Artistes</h1>

<section class="artist-section">
  <h2>Les beatmakers qui vendent le plus</h2>

  <div class="artist-row">
    <?php foreach ($topSellers as $u): ?>
      <div class="artist-card">
        <div class="artist-avatar">
          <?php if (!empty($u['avatar'])): ?>
            <img src="<?= base_url('images/' . esc($u['avatar'])) ?>" alt="Avatar">
          <?php else: ?>
            <img src="<?= base_url('images/default.png') ?>" alt="Avatar">
          <?php endif; ?>
        </div>

        <div class="artist-name"><?= esc($u['username']) ?></div>

        <?php if (!empty($u['artist_genre'])): ?>
          <div class="artist-genre"><?= esc($u['artist_genre']) ?></div>
        <?php endif; ?>

        <div class="artist-stats"><?= (int)$u['sold_count'] ?> beats vendus</div>

        <div class="beat-chips">
          <?php foreach (($soldBeats[(int)$u['user_id']] ?? []) as $b): ?>
            <a class="beat-chip" href="<?= site_url('beats/'.$b['id']) ?>" title="<?= esc($b['category_name']) ?>">
              <?= esc($b['title']) ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="artist-section">
  <h2>Les beatmakers qui postent le plus</h2>

  <div class="artist-row">
    <?php foreach ($topPosters as $u): ?>
      <div class="artist-card">
        <div class="artist-avatar">
          <img src="<?= !empty($u['avatar']) ? base_url('images/' . esc($u['avatar'])) : base_url('images/default.png') ?>" alt="Avatar">
        </div>

        <div class="artist-name"><?= esc($u['username']) ?></div>

        <?php if (!empty($u['artist_genre'])): ?>
          <div class="artist-genre"><?= esc($u['artist_genre']) ?></div>
        <?php endif; ?>

        <div class="artist-stats"><?= (int)$u['available_count'] ?> beats disponibles</div>

        <div class="beat-chips">
          <?php foreach (($availBeats[(int)$u['user_id']] ?? []) as $b): ?>
            <a class="beat-chip" href="<?= site_url('beats/'.$b['id']) ?>">
              <?= esc($b['title']) ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="artist-section">
  <h2>Les beatmakers qui font des promotions</h2>
  <p style="color: #64748b; text-align: center;">Aucune promotion active actuellement.</p>
</section>

<?= $this->endSection() ?>