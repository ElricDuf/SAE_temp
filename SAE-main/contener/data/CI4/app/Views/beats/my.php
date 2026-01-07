<?= $this->extend('layouts/main') ?>
<?= $this->section('extra-css') ?>
    <link rel="stylesheet" href="<?= base_url('css/account.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="container">
    <h1><?= esc($title ?? 'Mes beats') ?></h1>

    <p>
        <a href="<?= site_url('/beats/create') ?>" class="btn">+ Ajouter un beat</a>
    </p>

    <?php if (empty($beats)) : ?>
        <div class="empty-state">
            <p>Tu n'as encore publié aucun beat.</p>
            <p><a href="<?= site_url('/beats/create') ?>" class="btn">Créer mon premier beat</a></p>
        </div>
    <?php else : ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                <tr style="background: var(--bg-light-gray); border-bottom: 2px solid var(--primary-blue);">
                    <th style="padding: 15px; text-align: left; font-weight: 600; color: var(--text-main);">Titre</th>
                    <th style="padding: 15px; text-align: left; font-weight: 600; color: var(--text-main);">Prix</th>
                    <th style="padding: 15px; text-align: left; font-weight: 600; color: var(--text-main);">BPM</th>
                    <th style="padding: 15px; text-align: left; font-weight: 600; color: var(--text-main);">Status</th>
                    <th style="padding: 15px; text-align: left; font-weight: 600; color: var(--text-main);">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($beats as $b) : ?>
                    <tr style="border-bottom: 1px solid var(--border-color); transition: background 0.2s ease;">
                        <td style="padding: 15px;">
                            <a href="<?= site_url('/beats/' . (int)$b['id']) ?>" style="color: var(--primary-blue); font-weight: 500;">
                                <?= esc($b['title']) ?>
                            </a>
                        </td>
                        <td style="padding: 15px; color: var(--text-secondary);"><?= number_format((float)$b['price'], 2, ',', ' ') ?> €</td>
                        <td style="padding: 15px; color: var(--text-secondary);"><?= esc($b['bpm'] ?? '') ?></td>
                        <td style="padding: 15px;">
                            <span style="background: <?= $b['status'] === 'active' ? '#d1fae5' : '#fee2e2' ?>; color: <?= $b['status'] === 'active' ? '#047857' : '#7f1d1d' ?>; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                <?= esc($b['status'] ?? '') ?>
                            </span>
                        </td>
                        <td style="padding: 15px;">
                            <a href="<?= site_url('/beats/' . (int)$b['id'] . '/edit') ?>" class="btn btn-small" style="margin-right: 8px;">Modifier</a>

                            <form method="post" action="<?= site_url('/beats/' . (int)$b['id'] . '/delete') ?>" style="display:inline;">
                                <?= csrf_field() ?>
                                <button type="submit" onclick="return confirm('Supprimer ce beat ?')" class="btn btn-small btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <a href="<?= site_url('/account') ?>" class="back-link">← Retour Mon compte</a>
</div>

<?= $this->endSection() ?>
