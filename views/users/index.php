
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
                    <select name="role_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- All Roles --</option>
                        <?php foreach ($roles as $index => $role): ?>
                            <option value="<?php echo $role['id']?>" <?php echo ($role_id == $role['id']) ? 'selected' : ''?>><?php echo $role['name']?></option>
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
                <?php if(Auth::can('create_users')): ?>
                <div class="col-md-1">
                    <a href="/users/create" class="btn btn-primary" type="submit">New</a>
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
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Is Active</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $index => $user): ?>
                        <tr>
                            <th scope="row"><?php echo $index + 1?></th>
                            <td><?php echo $user['name']?></td>
                            <td><?php echo $user['email']?></td>
                            <td><?php echo $user['role_name']?></td>
                            <td><?php echo $user['is_active'] ? 'True' : 'False'?></td>
                            <td><?php echo $user['created_at']?></td>
                            <td>
                                <a href="/users/edit?id=<?= $user['id'] ?>" type="button" class="btn btn-primary">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach?>
                    <?php if (count($users) == 0): ?>
                        <tr>
                            <td colspan="7">
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
                        <a class="page-link" href="?page=<?= $page - 1?>&search=<?= $search ?>&role_id=<?= $role_id?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : ''?>">
                            <a class="page-link" href="?page=<?= $i?>&search=<?= $search ?>&role_id=<?= $role_id?>"><?= $i?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page == $total_pages ? 'disabled' : ''?>">
                        <a class="page-link" href="?page=<?= $page + 1?>&search=<?= $search ?>&role_id=<?= $role_id?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>


<?php
include_once "../views/partials/footer.php";
?>