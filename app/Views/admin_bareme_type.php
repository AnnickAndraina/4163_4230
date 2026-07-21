<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Barème - <?= esc($type['libelle']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('style.css') ?>">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-danger mb-4">
    <div class="container">
        <span class="navbar-brand mb-0 h1">Espace Opérateur</span>
        <a href="<?= base_url('admin/logout') ?>" class="btn btn-outline-light btn-sm">Déconnexion</a>
    </div>
</nav>

<div class="container mb-5">

    <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-sm btn-outline-secondary mb-3">&larr; Retour au dashboard</a>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong>Barème — <?= esc($type['libelle']) ?></strong>
            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalAjoutTranche">
                + Ajouter une tranche
            </button>
        </div>
        <div class="card-body">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Montant min</th>
                        <th>Montant max</th>
                        <th>Frais</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($baremes as $b): ?>
                        <tr>
                            <td><?= number_format($b['montant_min'], 0, ',', ' ') ?> Ar</td>
                            <td><?= number_format($b['montant_max'], 0, ',', ' ') ?> Ar</td>
                            <td><?= number_format($b['frais'], 0, ',', ' ') ?> Ar</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                        data-bs-toggle="modal" data-bs-target="#modalModifier<?= $b['id'] ?>">
                                    Modifier
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalModifier<?= $b['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="<?= base_url('admin/bareme/update') ?>" method="post">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                        <input type="hidden" name="type_operation_id" value="<?= $type['id'] ?>">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Modifier la tranche</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-2">
                                                <label class="form-label">Montant min</label>
                                                <input type="number" step="0.01" name="montant_min" class="form-control"
                                                       value="<?= $b['montant_min'] ?>" required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">Montant max</label>
                                                <input type="number" step="0.01" name="montant_max" class="form-control"
                                                       value="<?= $b['montant_max'] ?>" required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">Frais</label>
                                                <input type="number" step="0.01" name="frais" class="form-control"
                                                       value="<?= $b['frais'] ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-danger">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalAjoutTranche" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?= base_url('admin/bareme/add') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="type_operation_id" value="<?= $type['id'] ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter une tranche — <?= esc($type['libelle']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Montant min</label>
                            <input type="number" step="0.01" name="montant_min" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Montant max</label>
                            <input type="number" step="0.01" name="montant_max" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Frais</label>
                            <input type="number" step="0.01" name="frais" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Ajouter</button>