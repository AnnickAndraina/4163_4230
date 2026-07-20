<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FitSpace</title>
  <link rel="stylesheet" href="/css/style.css" />
</head>
<body>
  <section id="page-login" style="background:var(--surface);">
  <nav class="nav-public">
    <a href="#" class="brand">Fit<span>Space</span></a>
  </nav>
  <div class="auth-wrapper">
    <div class="auth-card">
      <div class="auth-logo">Fit<span>Space</span></div>
      <div class="auth-subtitle">Bienvenue ! Connectez-vous à votre espace.</div>

      <form method="post">
        <div class="form-group mb-3">
          <label class="form-label" >Adresse email</label>
          <input type="email" id="login-email" class="form-control" placeholder="votre@email.com" />
        </div>
        <div class="form-group mb-4">
          <label class="form-label">Mot de passe</label>
          <input type="password" id="login-pwd" class="form-control" placeholder="••••••••" />
        </div>

        <div class="flash-message flash-error" id="login-error" style="display:none;">
            Email ou mot de passe incorrect.
        </div>

        <button type="button" class="btn-primary-custom" onclick="doLogin(event)">Se connecter</button>
      </form>

      <hr class="auth-divider" />
      <div class="auth-footer">Pas encore de compte ? <a href="/inscription">Créer un compte</a></div>
    </div>
  </div>
</section>


<script>
function doLogin(event) {
    event.preventDefault();

    const email = document.getElementById('login-email').value.trim();
    const pwd   = document.getElementById('login-pwd').value;
    const err   = document.getElementById('login-error');

    // Vérification champs
    if (!email || !pwd) {
        err.style.display = 'block';
        err.textContent = 'Veuillez remplir tous les champs.';
        return;
    }

    err.style.display = 'none';

    // Vérifie utilisateur
    const user = JSON.parse(localStorage.getItem('fs_user') || 'null');

    // Si aucun utilisateur enregistré
    if (!user) {

        localStorage.setItem('fs_user', JSON.stringify({
            name: 'Invité',
            email: email,
            pwd: pwd
        }));

        localStorage.setItem('fs_logged', 'true');

        window.location.href = '/home';

    } else {

        // Vérification login
        if (user.email === email && user.pwd === pwd) {

            localStorage.setItem('fs_logged', 'true');

            window.location.href = '/home';

        } else {

            err.style.display = 'block';
            err.textContent = 'Email ou mot de passe incorrect.';
        }
    }
}
</script>