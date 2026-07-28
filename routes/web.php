<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarketingActivityController;

// 访问主页时，显示表单
Route::get('/', [MarketingActivityController::class, 'create']);

// 点击提交按钮时，触发存入数据库的动作
Route::post('/submit-form', [MarketingActivityController::class, 'store']);

// 老板专属看板页面
Route::get('/dashboard', [MarketingActivityController::class, 'index']);

// 导出 Excel/CSV 报表路由
Route::get('/export-marketing-data', [MarketingActivityController::class, 'export']);

Route::get('/marketing/{id}/edit', [MarketingActivityController::class, 'edit']);
Route::put('/marketing/{id}', [MarketingActivityController::class, 'update']);
Route::get('/marketing/{id}/details', [MarketingActivityController::class, 'show']);

// 审理
Route::post('/marketing/{id}/approve', [MarketingActivityController::class, 'approve']);

Route::post('/marketing/{id}/invoice', [MarketingActivityController::class, 'updateInvoice']);