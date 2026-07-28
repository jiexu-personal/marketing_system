<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MarketingActivity;

class MarketingActivityController extends Controller
{
    // 显示老板看板 (Dashboard)
    public function index()
    {
        // 从数据库捞出所有的活动表单，latest() 表示按最新提交的时间排序
        $activities = MarketingActivity::latest()->get();
        
        // 把这些数据打包，发送给名叫 'dashboard' 的网页
        return view('dashboard', compact('activities'));
    }
	
	// 显示表单页面
    public function create()
    {
        return view('form');
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');

        // 1. 所有可能涉及算钱的金额/数字栏位，如果留空全自动补 0
        $numericFields = [
            'new_opening_amount', 'listing_fee_per_sku', 'listing_total_sku', 'listing_by_package', 'listing_total_fee',
            'rental_gondola_full', 'rental_gondola_half', 'rental_power_wing_full', 'rental_power_wing_half', 
            'rental_shelf_full', 'rental_shelf_half', 'rental_standee', 'rental_block_island',
            'ps_aged_stock_qty', 'ps_aged_stock_total', 'ps_markdown_qty', 'ps_markdown_total',
            'sponsor_new_opening', 'sponsor_anniversary', 'sponsor_warehouse', 'sponsor_exhibition', 'sponsor_mailer', 'sponsor_others',
            'order_walfood_brand', 'order_other_brand'
        ];

        foreach ($numericFields as $field) {
            $data[$field] = $data[$field] ?: 0;
        }

        // 2. 自动累加总报销额 (Grand Total)
        $fieldsToSum = [
            'new_opening_amount', 'listing_total_fee', 'listing_by_package',
            'rental_gondola_full', 'rental_gondola_half', 'rental_power_wing_full', 'rental_power_wing_half', 
            'rental_shelf_full', 'rental_shelf_half', 'rental_standee', 'rental_block_island',
            'ps_aged_stock_total', 'ps_markdown_total',
            'sponsor_new_opening', 'sponsor_warehouse', 'sponsor_mailer', 'sponsor_anniversary', 'sponsor_exhibition', 'sponsor_others'
        ];

        $grandTotal = 0;
        foreach ($fieldsToSum as $field) {
            $grandTotal += $data[$field];
        }
        
        $data['grand_total_claim'] = $grandTotal;

        // 3. 文字类的如果没填，给个空字符串，防止报错
        $data['account_code'] = $data['account_code'] ?? '';
        $data['rental_duration_from'] = $data['rental_duration_from'] ?? null;
        $data['rental_duration_to'] = $data['rental_duration_to'] ?? null;
        $data['ps_aged_stock_product'] = $data['ps_aged_stock_product'] ?? '';
        $data['ps_markdown_product'] = $data['ps_markdown_product'] ?? '';

        // 一键安全存入云端数据库
        MarketingActivity::create($data);

        return back()->with('success', 'Marketing activity submitted successfully!');
    }
	
	// 导出完整版数据为 CSV 文件 (可以用 Excel 直接打开)
    public function export()
    {
        $fileName = 'walfood_marketing_full_report_' . date('Y-m-d') . '.csv';
        $activities = MarketingActivity::latest()->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // 完整版的所有列表头（末尾新增了 PI 编号与审批相关字段）
        $columns = [
            'ID', 'Outlet Name', 'Account Code', 'Salesman', 'Apply Date', 
            'New Opening Amount', 'Listing Fee (Per SKU)', 'Listing Total SKUs', 'Listing By Package', 'Listing Total Fee', 
            'Duration From', 'Duration To', 'Gondola Full', 'Gondola Half', 'Power Wing Full', 'Power Wing Half', 
            'Shelf Full', 'Shelf Half', 'Standee', 'Block Island', 
            'Aged Stock Product', 'Aged Qty', 'Aged Total', 'Markdown Product', 'Markdown Qty', 'Markdown Total', 
            'Sponsor New Opening', 'Sponsor Anniversary', 'Sponsor Warehouse', 'Sponsor Exhibition', 'Sponsor Mailer', 'Sponsor Others', 
            'Order Walfood Brand', 'Order Other Brand', 'Grand Total Claim (RM)', 
            'Purchase Invoice ID', 'Approval Status', 'Approved By', 'Approved At', 'Submitted At'
        ];

        $callback = function() use($activities, $columns) {
            $file = fopen('php://output', 'w');
            
            // 为了防止 Excel 打开 CSV 乱码，加上 UTF-8 签名
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $columns);

            foreach ($activities as $a) {
                fputcsv($file, [
                    $a->id,
                    $a->outlet_name,
                    $a->account_code,
                    $a->salesman,
                    $a->apply_date,
                    $a->new_opening_amount,
                    $a->listing_fee_per_sku,
                    $a->listing_total_sku,
                    $a->listing_by_package,
                    $a->listing_total_fee,
                    $a->rental_duration_from,
                    $a->rental_duration_to,
                    $a->rental_gondola_full,
                    $a->rental_gondola_half,
                    $a->rental_power_wing_full,
                    $a->rental_power_wing_half,
                    $a->rental_shelf_full,
                    $a->rental_shelf_half,
                    $a->rental_standee,
                    $a->rental_block_island,
                    $a->ps_aged_stock_product,
                    $a->ps_aged_stock_qty,
                    $a->ps_aged_stock_total,
                    $a->ps_markdown_product,
                    $a->ps_markdown_qty,
                    $a->ps_markdown_total,
                    $a->sponsor_new_opening,
                    $a->sponsor_anniversary,
                    $a->sponsor_warehouse,
                    $a->sponsor_exhibition,
                    $a->sponsor_mailer,
                    $a->sponsor_others,
                    $a->order_walfood_brand,
                    $a->order_other_brand,
                    $a->grand_total_claim,
                    $a->purchase_invoice_id,
                    $a->approval_status,
                    $a->approved_by,
                    $a->approved_at,
                    $a->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
	
	// 显示完整版表单用于编辑
    public function edit($id)
    {
        $activity = MarketingActivity::findOrFail($id);
        return view('form', compact('activity'));
    }

    // 更新完整版数据
    public function update(Request $request, $id)
    {
        $activity = MarketingActivity::findOrFail($id);
        
        // 1. 如果是来自 Details 页面的发票号保存请求，单独在这里处理并直接返回，绝对不报错！
        if ($request->filled('invoice_date') && $request->filled('invoice_custom_no')) {
            $date = \Carbon\Carbon::parse($request->invoice_date);
            $yy = $date->format('y'); 
            $mm = $date->format('m'); 
            $customNo = str_pad($request->invoice_custom_no, 4, '0', STR_PAD_LEFT);
            
            $activity->update([
                'purchase_invoice_id' => "PI-{$yy}{$mm}-{$customNo}"
            ]);

            return back()->with('success', 'Purchase Invoice ID saved successfully!');
        }

        // 2. 如果是正常的完整表单更新
        $data = $request->except('_token', '_method');

        $numericFields = [
            'new_opening_amount', 'listing_fee_per_sku', 'listing_total_sku', 'listing_by_package', 'listing_total_fee',
            'rental_gondola_full', 'rental_gondola_half', 'rental_power_wing_full', 'rental_power_wing_half', 
            'rental_shelf_full', 'rental_shelf_half', 'rental_standee', 'rental_block_island',
            'ps_aged_stock_qty', 'ps_aged_stock_total', 'ps_markdown_qty', 'ps_markdown_total',
            'sponsor_new_opening', 'sponsor_anniversary', 'sponsor_warehouse', 'sponsor_exhibition', 'sponsor_mailer', 'sponsor_others',
            'order_walfood_brand', 'order_other_brand'
        ];

        // 核心保险：如果某些字段没传过来（比如在 details 页面点保存时），自动赋予它原本的旧数据或 0，防止报错！
        foreach ($numericFields as $field) {
            if (!isset($data[$field])) {
                $data[$field] = $activity->$field ?? 0;
            } else {
                $data[$field] = $data[$field] ?: 0;
            }
        }

        $fieldsToSum = [
            'new_opening_amount', 'listing_total_fee', 'listing_by_package',
            'rental_gondola_full', 'rental_gondola_half', 'rental_power_wing_full', 'rental_power_wing_half', 
            'rental_shelf_full', 'rental_shelf_half', 'rental_standee', 'rental_block_island',
            'ps_aged_stock_total', 'ps_markdown_total',
            'sponsor_new_opening', 'sponsor_warehouse', 'sponsor_mailer', 'sponsor_anniversary', 'sponsor_exhibition', 'sponsor_others'
        ];

        $grandTotal = 0;
        foreach ($fieldsToSum as $field) {
            $grandTotal += $data[$field];
        }
        $data['grand_total_claim'] = $grandTotal;

        $activity->update($data);

        return redirect('/dashboard')->with('success', 'Record updated successfully!');
    }

    // 显示单条记录的完整版 Details 详情页
    public function show($id)
    {
        $activity = MarketingActivity::findOrFail($id);
        return view('details', compact('activity'));
    }
	
	// 处理管理层签名审批
    public function approve(Request $request, $id)
    {
        $activity = MarketingActivity::findOrFail($id);
        
        // 验证输入的审批人名字和密码
        $managerName = $request->input('manager_name');
        $managerPassword = $request->input('manager_password');

        if ($managerPassword !== 'admin123') {
			return back()->with('error', 'Approval failed: Incorrect Management password!');
		}

        // 验证通过，更新审批状态
        $activity->update([
            'approval_status' => 'Approved',
            'approved_by' => $managerName,
            'approved_at' => now(),
        ]);

		return back()->with('success', 'Successfully approved and signed by Sales Management!');    
		}
	
	// 专门用来在 Details 页面保存发票号
    public function updateInvoice(Request $request, $id)
    {
        $activity = MarketingActivity::findOrFail($id);

        if ($request->filled('invoice_date') && $request->filled('invoice_custom_no')) {
            $date = \Carbon\Carbon::parse($request->invoice_date);
            $yy = $date->format('y'); 
            $mm = $date->format('m'); 
            $customNo = str_pad($request->invoice_custom_no, 4, '0', STR_PAD_LEFT);
            
            $activity->update([
                'purchase_invoice_id' => "PI-{$yy}{$mm}-{$customNo}"
            ]);
        }

        return back()->with('success', 'Purchase Invoice ID 保存成功！');
    }
}