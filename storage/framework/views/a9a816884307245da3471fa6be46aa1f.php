<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walfood iOS Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f2f7;
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Segoe UI", Roboto, sans-serif;
            color: #1c1c1e;
        }

        /* 顶部毛玻璃导航 */
        .ios-navbar {
            background: rgba(255, 255, 255, 0.7) !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* iOS 风格纯白大卡片 */
        .ios-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border: none;
            overflow: hidden;
        }

        /* 苹果风格区块小标题 */
        .ios-section-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #8e8e93;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e5ea;
        }

        /* 输入框 iOS 优雅圆角与聚焦微光 */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #d1d1d6;
            padding: 10px 14px;
            font-size: 0.95rem;
            background-color: #fcfcfc;
            transition: all 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            background-color: #ffffff;
            border-color: #007aff;
            box-shadow: 0 0 0 4px rgba(0, 122, 255, 0.15);
        }

        /* 提交大按钮 - iOS 蓝 */
        .btn-ios-primary {
            background-color: #007aff;
            color: white;
            border-radius: 14px;
            font-weight: 600;
            padding: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 122, 255, 0.25);
            transition: background 0.2s;
        }
        .btn-ios-primary:hover { background-color: #005bb5; color: white; }

        /* 返回看板小按钮 */
        .btn-ios-secondary {
            background-color: rgba(142, 142, 147, 0.12);
            color: #1c1c1e;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 6px 16px;
            border: none;
            text-decoration: none;
        }
        .btn-ios-secondary:hover { background-color: rgba(142, 142, 147, 0.2); color: #000; }
    </style>
</head>
<body>

<!-- 顶部毛玻璃导航 -->
<nav class="ios-navbar py-2 px-4">
    <div class="container-fluid d-flex justify-content-between align-items-center" style="max-width: 800px;">
        <span class="fw-bold fs-6">Walfood System</span>
        <a href="/dashboard" class="btn-ios-secondary">📊 Dashboard</a>
    </div>
</nav>

<div class="container mt-4 mb-5" style="max-width: 800px;">
    
    <div class="mb-3 px-2">
        <h2 class="fw-bold" style="letter-spacing: -0.5px;">Marketing Activity Form</h2>
        <p class="text-secondary small">Fill in the details below to submit or update the marketing claim.</p>
    </div>

    <div class="ios-card p-4 p-md-5">
        <?php if(session('success')): ?>
            <div class="alert alert-success fw-bold border-0 rounded-3 shadow-sm"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <form action="<?php echo e(isset($activity) ? '/marketing/' . $activity->id : '/submit-form'); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php if(isset($activity)): ?>
                <?php echo method_field('PUT'); ?>
            <?php endif; ?>
            
            <!-- 基本信息 -->
            <div class="ios-section-title mt-0">Basic Information</div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary">Outlet Name</label>
                    <input type="text" name="outlet_name" class="form-control" value="<?php echo e($activity->outlet_name ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary">Account Code</label>
                    <input type="text" name="account_code" class="form-control" value="<?php echo e($activity->account_code ?? ''); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary">Salesman</label>
                    <input type="text" name="salesman" class="form-control" value="<?php echo e($activity->salesman ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary">Apply Date</label>
                    <input type="date" name="apply_date" class="form-control" value="<?php echo e($activity->apply_date ?? ''); ?>" required>
                </div>
            </div>

            <!-- 1) New Opening -->
            <div class="ios-section-title">1) New Opening Account / Branches (RM)</div>
            <div class="mb-4">
                <input type="number" step="0.01" name="new_opening_amount" class="form-control sum-target" value="<?php echo e($activity->new_opening_amount ?? 0); ?>" placeholder="0.00">
            </div>

            <!-- 2) Listing Fee -->
            <div class="ios-section-title">2) New Product Listing Fee</div>
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label small text-secondary">Charge / SKU</label>
                    <input type="number" step="0.01" id="list_per_sku" name="listing_fee_per_sku" class="form-control" value="<?php echo e($activity->listing_fee_per_sku ?? 0); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-secondary">Total SKUs</label>
                    <input type="number" id="list_total_sku" name="listing_total_sku" class="form-control" value="<?php echo e($activity->listing_total_sku ?? 0); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-secondary">By Package (RM)</label>
                    <input type="number" step="0.01" name="listing_by_package" class="form-control sum-target" value="<?php echo e($activity->listing_by_package ?? 0); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-danger">Total Listing</label>
                    <input type="number" step="0.01" id="list_total_fee" name="listing_total_fee" class="form-control sum-target bg-white fw-bold text-danger" readonly value="<?php echo e($activity->listing_total_fee ?? 0); ?>">
                </div>
            </div>

            <!-- 3) Rental Fee -->
            <div class="ios-section-title">3) Rental Fee</div>
            <div class="row g-3 mb-3">
                <div class="col-12 d-flex align-items-center gap-2">
                    <span class="small text-secondary fw-bold">Duration:</span>
                    <input type="date" name="rental_duration_from" class="form-control form-control-sm w-auto" value="<?php echo e($activity->rental_duration_from ?? ''); ?>">
                    <span class="small text-secondary">to</span>
                    <input type="date" name="rental_duration_to" class="form-control form-control-sm w-auto" value="<?php echo e($activity->rental_duration_to ?? ''); ?>">
                </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6"><label class="small text-secondary">Gondola End Full (RM)</label><input type="number" step="0.01" name="rental_gondola_full" class="form-control sum-target" value="<?php echo e($activity->rental_gondola_full ?? 0); ?>"></div>
                <div class="col-md-6"><label class="small text-secondary">Gondola End Half (RM)</label><input type="number" step="0.01" name="rental_gondola_half" class="form-control sum-target" value="<?php echo e($activity->rental_gondola_half ?? 0); ?>"></div>
                <div class="col-md-6"><label class="small text-secondary">Power Wing Full (RM)</label><input type="number" step="0.01" name="rental_power_wing_full" class="form-control sum-target" value="<?php echo e($activity->rental_power_wing_full ?? 0); ?>"></div>
                <div class="col-md-6"><label class="small text-secondary">Power Wing Half (RM)</label><input type="number" step="0.01" name="rental_power_wing_half" class="form-control sum-target" value="<?php echo e($activity->rental_power_wing_half ?? 0); ?>"></div>
                <div class="col-md-6"><label class="small text-secondary">Shelf Full (RM)</label><input type="number" step="0.01" name="rental_shelf_full" class="form-control sum-target" value="<?php echo e($activity->rental_shelf_full ?? 0); ?>"></div>
                <div class="col-md-6"><label class="small text-secondary">Shelf Half (RM)</label><input type="number" step="0.01" name="rental_shelf_half" class="form-control sum-target" value="<?php echo e($activity->rental_shelf_half ?? 0); ?>"></div>
                <div class="col-md-6"><label class="small text-secondary">Standee Display (RM)</label><input type="number" step="0.01" name="rental_standee" class="form-control sum-target" value="<?php echo e($activity->rental_standee ?? 0); ?>"></div>
                <div class="col-md-6"><label class="small text-secondary">Block Island Display (RM)</label><input type="number" step="0.01" name="rental_block_island" class="form-control sum-target" value="<?php echo e($activity->rental_block_island ?? 0); ?>"></div>
            </div>

            <!-- 4) Price Solve -->
            <div class="ios-section-title">4) Price Solve</div>
            <div class="row g-2 align-items-end mb-3 pb-2 border-bottom">
                <div class="col-md-5"><label class="small text-secondary">Aged Stock Product</label><input type="text" name="ps_aged_stock_product" class="form-control" value="<?php echo e($activity->ps_aged_stock_product ?? ''); ?>"></div>
                <div class="col-md-3"><label class="small text-secondary">Qty</label><input type="number" name="ps_aged_stock_qty" class="form-control" value="<?php echo e($activity->ps_aged_stock_qty ?? 0); ?>"></div>
                <div class="col-md-4"><label class="small text-secondary text-danger">Total (RM)</label><input type="number" step="0.01" name="ps_aged_stock_total" class="form-control sum-target" value="<?php echo e($activity->ps_aged_stock_total ?? 0); ?>"></div>
            </div>
            <div class="row g-2 align-items-end mb-4">
                <div class="col-md-5"><label class="small text-secondary">Mark Down Product</label><input type="text" name="ps_markdown_product" class="form-control" value="<?php echo e($activity->ps_markdown_product ?? ''); ?>"></div>
                <div class="col-md-3"><label class="small text-secondary">Qty</label><input type="number" name="ps_markdown_qty" class="form-control" value="<?php echo e($activity->ps_markdown_qty ?? 0); ?>"></div>
                <div class="col-md-4"><label class="small text-secondary text-danger">Total (RM)</label><input type="number" step="0.01" name="ps_markdown_total" class="form-control sum-target" value="<?php echo e($activity->ps_markdown_total ?? 0); ?>"></div>
            </div>

            <!-- 5) Sponsorships -->
            <div class="ios-section-title">5) Sponsorships (RM)</div>
            <div class="row g-3 mb-4">
                <div class="col-md-6"><label class="small text-secondary">New Opening Support</label><input type="number" step="0.01" name="sponsor_new_opening" class="form-control sum-target" value="<?php echo e($activity->sponsor_new_opening ?? 0); ?>"></div>
                <div class="col-md-6"><label class="small text-secondary">Yearly Anniversary Sales</label><input type="number" step="0.01" name="sponsor_anniversary" class="form-control sum-target" value="<?php echo e($activity->sponsor_anniversary ?? 0); ?>"></div>
                <div class="col-md-6"><label class="small text-secondary">Warehouse Sales</label><input type="number" step="0.01" name="sponsor_warehouse" class="form-control sum-target" value="<?php echo e($activity->sponsor_warehouse ?? 0); ?>"></div>
                <div class="col-md-6"><label class="small text-secondary">Exhibition / Road Show</label><input type="number" step="0.01" name="sponsor_exhibition" class="form-control sum-target" value="<?php echo e($activity->sponsor_exhibition ?? 0); ?>"></div>
                <div class="col-md-6"><label class="small text-secondary">Mailer Advertising</label><input type="number" step="0.01" name="sponsor_mailer" class="form-control sum-target" value="<?php echo e($activity->sponsor_mailer ?? 0); ?>"></div>
                <div class="col-md-6"><label class="small text-secondary">Others</label><input type="number" step="0.01" name="sponsor_others" class="form-control sum-target" value="<?php echo e($activity->sponsor_others ?? 0); ?>"></div>
            </div>

            <!-- 6) Total Order Amount -->
            <div class="ios-section-title">6) Total Order Amount (For Reference)</div>
            <div class="row g-3 mb-4">
                <div class="col-md-6"><label class="small text-secondary">Walfood/Waltree/Wal Family (RM)</label><input type="number" step="0.01" name="order_walfood_brand" class="form-control" value="<?php echo e($activity->order_walfood_brand ?? 0); ?>"></div>
                <div class="col-md-6"><label class="small text-secondary">Other Brand (RM)</label><input type="number" step="0.01" name="order_other_brand" class="form-control" value="<?php echo e($activity->order_other_brand ?? 0); ?>"></div>
            </div>

            <!-- Grand Total 浮动提示块 -->
            <div class="p-3 bg-light rounded-4 border text-end mb-4 shadow-sm">
                <span class="text-secondary small fw-bold">Grand Total Claim:</span>
                <span class="fs-3 fw-bold text-danger ms-2">RM <span id="grand_display_total"><?php echo e($activity->grand_total_claim ?? '0.00'); ?></span></span>
            </div>

            <button type="submit" class="btn btn-ios-primary w-100 fs-6 shadow-sm">
                <?php echo e(isset($activity) ? 'Update Changes' : 'Submit Form'); ?>

            </button>
        </form>
    </div>
</div>

<script>
    const listPerSku = document.getElementById('list_per_sku');
    const listTotalSku = document.getElementById('list_total_sku');
    const listTotalFee = document.getElementById('list_total_fee');

    function calcListing() {
        if (!listPerSku || !listTotalSku || !listTotalFee) return;
        listTotalFee.value = ((parseFloat(listPerSku.value) || 0) * (parseFloat(listTotalSku.value) || 0)).toFixed(2);
        calcGrandTotal();
    }
    if(listPerSku) listPerSku.addEventListener('input', calcListing);
    if(listTotalSku) listTotalSku.addEventListener('input', calcListing);

    const allSumTargets = document.querySelectorAll('.sum-target');
    const grandDisplay = document.getElementById('grand_display_total');

    function calcGrandTotal() {
        let total = 0;
        allSumTargets.forEach(input => total += parseFloat(input.value) || 0);
        if(grandDisplay) grandDisplay.innerText = total.toFixed(2);
    }
    
    allSumTargets.forEach(input => input.addEventListener('input', calcGrandTotal));
</script>
</body>
</html><?php /**PATH C:\xampp\htdocs\marketing-app\resources\views/form.blade.php ENDPATH**/ ?>