<style>
.site-footer {
    margin-top: 4rem;
    padding: 1.5rem 2rem;
    border-top: 1px solid #ddd;
    font-size: 0.9rem;
}

.site-footer .footer-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.site-footer a {
    margin: 0 0.5rem;
    text-decoration: none;
}
</style>

<footer class="site-footer">
    <div class="footer-container">

        <div class="footer-left">
            <p>
                © <?= date('Y') ?> Tempo — Projet pédagogique IUT
            </p>
        </div>

        <div class="footer-center">
            <nav class="footer-nav">
                <a href="<?= site_url('/') ?>">Accueil</a>
                <a href="<?= site_url('/boutique') ?>">Boutique</a>

                <?php if (session()->has('user_id')): ?>
                    <a href="<?= site_url('/mon-compte') ?>">Mon compte</a>
                    <a href="<?= site_url('/account/conversations') ?>">Conversations</a>
                <?php endif; ?>
            </nav>
        </div>

        <div class="footer-right">
            <p>
                PHP • CodeIgniter 4 • MVC
            </p>
        </div>

    </div>
</footer>
