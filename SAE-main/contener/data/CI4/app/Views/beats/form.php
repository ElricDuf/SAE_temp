<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<h1><?= esc($title ?? 'Beat') ?></h1>

<?php if (session()->getFlashdata('error')) : ?>
    <p><strong><?= esc(session()->getFlashdata('error')) ?></strong></p>
<?php endif; ?>

<?php if (session()->getFlashdata('success')) : ?>
    <p><strong><?= esc(session()->getFlashdata('success')) ?></strong></p>
<?php endif; ?>

<?php
    $isEdit = !empty($beat['id']);
    $action = $isEdit
        ? base_url('/beats/' . (int)$beat['id'] . '/edit')
        : base_url('/beats/create');
?>

<form method="POST" action="<?= esc($action) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <label>Titre</label><br>
    <input type="text" name="title" value="<?= esc(old('title', $beat['title'] ?? '')) ?>" required><br><br>

    <label>Prix (€)</label><br>
    <input type="number" step="0.01" min="0" name="price"
           value="<?= esc(old('price', $beat['price'] ?? '0.00')) ?>"><br><br>

    <label>Genre</label><br>
    <select name="category_id">
        <option value="">-- aucun --</option>
        <?php foreach (($categories ?? []) as $c) : ?>
            <?php $selected = (string)old('category_id', (string)($beat['category_id'] ?? '')) === (string)$c['id']; ?>
            <option value="<?= esc($c['id']) ?>" <?= $selected ? 'selected' : '' ?>>
                <?= esc($c['name']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>BPM</label><br>
    <input type="number" min="0" name="bpm" value="<?= esc(old('bpm', $beat['bpm'] ?? '')) ?>"><br><br>

    <label>Clé musicale (ex: Am, F#m)</label><br>
    <input type="text" name="musical_key" value="<?= esc(old('musical_key', $beat['musical_key'] ?? '')) ?>"><br><br>

    <label>Tags (séparés par des virgules)</label><br>
    <input type="text" name="tags" value="<?= esc(old('tags', $beat['tags'] ?? '')) ?>"><br><br>

    <label>Description</label><br>
    <textarea name="description" rows="6"><?= esc(old('description', $beat['description'] ?? '')) ?></textarea><br><br>

    <hr>
    <h3>Fichiers audio</h3>
    <p><em>Le preview sert à l’écoute. L’original est livré après achat (plus tard on gérera ça).</em></p>

    <label>Preview (MP3 uniquement)</label><br>
    <input type="file" name="preview_file" accept="audio/mpeg"><br>
    <small>Max 5 MB. <?= $isEdit ? 'Laisser vide pour garder le fichier actuel.' : '' ?></small>
    <br><br>

    <label>Original (MP3 ou WAV)</label><br>
    <input type="file" name="original_file" accept="audio/mpeg,audio/wav"><br>
    <small>Max 25 MB. <?= $isEdit ? 'Laisser vide pour garder le fichier actuel.' : '' ?></small>
    <br><br>

    <button type="submit"><?= $isEdit ? 'Enregistrer' : 'Créer' ?></button>
</form>

<p><a href="<?= base_url('/beats') ?>">← Retour</a></p>

<?= $this->endSection() ?>
