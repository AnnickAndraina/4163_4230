<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Administration - Mobile Money</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('style.css') ?>">
</head>
<body class="bg-light">

<?php if (session()->getFlashdata('popup_info')): ?>
    <div class="modal fade show" id="modalPopupInfo" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-primary"><i class="bi bi-info-circle me-2"></i>Notification</h5>
                    <button type="button" class="btn-close" onclick="document.getElementById('modalPopupInfo').style.display='none'"></button>
                </div>
                <div class="modal-body py-4 text-center">
                    <p class="fs-5 mb-0"><?= session()->getFlashdata('popup_info') ?></p>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center">
                    <button type="button" class="btn btn-primary px-4 rounded-pill" onclick="document.getElementById('modalPopupInfo').style.display='none'">Compris</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#"><i class="bi bi-shield-lock me-2"></i>M-Money <span class="badge bg-danger ms-1 fs-6">Admin</span></a>
        <a href="<?= base_url('/') ?>" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right me-1"></i>Déconnexion</a>
    </div>
</nav>

<div class="container pb-5">
    <div class="row g-4">
        
        <!-- Colonne Gauche : Résumé & Actions Administrateur -->
        <div class="col-lg-4">
            <!-- Carte Récapitulative / Total Commissions -->
            <div class="card balance-card-admin p-4 shadow-sm mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-white-50 small text-uppercase tracking-wider">Total Frais Générés</span>
                    <i class="bi bi-pie-chart text-white-50 fs-5"></i>
                </div>
                <h2 class="display-6 fw-bold mb-2"><?= number_format($total_commissions ?? 0, 2, ',', ' ') ?> <span class="fs-4">Ar</span></h2>
                <hr class="text-white-50 my-3">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                        <i class="bi bi-people text-white fs-5"></i>
                    </div>
                    <div>
                        <small class="text-white-50 d-block">Clients Enregistrés</small>
                        <span class="fw-medium"><?= esc($total_clients ?? 0) ?> utilisateurs</span>
                    </div>
                </div>
            </div>

            <!-- Bloc Actions Admin Cliquables -->
            <div class="card p-4 shadow-sm border-0 rounded-4 mb-4">
                <h5 class="fw-bold mb-3">Gestion système</h5>
                
                <div class="d-flex align-items-center p-3 mb-2 action-card" data-bs-toggle="modal" data-bs-target="#modalConfigFrais">
                    <div class="icon-box badge-admin me-3"><i class="bi bi-sliders"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold">Grille des Frais</h6>
                        <small class="text-muted">Ajuster les pourcentages/tarifs</small>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </div>

                <div class="d-flex align-items-center p-3 mb-2 action-card" data-bs-toggle="modal" data-bs-target="#modalAddClient">
                    <div class="icon-box badge-deposit me-3"><i class="bi bi-person-plus"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold">Nouveau Client</h6>
                        <small class="text-muted">Créer un compte utilisateur</small>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </div>

                <div class="d-flex align-items-center p-3 action-card" data-bs-toggle="modal" data-bs-target="#modalRapports">
                    <div class="icon-box badge-transfer me-3"><i class="bi bi-file-earmark-bar-graph"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold">Rapports & Audit</h6>
                        <small class="text-muted">Exporter les relevés globaux</small>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </div>
            </div>
        </div>

        <!-- Colonne Droite : Historique Global des Opérations -->
        <div class="col-lg-8">
            <div class="card p-4 shadow-sm border-0 rounded-4 min-vh-50">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Flux Global des Opérations</h5>
                    <span class="badge bg-secondary rounded-pill"><?= count($historique_global ?? []) ?> au total</span>
                </div>

                <?php if (empty($historique_global)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted display-4"></i>
                        <p class="text-muted mt-3 mb-0">Aucune transaction sur la plateforme pour le moment.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th>Type</th>
                                    <th>Client / Numéro</th>
                                    <th>Détails Financiers</th>
                                    <th class="text-end">Montant</th>
                                    <th>Date & Heure</th>
                                    <th class="text-center">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historique_global as $op): ?>
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
                                            <span class="fw-medium text-dark d-block"><?= esc($op['numero_telephone'] ?? 'Client') ?></span>
                                        </td>
                                        <td>
                                            <small class="text-muted d-block">Montant op. : <?= number_format($op['montant'], 0, ',', ' ') ?> Ar</small>
                                            <small class="text-muted d-block">Frais : <?= number_format($op['frais_applique'], 0, ',', ' ') ?> Ar</small>
                                            <small class="text-muted d-block">Total : <?= number_format($op['montant_total'], 0, ',', ' ') ?> Ar</small>
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

<!-- Modal Config Frais -->
<div class="modal fade" id="modalConfigFrais" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Configuration des frais</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/config-frais') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-muted">Frais de Retrait (%)</label>
                        <input type="number" step="0.01" name="frais_retrait" class="form-control form-control-lg" placeholder="ex: 1.5" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-muted">Frais de Transfert (%)</label>
                        <input type="number" step="0.01" name="frais_transfert" class="form-control form-control-lg" placeholder="ex: 1.0" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary w-50">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Création Client -->
<div class="modal fade" id="modalAddClient" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Créer un nouveau client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/add-client') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-muted">Numéro de Téléphone</label>
                        <input type="text" name="numero_telephone" class="form-control form-control-lg" placeholder="034 00 000 00" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-muted">Solde Initial (Ar)</label>
                        <input type="number" name="solde_initial" class="form-control form-control-lg" placeholder="0.00" min="0" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success w-50">Créer le compte</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>