<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light d-flex align-items-center min-vh-100">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card p-4 shadow-sm">
                <h3 class="text-center mb-4">Mobile Money</h3>
                <form action="<?= base_url('connexion/login') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Numéro de téléphone</label>
                        <input type="text" name="telephone" id="telephone" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Accéder au compte</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>