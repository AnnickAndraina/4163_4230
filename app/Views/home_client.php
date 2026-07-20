<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Espace - Mobile Money</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="card p-4 shadow-sm mb-4">
                <h2>Bienvenue, <?= esc($client['numero_telephone']) ?></h2>
                <h4 class="text-success mt-2">Mon Solde actuel : <?= esc($client['solde']) ?> Ar</h4>
                <div class="mt-3">
                    <a href="<?= base_url('/') ?>" class="btn btn-sm btn-secondary">Déconnexion</a>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card p-3 shadow-sm">
                        <h5>Faire un Dépôt</h5>
                        <form action="<?= base_url('client/depot') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="number" name="montant" class="form-control mb-2" placeholder="Montant Ar" required>
                            <button type="submit" class="btn btn-success w-100">Déposer</button>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p-3 shadow-sm">
                        <h5>Faire un Retrait</h5>
                        <form action="<?= base_url('client/retrait') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="number" name="montant" class="form-control mb-2" placeholder="Montant Ar" required>
                            <button type="submit" class="btn btn-danger w-100">Retirer</button>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p-3 shadow-sm">
                        <h5>Faire un Transfert</h5>
                        <form action="<?= base_url('client/transfert') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="text" name="destinataire" class="form-control mb-2" placeholder="N° Destinataire" required>
                            <input type="number" name="montant" class="form-control mb-2" placeholder="Montant Ar" required>
                            <button type="submit" class="btn btn-primary w-100">Transférer</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card p-4 shadow-sm">
                <h5>Historique des opérations</h5>
                <table class="table table-striped mt-2">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historique as $op): ?>
                            <tr>
                                <td><?= esc($op['type_libelle']) ?></td>
                                <td><?= esc($op['montant']) ?> Ar</td>
                                <td><?= esc($op['date_operation']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

</body>
</html>