<?php
namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\HTTP;
use App\Helpers\SessionHelper;
use App\Helpers\Validator;
use App\Services\RoleService;
use App\Services\UserService;

class UserController extends Controller
{
    private UserService $userService;
    private RoleService $roleService;
    private $user;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->user = Auth::check();
        $this->userService = $userService;
        $this->roleService = $roleService;   
    }

    public function index()
    {
        $can = Auth::can('view_users');

        if(!$can) {
           $this->render('error', ['message' => '403 Forbidden']);
           exit;
        }

        $roles = $this->roleService->findAll();

        $role_id = isset($_GET['role_id']) ? (int) $_GET['role_id'] : null;
        $search  = isset($_GET['search']) ? $_GET['search'] : '';
        $page    = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit   = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;

        $users = $this->userService->findByParams([
            'role_id' => $role_id,
            'search'  => $search,
            'page'    => $page,
            'limit'   => $limit,
        ]);
        $total_count = $this->userService->getCount($role_id, $search);
        $total_pages = ceil($total_count / $limit);

        $this->render('users/index', [
            'roles'       => $roles,
            'users'       => $users ?? [],
            'role_id'     => $role_id,
            'search'      => $search,
            'page'        => $page,
            'limit'       => $limit,
            'total_pages' => $total_pages,
        ]);
    }

    public function create()
    {
        $can = Auth::can('create_users');

        if(!$can) {
           $this->render('error', ['message' => '403 Forbidden']);
           exit;
        }

        $roles = $this->roleService->findAll();

        $this->render('users/create', [
            'roles' => $roles,
        ]);
    }

    public function store()
    {
        $can = Auth::can('create_users');

        if(!$can) {
           $this->render('error', ['message' => '403 Forbidden']);
           exit;
        }

        $validator = new Validator($_POST);

        $validator->required('name')
            ->required('email')->email('email')->unique('email', 'users', 'email')
            ->required('password')->minLength('password', 6)
            ->required('role_id')->exists('role_id', 'roles', 'id');

        SessionHelper::startSession();

        if ($validator->fails()) {
            SessionHelper::setValidationErrors($validator->errors());
            SessionHelper::setOldValues($_POST);

            header('Location: /users/create');
            exit;
        }

        unset($_SESSION['validation_errors']);
        unset($_SESSION['old']);

        $this->userService->save($_POST);

        SessionHelper::setFlashMessage('success', 'Saved');

        HTTP::redirect("/users", "");
    }

    public function edit()
    {

        $can = Auth::can('edit_users');

        if(!$can) {
           $this->render('error', ['message' => '403 Forbidden']);
           exit;
        }

        $id   = $_GET['id'] ?? null;
        $user = $this->userService->findById($id);

        if (! $user) {
            $this->render('error', ['message' => '404 Not Found']);
            exit;
        }

        $roles = $this->roleService->findAll();

        $this->render('users/edit', [
            'user'  => $user,
            'roles' => $roles,
        ]);
    }

    public function update()
    {
        $can = Auth::can('edit_users');

        if(!$can) {
           $this->render('error', ['message' => '403 Forbidden']);
           exit;
        }
        
        $validator = new Validator($_POST);

        $validator->required('name')
            ->required('email')->email('email')->unique('email', 'users', 'email', $_POST['id'])
            ->required('password')->minLength('password', 6)
            ->required('role_id')->exists('role_id', 'roles', 'id');

        SessionHelper::startSession();

        if ($validator->fails()) {
            SessionHelper::setValidationErrors($validator->errors());
            SessionHelper::setOldValues($_POST);

            HTTP::redirect("/users/edit?id=" . $_POST['id'], "");
        }

        unset($_SESSION['validation_errors']);
        unset($_SESSION['old']);

        $this->userService->update($_POST['id'], $_POST);

        SessionHelper::setFlashMessage('success', 'Updated');

        HTTP::redirect("/users", "");
    }

    public function destroy()
    {

    }
}
