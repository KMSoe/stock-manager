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
        <h2>Edit Item</h2>
        <form method="POST" action="/items/update">
            <?php
                $errors = SessionHelper::getValidationErrors() ?? [];
                $old = SessionHelper::getOldValues() ?? [];
            ?>
            <input type="hidden" name="id" value="<?= $item['id'] ?>">

            <!-- Name Field -->
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input 
                    type="text" 
                    class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                    id="name" 
                    name="name" 
                    value="<?= isset($old['name']) ? $old['name'] : $item['name'] ?>"
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
                    value="<?= isset($old['sku']) ? $old['sku'] : $item['sku'] ?>"
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
                    value="<?= isset($old['price']) ? $old['price'] : $item['price'] ?>"
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
                        <option value="<?= $category['id'] ?>"
                        <?= ((isset($old['category_id']) && $old['category_id'] == $category['id']) || $item['category_id'] == $category['id']) ? 'selected' : '' ?>
                        ><?= $category['name'] ?></option>
                    <?php endforeach ?>
                </select>
                <?php if (isset($errors['category_id'])): ?>
                    <div class="invalid-feedback">
                        <?= $errors['category_id'][0] ?>
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
        </div>
    </div>
</div>

<?php
include_once "../views/partials/footer.php";
?>