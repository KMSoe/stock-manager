<!-- /app/Views/login.view.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

  <div class="card p-4 shadow-lg" style="width: 400px;">
    <h3 class="text-center mb-4">Login</h3>

    <?php

use App\Helpers\SessionHelper;

 SessionHelper::startSession();
      $flash_message = SessionHelper::getFlashMessage('success');
     if ($flash_message): 
     ?>
        <div class="alert alert-success"><?= $flash_message ?></div>
    <?php endif; ?>

    <?php
    $error_message = SessionHelper::getFlashMessage('error');
     if ($error_message): 
     ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
    <?php endif; ?>

    <form method="POST" action="/auth/login">
    <?php
                $errors = SessionHelper::getValidationErrors() ?? [];
                $old = SessionHelper::getOldValues() ?? [];
            ?>
      <div class="mb-3">
        <label>Email</label>
        <input 
                    type="email" 
                    class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                    id="email" 
                    name="email" 
                    value="<?= $old['email'] ?? '' ?>"
                >
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback">
                        <?= $errors['email'][0] ?>
                    </div>
                <?php endif; ?>
      </div>

      <div class="mb-3">
        <label>Password</label>
        <input 
                    type="password" 
                    class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                    id="password" 
                    name="password"
                >
                <?php if (isset($errors['password'])): ?>
                    <div class="invalid-feedback">
                        <?= $errors['password'][0] ?>
                    </div>
                <?php endif; ?>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Login</button>
      </div>
    </form>
  </div>

</body>
</html>
