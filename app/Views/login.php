<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light d-flex align-items-center min-vh-100">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card p-4 shadow-sm">
                
                <div id="zone-client">
                    <h3 class="text-center mb-4">Mobile Money</h3>
                    <form action="<?= base_url('connexion/login') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="telephone" class="form-label">Numéro de téléphone</label>
                            <input type="text" name="telephone" id="telephone" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Accéder au compte</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="#" onclick="basculer(true)" class="text-muted small">Espace Opérateur</a>
                    </div>
                </div>

                <div id="zone-admin" class="d-none">
                    <h3 class="text-center mb-4 text-danger">Espace Opérateur</h3>
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger py-1 small"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>
                    <form action="<?= base_url('connexion/loginAdmin') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">Identifiant</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Se connecter (Admin)</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="#" onclick="basculer(false)" class="text-muted small">Retour au mode Client</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
function basculer(isAdmin) {
    document.getElementById('zone-client').classList.toggle('d-none', isAdmin);
    document.getElementById('zone-admin').classList.toggle('d-none', !isAdmin);
}
<?php if (session()->getFlashdata('error')): ?> basculer(true); <?php endif; ?>
</script>
</body>
</html>