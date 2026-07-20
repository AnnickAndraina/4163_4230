<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Espace - Mobile Money</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .balance-card {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            border: none;
            border-radius: 16px;
        }
        .action-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }
        .action-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .badge-deposit { background-color: #e8f5e9; color: #2e7d32; }
        .badge-withdrawal { background-color: #ffebee; color: #c62828; }
        .badge-transfer { background-color: #e3f2fd; color: #1565c0; }
        
        .op-row { vertical-align: middle; }
    </style>
</head>
<body class="bg-light">

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

            <div class="card p-4 shadow-sm border-0 rounded-4 mb-4">
                <h5 class="fw-bold mb-3">Opérations rapides</h5>
                
                <div class="d-flex align-items-center p-3 mb-2 action-card" data-bs-toggle="modal" data-bs-target="#modalDepot">
                    <div class="icon-box badge-deposit me-3"><i class="bi bi-arrow-down-left-circle"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold">Dépôt</h6>
                        <small class="text-muted">Alimenter votre compte</small>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </div>

                <div class="d-flex align-items-center p-3 mb-2 action-card" data-bs-toggle="modal" data-bs-target="#modalRetrait">
                    <div class="icon-box badge-withdrawal me-3"><i class="bi bi-arrow-up-right-circle"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold">Retrait</h6>
                        <small class="text-muted">Retirer des fonds</small>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </div>

                <div class="d-flex align-items-center p-3 action-card" data-bs-toggle="modal" data-bs-target="#modalTransfert">
                    <div class="icon-box badge-transfer me-3"><i class="bi bi-send"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold">Transfert</h6>
                        <small class="text-muted">Envoyer à un proche</small>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card p-4 shadow-sm border-0 rounded-4 min-vh-50">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Historique des mouvements</h5>
                    <span class="badge bg-secondary rounded-pill"><?= count($historique) ?> au total</span>
                </div>

                <?php if (empty($historique)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-clock-history text-muted display-4"></i>
                        <p class="text-muted mt-3 mb-0">Aucune opération enregistrée pour le moment.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th>Type</th>
                                    <th>Détails</th>
                                    <th class="text-end">Montant</th>
                                    <th>Date & Heure</th>
                                    <th class="text-center">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historique as $op): ?>
                                    <tr class="op-row">
                                        <td>
                                            <?php if ($op['type_operation_id'] == 1): ?>
                                                <span class="badge badge-deposit p-2 rounded-3"><i class="bi bi-arrow-down-left me-1"></i> Dépôt</span>
                                            <?php elseif ($op['type_operation_id'] == 2): ?>
                                                <span class="badge badge-withdrawal p-2 rounded-3"><i class="bi bi-arrow-up-right me-1"></i> Retrait</span>
                                            <?php else: ?>
                                                <span class="badge badge-transfer p-2 rounded-3"><i class="bi bi-send me-1"></i> Transfert</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small class="text-muted d-block">Avant: <?= number_format($op['solde_avant'], 0, ',', ' ') ?> Ar</small>
                                            <small class="text-muted d-block">Après: <?= number_format($op['solde_apres'], 0, ',', ' ') ?> Ar</small>
                                        </td>
                                        <td class="text-end fw-bold">
                                            <?php if ($op['type_operation_id'] == 1): ?>
                                                <span class="text-success">+<?= number_format($op['montant'], 2, ',', ' ') ?> Ar</span>
                                            <?php else: ?>
                                                <span class="text-danger">-<?= number_format($op['montant'], 2, ',', ' ') ?> Ar</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="small text-dark"><?= date('d/m/Y', strtotime($op['date_operation'])) ?></span>
                                            <span class="small text-muted d-block"><?= date('H:i', strtotime($op['date_operation'])) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($op['statut'] === 'reussie'): ?>
                                                <i class="bi bi-check-circle-fill text-success fs-5" title="Réussie"></i>
                                            <?php else: ?>
                                                <i class="bi bi-x-circle-fill text-danger fs-5" title="Échouée"></i>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="modalDepot" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Effectuer un Dépôt</h5>
                <button type="button" class="btn-close" data-bs-shadow="none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('client/depot') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-muted">Montant à déposer (Ar)</label>
                        <div class="input-group">
                            <input type="number" name="montant" class="form-control form-control-lg" placeholder="0.00" min="1" required>
                            <span class="input-group-text bg-light fw-bold">Ar</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success w-50">Confirmer le dépôt</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRetrait" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Effectuer un Retrait</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('client/retrait') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-muted">Montant à retirer (Ar)</label>
                        <div class="input-group">
                            <input type="number" name="montant" class="form-control form-control-lg" placeholder="0.00" min="1" max="<?= $client['solde'] ?>" required>
                            <span class="input-group-text bg-light fw-bold">Ar</span>
                        </div>
                        <small class="text-muted mt-1 d-block">Solde max disponible : <?= number_format($client['solde'], 0, ',', ' ') ?> Ar</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger w-50">Confirmer le retrait</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTransfert" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Effectuer un Transfert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('client/transfert') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-muted">Numéro du destinataire</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="destinataire" class="form-control form-control-lg" placeholder="Ex: 034 00 000 00" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-muted">Montant à envoyer (Ar)</label>
                        <div class="input-group">
                            <input type="number" name="montant" class="form-control form-control-lg" placeholder="0.00" min="1" max="<?= $client['solde'] ?>" required>
                            <span class="input-group-text bg-light fw-bold">Ar</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary w-50">Envoyer les fonds</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>