
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
            <select name="category_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- All Categories --</option>
                <?php foreach ($categories as $index => $category): ?>
                    <option value="<?php echo $category['id']?>" <?php echo ($category_id == $category['id']) ? 'selected' : ''?>><?php echo $category['name']?></option>
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
    <?php if(Auth::can('create_items')): ?>
    <div class="col-md-1">
      <a href="/items/create" class="btn btn-primary">New</a>
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
      <th scope="col">Name</th>
      <th scope="col">SKU</th>
      <th scope="col">Category</th>
      <th scope="col">Price</th>
      <th scope="col">Quantity</th>
      <th scope="col">Author</th>
      <th scope="col">Created At</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($items as $index => $item): ?>
        <tr>
            <th scope="row"><?php echo $index + 1?></th>
            <td><?php echo $item['name']?></td>
            <td><?php echo $item['sku']?></td>
            <td><?php echo $item['category_name']?></td>
            <td><?php echo $item['price'] ?></td>
            <td><?php echo $item['quantity'] ?></td>
            <td><?php echo $item['author_name']?></td>
            <td><?php echo $item['created_at']?></td>
            <td>
            <div class="d-flex flex-row gap-2">
                <?php if(Auth::can('edit_items')): ?>
                <a href="/items/edit?id=<?= $item['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                <?php endif; ?>
                <?php if(Auth::can('delete_items')): ?>
                <form action="/item-destroy" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger">
                        Delete
                    </button>
                </form>
                <?php endif; ?>

    </div>
            </td>
        </tr>
    <?php endforeach?>
<?php if (count($items) == 0): ?>
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
                    <a class="page-link" href="?page=<?= $page - 1?>&search=<?= $search ?>&category_id=<?= $category_id?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : ''?>">
                        <a class="page-link" href="?page=<?= $i?>&search=<?= $search ?>&category_id=<?= $category_id?>"><?= $i?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page == $total_pages ? 'disabled' : ''?>">
                    <a class="page-link" href="?page=<?= $page + 1?>&search=<?= $search ?>&category_id=<?= $category_id?>">Next</a>
                </li>
            </ul>
        </nav>
        </div>
    </div>
</div>


<?php
include_once "../views/partials/footer.php";
?>