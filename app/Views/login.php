<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Mobile Money</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            min-vh: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(30, 60, 114, 0.08);
            overflow: hidden;
            background: #ffffff;
        }
        .brand-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            transition: background 0.4s ease;
        }
        .brand-icon {
            width: 64px;
            height: 64px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 16px auto;
            backdrop-filter: blur(4px);
        }
        .form-control-lg {
            border-radius: 12px;
            font-size: 1rem;
            padding: 12px 16px;
            border-color: #e2e8f0;
        }
        .form-control-lg:focus {
            border-color: #2a5298;
            box-shadow: 0 0 0 4px rgba(42, 82, 152, 0.1);
        }
        .input-group-text-custom {
            background-color: transparent;
            border-color: #e2e8f0;
            border-radius: 12px 0 0 12px !important;
            color: #94a3b8;
            padding-left: 16px;
        }
        .has-icon .form-control-lg {
            border-radius: 0 12px 12px 0 !important;
        }
        .btn-custom-primary {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-custom-primary:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(42, 82, 152, 0.2);
            color: white;
        }
        .btn-custom-danger {
            background: linear-gradient(135deg, #cb2d3e 0%, #ef473a 100%);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-custom-danger:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 71, 58, 0.2);
            color: white;
        }
        .alert-custom {
            border-radius: 12px;
            border: none;
            font-size: 0.875rem;
        }
        .toggle-link {
            color: #64748b;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.2s;
        }
        .toggle-link:hover {
            color: #1e3c72;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-4">
            <div class="card login-card">
                
                <!-- En-tête Dynamique -->
                <div class="brand-header" id="entete-visuelle">
                    <div class="brand-icon" id="icone-plateforme">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <h4 class="fw-bold mb-1" id="titre-plateforme">Mobile Money</h4>
                    <p class="text-white-50 small mb-0" id="sous-titre-plateforme">Accédez à votre portefeuille en un instant</p>
                </div>

                <div class="card-body p-4">
                    
                    <!-- ZONE CLIENT -->
                    <div id="zone-client">
                        <?php if (!empty($errors['telephone'])): ?>
                            <div class="alert alert-danger alert-custom d-flex align-items-center mb-3" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <div><?= esc($errors['telephone']) ?></div>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('connexion/login') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="mb-4">
                                <label for="telephone" class="form-label small fw-semibold text-secondary">Numéro de téléphone</label>
                                <div class="input-group has-icon">
                                    <span class="input-group-text input-group-text-custom"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="telephone" id="telephone" class="form-control form-control-lg" placeholder="Ex: 034 00 000 00" value="<?= esc($values['telephone'] ?? '') ?>" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-custom-primary w-100 mb-3">
                                Se connecter <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </form>
                        
                        <div class="text-center">
                            <a href="#" onclick="basculer(true)" class="toggle-link">
                                <i class="bi bi-shield-lock me-1"></i> Espace Opérateur
                            </a>
                        </div>
                    </div>

                    <div id="zone-admin" class="d-none">
                        <?php if (session()->getFlashdata('error') && session()->getFlashdata('admin_error_flag')): ?>
                            <div class="alert alert-danger alert-custom d-flex align-items-center mb-3" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <div><?= session()->getFlashdata('error') ?></div>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('connexion/loginAdmin') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary">Identifiant</label>
                                <div class="input-group has-icon">
                                    <span class="input-group-text input-group-text-custom"><i class="bi bi-person"></i></span>
                                    <input type="text" name="username" class="form-control form-control-lg" placeholder="Nom d'utilisateur" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-semibold text-secondary">Mot de passe</label>
                                <div class="input-group has-icon">
                                    <span class="input-group-text input-group-text-custom"><i class="bi bi-key"></i></span>
                                    <input type="password" name="password" class="form-control form-control-lg" placeholder="••••••••" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-custom-danger w-100 mb-3">
                                Connexion Admin <i class="bi bi-lock ms-1"></i>
                            </button>
                        </form>

                        <div class="text-center">
                            <a href="#" onclick="basculer(false)" class="toggle-link">
                                <i class="bi bi-arrow-left me-1"></i> Retour au mode Client
                            </a>
                        </div>
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
    
    const entete = document.getElementById('entete-visuelle');
    const icone = document.getElementById('icone-plateforme');
    const titre = document.getElementById('titre-plateforme');
    const sousTitre = document.getElementById('sous-titre-plateforme');

    if (isAdmin) {
        entete.style.background = 'linear-gradient(135deg, #cb2d3e 0%, #ef473a 100%)';
        icone.innerHTML = '<i class="bi bi-shield-lock"></i>';
        titre.innerText = 'Espace Opérateur';
        sousTitre.innerText = 'Administration et suivi des flux';
    } else {
        entete.style.background = 'linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)';
        icone.innerHTML = '<i class="bi bi-wallet2"></i>';
        titre.innerText = 'Mobile Money';
        sousTitre.innerText = 'Accédez à votre portefeuille en un instant';
    }
}

// Reste sur l'interface d'administration si une erreur de connexion admin survient au rechargement
<?php if (session()->getFlashdata('error') && session()->getFlashdata('admin_error_flag')): ?> 
    basculer(true); 
<?php endif; ?>
</script>
</body>
</html>