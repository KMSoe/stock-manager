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
        <h2>Add Item</h2>
        <form method="POST" action="/items">
            <?php
                $errors = SessionHelper::getValidationErrors() ?? [];
                $old = SessionHelper::getOldValues() ?? [];
            ?>
            <!-- Name Field -->
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input 
                    type="text" 
                    class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                    id="name" 
                    name="name" 
                    value="<?= $old['name'] ?? '' ?>"
                >
                <?php if (isset($errors['name'])): ?>
                    <div class="invalid-feedback">
                        <?= $errors['name'][0] ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="sku" class="form-label">SKU</label>
                <input 
                    type="text" 
                    class="form-control <?= isset($errors['sku']) ? 'is-invalid' : '' ?>" 
                    id="sku" 
                    name="sku" 
                    value="<?= $old['sku'] ?? '' ?>"
                >
                <?php if (isset($errors['sku'])): ?>
                    <div class="invalid-feedback">
                        <?= $errors['sku'][0] ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input 
                    type="text" 
                    class="form-control <?= isset($errors['price']) ? 'is-invalid' : '' ?>" 
                    id="price" 
                    name="price" 
                    value="<?= $old['price'] ?? '' ?>"
                >
                <?php if (isset($errors['price'])): ?>
                    <div class="invalid-feedback">
                        <?= $errors['price'][0] ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select id="category_id" name="category_id" class="form-select">
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                    <?php endforeach ?>
                </select>
                <?php if (isset($errors['category_id'])): ?>
                    <div class="invalid-feedback">
                        <?= $errors['category_id'][0] ?>
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
        </div>
    </div>
</div>

<?php
include_once "../views/partials/footer.php";
?>