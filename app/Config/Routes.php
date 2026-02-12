<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =========================
// GLOBAL
// =========================
$routes->options('(:any)', fn () => response()->setStatusCode(200));

$routes->get('/', 'Home::index');

$routes->post('ping', fn () =>
    response()->setJSON([
        'ok' => true,
        'method' => service('request')->getMethod(),
    ])
);



// =========================
// AUTH (WEB)
// =========================
$routes->get('login', 'Auth\Login::index');
$routes->post('login', 'Auth\Login::auth');
$routes->get('logout', 'Auth\Login::logout');


// DASHBOARD (WEB)
$routes->get('dashboard', 'DashboardController::index', ['filter' => 'auth']);

// USER (WEB)
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('users', 'UserController::index',['filter' => 'permission:users.view']);
    $routes->post('users/datatable', 'UserController::datatable',['filter' => 'permission:users.view']);
    $routes->post('users/store', 'UserController::store', ['filter' => 'permission:users.create']);
    $routes->post('users/get', 'UserController::getById', ['filter' => 'permission:users.edit']);
    $routes->post('users/update', 'UserController::update', ['filter' => 'permission:users.edit']);
    $routes->post('users/delete', 'UserController::delete', ['filter' => 'permission:users.delete']);
});

// COA (WEB)
$routes->group('coa', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'CoaController::index');
    $routes->post('datatable', 'CoaController::datatable');
    $routes->post('store', 'CoaController::store');
    $routes->post('get', 'CoaController::get');
    $routes->post('update', 'CoaController::update');
    $routes->post('delete', 'CoaController::delete');
});

// equity
$routes->group('equity', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'EquityController::index');
    $routes->post('datatable', 'EquityController::datatable');
    $routes->post('store', 'EquityController::store');
    $routes->post('update', 'EquityController::update');
    $routes->post('delete', 'EquityController::delete');
    $routes->post('get', 'EquityController::get');
    $routes->get('opening-balance', 'EquityController::openingBalance');
    $routes->post('opening-balance/save', 'EquityController::saveOpeningBalance');

});

// $routes->group('equity', ['filter' => 'auth'], function ($routes) {
//     $routes->get('/', 'EquityController::index', ['filter' => 'permission:equity.view']);
//     $routes->post('datatable', 'EquityController::datatable', ['filter' => 'permission:equity.view']);
//     $routes->post('store', 'EquityController::store', ['filter' => 'permission:equity.create']);
//     $routes->post('update', 'EquityController::update', ['filter' => 'permission:equity.edit']);
//     $routes->post('delete', 'EquityController::delete', ['filter' => 'permission:equity.delete']);
//     $routes->post('get', 'EquityController::get');
//     $routes->get('opening-balance', 'EquityController::openingBalance', ['filter' => 'permission:equity.create']);
//     $routes->post('opening-balance/save', 'EquityController::saveOpeningBalance', ['filter' => 'permission:equity.create']);
// });




// =========================
// API – PUBLIC
// =========================
$routes->group('api', function ($routes) {
    $routes->post('auth/login',    'Api\AuthController::login');
    $routes->post('auth/register', 'Api\AuthController::register');
    $routes->post('auth/refresh',  'Api\AuthController::refresh');
});

// =========================
// API – PROTECTED (JWT)
// =========================
$routes->group('api', ['filter' => 'jwt'], function ($routes) {
    // Login & Logout
    $routes->post('loginrefresh',   'Api\AuthController::refresh');
    $routes->post('logout',         'Api\AuthController::logout');
    $routes->post('logout-all',     'Api\AuthController::logoutAll');

    // Companies
    $routes->get('companies',            'Api\CompanyController::index');
    $routes->post('companies',           'Api\CompanyController::store');
    $routes->get('companies/(:num)',     'Api\CompanyController::show/$1');
    $routes->put('companies/(:num)',     'Api\CompanyController::update/$1');

    // Branches
    $routes->get('branches',              'Api\BranchController::index');
    $routes->post('branches',             'Api\BranchController::store');
    $routes->get('branches/(:num)',       'Api\BranchController::show/$1');

    // Fiscal Years
    $routes->get('fiscal-years',                   'Api\FiscalYearController::index');
    $routes->post('fiscal-years',                  'Api\FiscalYearController::store');
    $routes->post('fiscal-years/(:num)/activate',  'Api\FiscalYearController::activate/$1');

    // Accounting Periods
    $routes->get('periods',                 'Api\AccountingPeriodController::index');
    $routes->post('periods',                'Api\AccountingPeriodController::store');
    $routes->post('periods/(:num)/close',   'Api\AccountingPeriodController::close/$1');
    $routes->post('periods/(:num)/open',    'Api\AccountingPeriodController::open/$1');

    // Accounts
    $routes->get('accounts',            'Api\AccountController::index');
    $routes->post('accounts',           'Api\AccountController::store');
    $routes->get('accounts/tree',       'Api\AccountController::tree');
    $routes->get('accounts/(:num)',     'Api\AccountController::show/$1');
    $routes->put('accounts/(:num)',     'Api\AccountController::update/$1');

    // Business Partners
    $routes->get('partners',            'Api\BusinessPartnerController::index');
    $routes->post('partners',           'Api\BusinessPartnerController::store');
    $routes->get('partners/(:num)',     'Api\BusinessPartnerController::show/$1');

    // Transactions
    $routes->get('transactions',        'Api\TransactionController::index');
    $routes->post('transactions',       'Api\TransactionController::store');
    $routes->get('transactions/(:num)', 'Api\TransactionController::show/$1');

    // Journals
    $routes->get('journals',                    'Api\JournalController::index');
    $routes->post('journals',                    'Api\JournalController::create', ['filter' => 'permission:journal.create']);
    // $routes->post('journals',                   'Api\JournalController::store');
    $routes->get('journals/(:num)',             'Api\JournalController::show/$1');
    $routes->post('journals/(:num)/submit',     'Api\JournalController::submit/$1');
    $routes->post('journals/(:num)/approve',    'Api\JournalApprovalController::approve/$1');
    $routes->post('journals/(:num)/reject',     'Api\JournalApprovalController::reject/$1');
    $routes->post('journals/(:num)/post',       'Api\JournalController::post/$1');
    $routes->post('journals/(:num)/reverse',    'Api\JournalController::reverse/$1');

    // Taxes
    $routes->get('taxes',   'Api\TaxController::index');
    $routes->post('taxes',  'Api\TaxController::store');

    // Journal Taxes
    $routes->post('journal-taxes', 'Api\JournalTaxController::store');

    // Sub Ledgers
    $routes->get('sub-ledgers/ar',    'Api\SubLedgerController::accountsReceivable');
    $routes->get('sub-ledgers/ap',    'Api\SubLedgerController::accountsPayable');
    $routes->get('sub-ledgers/aging', 'Api\SubLedgerController::aging');

    // Reports
    $routes->get('reports/profit-loss',     'Api\ReportController::profitLoss');
    $routes->get('reports/balance-sheet',   'Api\ReportController::balanceSheet');
    $routes->get('reports/cash-flow',       'Api\ReportController::cashFlow');
    $routes->get('reports/branch/(:num)',   'Api\ReportController::byBranch/$1');
    $routes->get('reports/consolidated',    'Api\ReportController::consolidated');

    // Audit
    $routes->get('audit',            'Api\AuditController::index');
    $routes->get('audit/(:num)',     'Api\AuditController::show/$1');

    // Export
    $routes->get('export/journals',        'Api\ExportController::journals');
    $routes->get('export/profit-loss',     'Api\ExportController::profitLoss');
    $routes->get('export/balance-sheet',   'Api\ExportController::balanceSheet');

    // System
    $routes->get('system/health',       'Api\SystemController::health');
    $routes->get('system/permissions',  'Api\SystemController::permissions');
});
