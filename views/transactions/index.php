
<?php

use App\Helpers\Auth;
use App\Helpers\SessionHelper;

    include_once "../views/partials/header.php";
?>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <?php
            include_once "../views/partials/sidebar.php";
        ?>
    <div class="col py-3">
        <form method="GET" class="row mb-4 g-2">
            <div class="col-md-4">
            <input type="text" onchange="this.form.submit()" name="search" value="<?php echo $search?>" class="form-control" placeholder="Search">
            </div>
            <div class="col-md-2">
            <button class="btn btn-primary" type="submit">Search</button>
            </div>

            <div class="col-md-2">
                <select name="transaction_type" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All --</option>
                    <?php foreach ($transaction_types as $index => $transaction_type_value): ?>
                        <option value="<?= $transaction_type_value ?>" <?= ($transaction_type == $transaction_type_value) ? 'selected' : ''?>><?= $transaction_type_value ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
       
            <div class="col-md-2">
                <select name="limit" class="form-select" onchange="this.form.submit()">
                    <?php foreach ([10, 20, 30] as $option): ?>
                        <option value="<?php echo $option?>"<?php echo ($limit == $option) ? 'selected' : ''?>><?php echo $option?> per page</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if(Auth::can('create_transactions')): ?>
            <div class="col-md-1">
            <a href="/transactions/create" class="btn btn-primary">New</a>
            </div>
            <?php endif;?>
        </form>
  <?php SessionHelper::startSession();
    $flash_message = SessionHelper::getFlashMessage('success');
     if ($flash_message): 
     ?>
        <div class="alert alert-success"><?= $flash_message ?></div>
    <?php endif; ?>
    <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Item</th>
      <th scope="col">Type</th>
      <th scope="col">Quantity</th>
      <th scope="col">Remark</th>
      <th scope="col">Author</th>
      <th scope="col">Created At</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($transactions as $index => $transaction): ?>
        <tr>
            <th scope="row"><?php echo $index + 1?></th>
            <td><?php echo $transaction['item_name']?></td>
            <td><?php echo $transaction['transaction_type']?></td>
            <td><?php echo $transaction['quantity']?></td>
            <td><?php echo $transaction['remark'] ?></td>
            <td><?php echo $transaction['author_name']?></td>
            <td><?php echo $transaction['created_at']?></td>
            <td>
                <form action="/transaction-destroy" method="POST" onsubmit="return confirm('Are you sure you want to delete?');" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $transaction['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach?>
<?php if (count($transactions) == 0): ?>
        <tr>
            <td colspan="9">
                No Records
            </td>
        </tr>
    <?php endif?>

  </tbody>
</table>
        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item <?= $page == 1 ? 'disabled' : ''?>">
                    <a class="page-link" href="?page=<?= $page - 1?>&search=<?= $search ?>&transaction_type=<?= $transaction_type?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : ''?>">
                        <a class="page-link" href="?page=<?= $i?>&search=<?= $search ?>&transaction_type=<?= $transaction_type?>"><?= $i?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page == $total_pages ? 'disabled' : ''?>">
                    <a class="page-link" href="?page=<?= $page + 1?>&search=<?= $search ?>&transaction_type=<?= $transaction_type?>">Next</a>
                </li>
            </ul>
        </nav>
        </div>
    </div>
</div>


<?php
include_once "../views/partials/footer.php";
?>