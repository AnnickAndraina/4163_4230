<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FoodSwipe — Inscription</title>
  <link rel="stylesheet" href="/css/style.css" />
</head>
<body>

<section id="page-inscription" style="background:var(--surface);">
  <nav class="nav-public">
    <a href="#" class="brand">Fit<span>Space</span></a>
  </nav>
  <div class="auth-wrapper">
    <div class="auth-card">
      <div class="auth-logo">Fit<span>Space</span></div>
      <div class="auth-subtitle">Créez votre compte client gratuitement.</div>

      <form onsubmit="doRegister(event)">
        <div class="form-grid-2 mb-3">
          <div class="form-group">
            <label class="form-label">Prénom</label>
            <input type="text" class="form-control" id="reg-prenom" placeholder="Jean" />
          </div>
          <div class="form-group">
            <label class="form-label">Nom</label>
            <input type="text" class="form-control" id="reg-nom" placeholder="Dupont" />
          </div>
        </div>
        <div class="form-group mb-3">
          <label class="form-label">Adresse email</label>
          <input type="email" class="form-control" id="reg-email" placeholder="jean.dupont@email.com" />
        </div>
        <div class="form-group mb-3">
          <label class="form-label">Mot de passe</label>
          <input type="password" class="form-control" id="reg-password" placeholder="8 caractères minimum" />
        </div>
        <div class="form-group mb-4">
          <label class="form-label">Confirmer le mot de passe</label>
          <input type="password" class="form-control" id="reg-password-confirm" placeholder="Retapez votre mot de passe" />
        </div>

        <p class="form-error" id="reg-error" style="color:var(--accent);font-size:0.78rem;margin-top:3px;"></p>
        
        <button type="submit" class="btn-primary-custom">Créer mon compte</button>
      </form>

      <hr class="auth-divider" />
      <div class="auth-footer">Déjà inscrit ? <a href="/login">Se connecter</a></div>
    </div>
  </div>
</section>



<script>
  function doRegister(event) {
    if (event) {
      event.preventDefault();
    }

    const name  = document.getElementById('reg-prenom').value.trim() + ' ' + document.getElementById('reg-nom').value.trim();
    const email = document.getElementById('reg-email').value.trim();
    const pwd   = document.getElementById('reg-password').value;
    const pwd2  = document.getElementById('reg-password-confirm').value;
    const err   = document.getElementById('reg-error');

    if (!name || !email || !pwd || !pwd2) {
      err.textContent = 'Veuillez remplir tous les champs.';
      err.classList.add('visible');
      return;
    }

    if (pwd.length < 8) {
      err.textContent = 'Le mot de passe doit contenir au moins 8 caractères.';
      err.classList.add('visible');
      return;
    }

    if (pwd !== pwd2) {
      err.textContent = 'Les mots de passe ne correspondent pas.';
      err.classList.add('visible');
      return;
    }

    err.classList.remove('visible');

    localStorage.setItem('fs_user', JSON.stringify({ name, email, pwd }));
    localStorage.setItem('fs_logged', 'true');

    window.location.href = '/home';
  }
</script>

</body>
</html>
