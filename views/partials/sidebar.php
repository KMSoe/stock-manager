<div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <span class="fs-5 d-none d-sm-inline">Menu</span>
                </a>
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                    <li class="nav-item">
                        <a href="/" class="nav-link align-middle px-0">
                            <i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline">Home</span>
                        </a>
                    </li>
                <?php
                    use App\Helpers\Auth;
                ?>
                <?php

                if(Auth::can('view_users')): ?>
                    <li>
                        <a href="/users" class="nav-link px-0 align-middle">
                            <i class="fs-4 bi-table"></i> <span class="ms-1 d-none d-sm-inline">Users</span></a>
                    </li>
                <?php endif; ?>

                <?php if(Auth::can('view_items')): ?>
                    <li>
                        <a href="/items" class="nav-link px-0 align-middle">
                            <i class="fs-4 bi-table"></i> <span class="ms-1 d-none d-sm-inline">Items</span></a>
                    </li>
                <?php endif; ?>

                <?php if(Auth::can('view_transactions')): ?>
                    <li>
                        <a href="/transactions" class="nav-link px-0 align-middle">
                            <i class="fs-4 bi-table"></i> <span class="ms-1 d-none d-sm-inline">Transactions</span></a>
                    </li>
                <?php endif; ?>

                  
                    <li>
                    <form action="/auth/logout" method="POST">
                        <button type="submit" class="btn btn-danger">Log Out</button>
                    </form>
                    </li>
                </ul>
            </div>
        </div>