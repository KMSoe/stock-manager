<?php

use App\Helpers\SessionHelper;

    include_once "../views/partials/header.php";
?>
<div class="container-fluid">
    <div class="row flex-nowrap">
    <?php
        include_once "../views/partials/sidebar.php";
    ?>
        <div class="col py-3">
        <h2>Edit User</h2>
        <form method="POST" action="/users/update">
            <?php
                $errors = SessionHelper::getValidationErrors() ?? [];
                $old = SessionHelper::getOldValues() ?? [];
            ?>
            <input type="hidden" name="id" value="<?= $user['id'] ?>">

            <!-- Name Field -->
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input 
                    type="text" 
                    class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                    id="name" 
                    name="name" 
                    value="<?= isset($old['name']) ? $old['name'] : $user['name'] ?>"
                >
                <?php if (isset($errors['name'])): ?>
                    <div class="invalid-feedback">
                        <?= $errors['name'][0] ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Email Field -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input 
                    type="email" 
                    class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                    id="email" 
                    name="email" 
                    value="<?= isset($old['email']) ? $old['email'] : $user['email'] ?>"
                >
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback">
                        <?= $errors['email'][0] ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Password Field -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
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
            <div class="mb-3">
                <label for="role_id" class="form-label">Role</label>
                <select id="role_id" name="role_id" class="form-select">
                    <?php foreach ($roles as $role) : ?>
                        <option value="<?= $role['id'] ?>" 
                            <?= ((isset($old['role_id']) && $old['role_id'] == $role['id']) || $user['role_id'] == $role['id']) ? 'selected' : '' ?>
                        ><?= $role['name'] ?></option>
                    <?php endforeach ?>
                </select>
                <?php if (isset($errors['role_id'])): ?>
                    <div class="invalid-feedback">
                        <?= $errors['role_id'][0] ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-3 form-check">
                <input 
                        type="checkbox" 
                        class="form-check-input" 
                        id="is_active" 
                        name="is_active"
                        <?= ((isset($old['is_active']) && $old['is_active'] == 'on') || $user['is_active']) ? 'checked' : '' ?>

                    >
                <label for="is_active" class="form-check-label">Active</label>
                
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
        </div>
    </div>
</div>

<?php
include_once "../views/partials/footer.php";
?>