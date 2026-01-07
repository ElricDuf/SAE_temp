<?= $this->extend('layouts/main') ?>

<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/boutique.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div style="display: flex; justify-content: space-between; align-items: center;">
    <h1><?= esc($title ?? 'Boutique') ?></h1>
    <?php if (session()->get('user_id')) : ?>
        <a href="<?= base_url('/beats/create') ?>" class="btn-publish">+ Publier un beat</a>
    <?php endif; ?>
</div>

<div class="search-container">
    <form method="GET" action="<?= base_url('/beats/search') ?>" class="search-form">
        <input type="hidden" name="do_search" value="1">

        <div class="form-group">
            <label>Recherche</label>
            <input type="text" name="q" placeholder="Titre, artiste..." value="<?= esc($filters['q'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Genre</label>
            <select name="category_id">
                <option value="">Tous les genres</option>
                <?php foreach (($categories ?? []) as $c) : ?>
                    <option value="<?= esc($c['id']) ?>" <?= ((string)($filters['category_id'] ?? '') === (string)$c['id']) ? 'selected' : '' ?>>
                        <?= esc($c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>BPM Min</label>
            <input type="number" name="bpm_min" value="<?= esc($filters['bpm_min'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>BPM Max</label>
            <input type="number" name="bpm_max" value="<?= esc($filters['bpm_max'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Clé</label>
            <input type="text" name="musical_key" placeholder="ex: Am" value="<?= esc($filters['musical_key'] ?? '') ?>">
        </div>

        <button type="submit" class="btn-search">Rechercher</button>
    </form>
</div>

<p style="color: #64748b; font-weight: 600; margin-bottom: 20px;">
    <?php if (empty($doSearch)) : ?>
         Propositions de beats
    <?php else : ?>
         Résultats de la recherche
    <?php endif; ?>
</p>

<?php if (empty($beats)) : ?>
    <div style="text-align: center; padding: 50px; background: #f8fafc; border-radius: 15px;">
        <p style="color: #64748b;">Aucun beat ne correspond à votre recherche.</p>
    </div>
<?php else : ?>
    <div class="beats-grid">
        <?php foreach ($beats as $b) : ?>
            <div class="beat-card">
                <div>
                    <div class="beat-header">
                        <a href="<?= base_url('/beats/' . (int)$b['id']) ?>" class="beat-title">
                            <?= esc($b['title']) ?>
                        </a>
                        <span class="beat-price"><?= esc($b['price']) ?>€</span>
                    </div>

                    <div class="beat-info">
                        <span class="info-tag"> <?= esc($b['category_name'] ?? 'Inconnu') ?></span>
                        <span class="info-tag"> <?= esc($b['bpm'] ?? '—') ?> BPM</span>
                        <span class="info-tag"> <?= esc($b['musical_key'] ?? '—') ?></span>
                    </div>
                </div>

                <div class="beat-seller">
                    Par <strong><?= esc($b['seller_username'] ?? 'Anonyme') ?></strong>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>