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
        <h2>Add Transaction</h2>
        <form method="POST" action="/transactions">
            <?php
                $errors = SessionHelper::getValidationErrors() ?? [];
                $old = SessionHelper::getOldValues() ?? [];
            ?>
    
            
            

            

            <div class="mb-3">
                <label for="item_id" class="form-label">Item</label>
                <select id="item_id" name="item_id" class="form-select">
                    <?php foreach ($items as $item) : ?>
                        <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                    <?php endforeach ?>
                </select>
                <?php if (isset($errors['item_id'])): ?>
                    <div class="invalid-feedback">
                        <?= $errors['item_id'][0] ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="transaction_type" class="form-label">Transaction Type</label>
                <select id="transaction_type" name="transaction_type" class="form-select">
                    <?php foreach ($transaction_types as $transaction_type) : ?>
                        <option value="<?= $transaction_type ?>"><?= $transaction_type ?></option>
                    <?php endforeach ?>
                </select>
                <?php if (isset($errors['transaction_type'])): ?>
                    <div class="invalid-feedback">
                        <?= $errors['transaction_type'][0] ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input 
                    type="text" 
                    class="form-control <?= isset($errors['quantity']) ? 'is-invalid' : '' ?>" 
                    id="quantity" 
                    name="quantity" 
                    value="<?= $old['quantity'] ?? '' ?>"
                >
                <?php if (isset($errors['quantity'])): ?>
                    <div class="invalid-feedback">
                        <?= $errors['quantity'][0] ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="remark" class="form-label">Remark</label>
                <textarea class="form-control" id="remark" name="remark" rows="3"><?= $old['remark'] ?? '' ?></textarea>
                <?php if (isset($errors['remark'])): ?>
                    <div class="invalid-feedback">
                        <?= $errors['remark'][0] ?>
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