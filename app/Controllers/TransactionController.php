<?php
namespace App\Controllers;

use App\Enums\TransactionType;
use App\Helpers\Auth;
use App\Helpers\HTTP;
use App\Helpers\SessionHelper;
use App\Helpers\Validator;
use App\Services\ItemService;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    private TransactionService $transactionService;
    private ItemService $itemService;
    private $user;

    public function __construct(TransactionService $transactionService, ItemService $itemService)
    {
        $this->user = Auth::check();
        $this->transactionService = $transactionService;
        $this->itemService        = $itemService;
    }

    public function index()
    {
        $can = Auth::can('view_transactions');

        if(!$can) {
           $this->render('error', ['message' => '403 Forbidden']);
           exit;
        }

        $transaction_type = isset($_GET['transaction_type']) ? $_GET['transaction_type'] : '';
        $search           = isset($_GET['search']) ? $_GET['search'] : '';
        $page             = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit            = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;

        $transactions = $this->transactionService->findByParams([
            'transaction_type' => $transaction_type,
            'search'           => $search,
            'page'             => $page,
            'limit'            => $limit,
        ]);

        $total_count = $this->transactionService->getCount($transaction_type, $search);
        $total_pages = ceil($total_count / $limit);

        $this->render('transactions/index', [
            'transactions'      => $transactions,
            'transaction_types' => array_map(fn($case) => $case->name, TransactionType::cases()),
            'transaction_type'  => $transaction_type,
            'search'            => $search,
            'page'              => $page,
            'limit'             => $limit,
            'total_pages'       => $total_pages,
        ]);
    }

    public function create()
    {
        $can = Auth::can('create_transactions');

        if(!$can) {
           $this->render('error', ['message' => '403 Forbidden']);
           exit;
        }

        $items = $this->itemService->findAll();

        $this->render('transactions/create', [
            'transaction_types' => array_map(fn($case) => $case->name, TransactionType::cases()),
            'items'             => $items,
        ]);
    }

    public function store()
    {
        $can = Auth::can('create_transactions');

        if(!$can) {
           $this->render('error', ['message' => '403 Forbidden']);
           exit;
        }

        $validator = new Validator($_POST);

        $validator->required('item_id')
            ->required('transaction_type')
            ->required('quantity');

        SessionHelper::startSession();

        if ($validator->fails()) {
            SessionHelper::setValidationErrors($validator->errors());
            SessionHelper::setOldValues($_POST);

            header('Location: /items/create');
            exit;
        }

        unset($_SESSION['validation_errors']);
        unset($_SESSION['old']);

        $_POST['author_id'] = $this->user['id'];
        $this->transactionService->save($_POST);

        SessionHelper::setFlashMessage('success', 'Saved');

        HTTP::redirect("/transactions", "");
    }

    public function destroy()
    {
        $can = Auth::can('delete_transactions');

        if(!$can) {
           $this->render('error', ['message' => '403 Forbidden']);
           exit;
        }

        $id = $_POST['id'];

        $this->transactionService->delete($id);

        SessionHelper::setFlashMessage('success', 'Deleted');

        HTTP::redirect("/transactions", "");
    }
}
