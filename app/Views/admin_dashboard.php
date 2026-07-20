<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Opérateur - Mobile Money</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .bareme-card { transition: 0.15s; }
        .bareme-card:hover { background-color: #f8f9fa; border-color: #BA1A1A !important; }
    </style>
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

    <!-- ========================================= -->
    <!-- SECTION 1 : Préfixes opérateurs -->
    <!-- ========================================= -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong>Préfixes valables</strong>
            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalAjoutPrefixe">
                + Ajouter
            </button>
        </div>
        <div class="card-body">
            <table class="table table-sm align-middle">
                <thead>
                    <tr>
                        <th>Préfixe</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prefixes as $p): ?>
                        <tr>
                            <td><?= esc($p['prefixe']) ?></td>
                            <td>
                                <?php if ($p['actif']): ?>
                                    <span class="badge bg-success">Actif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= base_url('admin/prefixe/toggle/' . $p['id']) ?>"
                                   class="btn btn-sm btn-outline-secondary">
                                    <?= $p['actif'] ? 'Désactiver' : 'Activer' ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Popup d'ajout de préfixe -->
    <div class="modal fade" id="modalAjoutPrefixe" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?= base_url('admin/prefixe/add') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter un préfixe</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Préfixe</label>
                        <input type="text" name="prefixe" class="form-control" placeholder="Ex: 034" maxlength="5" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ========================================= -->
    <!-- SECTION 2 : Barèmes de frais -->
    <!-- ========================================= -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
            <strong>Barèmes de frais</strong>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <?php foreach ($types as $t): ?>
                    <div class="col-md-4">
                        <a href="<?= base_url('admin/bareme/type/' . $t['id']) ?>" class="text-decoration-none">
                            <div class="border rounded p-4 text-center h-100 text-dark bareme-card">
                                <div class="fs-5 fw-bold"><?= esc($t['libelle']) ?></div>
                                <div class="text-muted small mt-1">Voir / modifier les tranches</div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ========================================= -->
    <!-- SECTION 3 : Situation des gains -->
    <!-- ========================================= -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
            <strong>Situation des gains (frais collectés)</strong>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <?php foreach ($gains as $g): ?>
                    <div class="col-md-6">
                        <div class="border rounded p-3 mb-2">
                            <div class="text-muted small"><?= esc($g['type_operation']) ?></div>
                            <div class="fs-4 fw-bold text-danger">
                                <?= number_format($g['total_gains'], 0, ',', ' ') ?> Ar
                            </div>
                            <div class="text-muted small"><?= $g['nombre_operations'] ?> opération(s)</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ========================================= -->
    <!-- SECTION 4 : Situation des comptes clients -->
    <!-- ========================================= -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
            <strong>Situation des comptes clients</strong>
        </div>
        <div class="card-body">
            <table class="table table-sm align-middle">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Solde</th>
                        <th>Statut</th>
                        <th>Nb opérations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comptes as $c): ?>
                        <tr>
                            <td><?= esc($c['nom']) ?></td>
                            <td><?= esc($c['numero_telephone']) ?></td>
                            <td><?= number_format($c['solde'], 0, ',', ' ') ?> Ar</td>
                            <td>
                                <?php if ($c['status'] === 'actif'): ?>
                                    <span class="badge bg-success">Actif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= esc($c['status']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= $c['total_operations_effectuees'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
</html>