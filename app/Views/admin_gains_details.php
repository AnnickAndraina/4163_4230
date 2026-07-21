<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail des gains - Espace Opérateur</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('style.css') ?>">
</head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<body class="bg-light">

<nav class="navbar navbar-dark bg-danger mb-4">
    <div class="container">
        <span class="navbar-brand mb-0 h1">Espace Opérateur</span>
        <a href="<?= base_url('admin/logout') ?>" class="btn btn-outline-light btn-sm">Déconnexion</a>
    </div>
</nav>

<div class="container mb-5">

    <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary btn-sm mb-3">
        &larr; Retour au tableau de bord
    </a>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <strong>
                Détail des gains —
                <?= $categorie === 'local' ? 'Opérateurs locaux' : 'Autres opérateurs' ?>
            </strong>
        </div>
        <div class="card-body">
            <?php if (!empty($details)): ?>
                <div class="row text-center g-3">
                    <?php foreach ($details as $d): ?>
                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small"><?= esc($d['type_operation']) ?></div>
                                <div class="fs-4 fw-bold text-danger">
                                    <?= number_format($d['total_gains'], 0, ',', ' ') ?> Ar
                                </div>
                                <div class="text-muted small"><?= $d['nombre_operations'] ?> opération(s)</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted">Aucune donnée disponible pour cette catégorie.</p>
            <?php endif; ?>
        </div>
    </div>

</div>
</body>
</html>
