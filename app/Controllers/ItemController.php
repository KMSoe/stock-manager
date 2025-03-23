<?php
namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\HTTP;
use App\Helpers\SessionHelper;
use App\Helpers\Validator;
use App\Services\CategoryService;
use App\Services\ItemService;

class ItemController extends Controller
{
    private ItemService $itemService;
    private CategoryService $categoryService;
    private $user;

    public function __construct(ItemService $itemService, CategoryService $categoryService)
    {
        $this->user            = Auth::check();
        $this->itemService     = $itemService;
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $can = Auth::can('view_items');

        if (! $can) {
            $this->render('error', ['message' => '403 Forbidden']);
            exit;
        }

        $categories = $this->categoryService->findAll();

        $category_id = isset($_GET['category_id']) ? (int) $_GET['category_id'] : null;
        $search      = isset($_GET['search']) ? $_GET['search'] : '';
        $page        = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit       = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;

        $items = $this->itemService->findByParams([
            'category_id' => $category_id,
            'search'      => $search,
            'page'        => $page,
            'limit'       => $limit,
        ]);
        $total_count = $this->itemService->getCount($category_id, $search);
        $total_pages = ceil($total_count / $limit);

        $this->render('items/index', [
            'categories'  => $categories,
            'items'       => $items,
            'category_id' => $category_id,
            'search'      => $search,
            'page'        => $page,
            'limit'       => $limit,
            'total_pages' => $total_pages,
        ]);
    }

    public function create()
    {
        $can = Auth::can('create_items');

        if (! $can) {
            $this->render('error', ['message' => '403 Forbidden']);
            exit;
        }

        $categories = $this->categoryService->findAll();

        $this->render('items/create', [
            'categories' => $categories,
        ]);
    }

    public function store()
    {
        $can = Auth::can('create_items');

        if (! $can) {
            $this->render('error', ['message' => '403 Forbidden']);
            exit;
        }

        $validator = new Validator($_POST);

        $validator->required('name')
            ->required('quantity')
            ->required('price')
            ->required('category_id')->exists('category_id', 'categories', 'id');

        SessionHelper::startSession();

        if ($validator->fails()) {
            SessionHelper::setValidationErrors($validator->errors());
            SessionHelper::setOldValues($_POST);

            HTTP::redirect("/items/create", "");
        }

        unset($_SESSION['validation_errors']);
        unset($_SESSION['old']);

        $_POST['author_id'] = $this->user['id'];
        $this->itemService->save($_POST);

        SessionHelper::setFlashMessage('success', 'Saved');

        HTTP::redirect("/items", "");
    }

    public function edit()
    {
        $can = Auth::can('edit_items');

        if (! $can) {
            $this->render('error', ['message' => '403 Forbidden']);
            exit;
        }

        $id   = $_GET['id'] ?? null;
        $item = $this->itemService->findById($id);

        if (! $item) {
            $this->render('error', ['message' => '404 Not Found']);
            exit;
        }

        $categories = $this->categoryService->findAll();

        $this->render('items/edit', [
            'item'       => $item,
            'categories' => $categories,

        ]);
    }

    public function update()
    {
        $can = Auth::can('edit_items');

        if (! $can) {
            $this->render('error', ['message' => '403 Forbidden']);
            exit;
        }

        $validator = new Validator($_POST);

        $validator->required('name')
            ->required('quantity')
            ->required('price')
            ->required('category_id');

        SessionHelper::startSession();

        if ($validator->fails()) {
            SessionHelper::setValidationErrors($validator->errors());
            SessionHelper::setOldValues($_POST);

            HTTP::redirect("/items/edit?id=" . $_POST['id'], "");
        }

        unset($_SESSION['validation_errors']);
        unset($_SESSION['old']);

        $_POST['author_id'] = $this->user['id'];
        $this->itemService->update($_POST['id'], $_POST);

        SessionHelper::setFlashMessage('success', 'Updated');

        HTTP::redirect("/items", "");
    }

    public function destroy()
    {
        $can = Auth::can('delete_items');

        if (! $can) {
            $this->render('error', ['message' => '403 Forbidden']);
            exit;
        }

        $id = $_POST['id'];

        $this->itemService->delete($id);

        SessionHelper::setFlashMessage('success', 'Deleted');

        HTTP::redirect("/items", "");
    }
}
