<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Opérateur - Mobile Money</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
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
        <div class="card-header bg-white">
            <strong>Préfixes valables</strong>
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

            <form action="<?= base_url('admin/prefixe/add') ?>" method="post" class="row g-2 mt-2">
                <?= csrf_field() ?>
                <div class="col-auto">
                    <input type="text" name="prefixe" class="form-control form-control-sm"
                           placeholder="Ex: 034" required maxlength="5">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-danger">Ajouter un préfixe</button>
                </div>
            </form>
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
            <table class="table table-sm align-middle">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Montant min</th>
                        <th>Montant max</th>
                        <th>Frais</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($baremes as $b): ?>
                        <tr>
                            <td><?= esc($b['type_libelle']) ?></td>
                            <td><?= number_format($b['montant_min'], 0, ',', ' ') ?> Ar</td>
                            <td><?= number_format($b['montant_max'], 0, ',', ' ') ?> Ar</td>
                            <td><?= number_format($b['frais'], 0, ',', ' ') ?> Ar</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <hr>
            <p class="small text-muted mb-2">Ajouter une nouvelle tranche (remplace automatiquement l'ancienne tranche active du même type) :</p>
            <form action="<?= base_url('admin/bareme/add') ?>" method="post" class="row g-2">
                <?= csrf_field() ?>
                <div class="col-md-3">
                    <select name="type_operation_id" class="form-select form-select-sm" required>
                        <option value="">-- Type --</option>
                        <?php foreach ($types as $t): ?>
                            <option value="<?= $t['id'] ?>"><?= esc($t['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" step="0.01" name="montant_min" class="form-control form-control-sm" placeholder="Montant min" required>
                </div>
                <div class="col-md-3">
                    <input type="number" step="0.01" name="montant_max" class="form-control form-control-sm" placeholder="Montant max" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="frais" class="form-control form-control-sm" placeholder="Frais" required>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-sm btn-danger w-100">OK</button>
                </div>
            </form>
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