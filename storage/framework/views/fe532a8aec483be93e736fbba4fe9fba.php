<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application Details #<?php echo e($activity->id); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f2f2f7; font-family: -apple-system, sans-serif; }
        .ios-card { background: #fff; border-radius: 16px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
        .section-title { background: #f8f9fa; padding: 10px; border-left: 4px solid #007aff; font-weight: bold; margin-top: 20px; margin-bottom: 15px; }
    </style>
</head>
<body class="py-5">
<div class="container" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Record Details #<?php echo e($activity->id); ?></h3>
        <a href="/dashboard" class="btn btn-outline-secondary btn-sm rounded-pill px-3">← Back to Dashboard</a>
    </div>

    <div class="ios-card p-4">
        <h5 class="text-primary mb-3">Basic Information</h5>
        <p><strong>Outlet Name:</strong> <?php echo e($activity->outlet_name); ?></p>
        <p><strong>Account Code:</strong> <?php echo e($activity->account_code ?? '-'); ?></p>
        <p><strong>Salesman:</strong> <?php echo e($activity->salesman); ?></p>
        <p><strong>Apply Date:</strong> <?php echo e($activity->apply_date); ?></p>

        <div class="section-title">1) New Opening & 2) Listing Fee</div>
        <p><strong>New Opening Amount:</strong> RM <?php echo e(number_format($activity->new_opening_amount, 2)); ?></p>
        <p><strong>Listing Fee Total:</strong> RM <?php echo e(number_format($activity->listing_total_fee, 2)); ?> (<?php echo e($activity->listing_total_sku); ?> SKUs @ RM <?php echo e($activity->listing_fee_per_sku); ?>)</p>

        <div class="section-title">3) Rental Fee</div>
        <p><strong>Duration:</strong> <?php echo e($activity->rental_duration_from); ?> to <?php echo e($activity->rental_duration_to); ?></p>
        <p><strong>Gondola Full/Half:</strong> RM <?php echo e($activity->rental_gondola_full); ?> / RM <?php echo e($activity->rental_gondola_half); ?></p>
        <p><strong>Power Wing Full/Half:</strong> RM <?php echo e($activity->rental_power_wing_full); ?> / RM <?php echo e($activity->rental_power_wing_half); ?></p>
        <p><strong>Shelf Full/Half:</strong> RM <?php echo e($activity->rental_shelf_full); ?> / RM <?php echo e($activity->rental_shelf_half); ?></p>
        <p><strong>Standee / Block Island:</strong> RM <?php echo e($activity->rental_standee); ?> / RM <?php echo e($activity->rental_block_island); ?></p>

        <div class="section-title">4) Price Solve & 5) Sponsorships</div>
        <p><strong>Aged Stock (<?php echo e($activity->ps_aged_stock_product); ?>) Qty <?php echo e($activity->ps_aged_stock_qty); ?>:</strong> RM <?php echo e($activity->ps_aged_stock_total); ?></p>
        <p><strong>Mark Down (<?php echo e($activity->ps_markdown_product); ?>) Qty <?php echo e($activity->ps_markdown_qty); ?>:</strong> RM <?php echo e($activity->ps_markdown_total); ?></p>
        <p><strong>Sponsorships Total:</strong> RM <?php echo e(number_format($activity->sponsor_new_opening + $activity->sponsor_anniversary + $activity->sponsor_warehouse + $activity->sponsor_exhibition + $activity->sponsor_mailer + $activity->sponsor_others, 2)); ?></p>

        <div class="section-title">6) Order Amount & Grand Total</div>
        <p><strong>Walfood Brand Order:</strong> RM <?php echo e($activity->order_walfood_brand); ?></p>
        <p><strong>Other Brand Order:</strong> RM <?php echo e($activity->order_other_brand); ?></p>
        
        <div class="alert alert-danger text-end fs-4 mt-4">
            <strong>Grand Total Claim: RM <?php echo e(number_format($activity->grand_total_claim, 2)); ?></strong>
        </div>
		
		<!-- Purchase Invoice ID 管理区 -->
        <div class="section-title mt-4">Purchase Invoice ID Assignment</div>
        <div class="p-4 bg-light rounded-4 border mb-3">
            <?php if(session('success')): ?>
                <div class="alert alert-success fw-bold small"><?php echo e(session('success')); ?></div>
            <?php endif; ?>

            <?php if($activity->purchase_invoice_id): ?>
                <div class="alert alert-info py-2 mb-3 fw-bold">
                    Current Invoice ID: <span class="text-dark"><?php echo e($activity->purchase_invoice_id); ?></span>
                </div>
            <?php endif; ?>

            <form action="/marketing/<?php echo e($activity->id); ?>/invoice" method="POST">
                <?php echo csrf_field(); ?>
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small fw-bold">Select Year & Month</label>
                        <input type="date" name="invoice_date" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">4-Digit Number (e.g. 0001)</label>
                        <input type="text" name="invoice_custom_no" class="form-control form-control-sm" maxlength="4" placeholder="0001" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-primary btn-sm w-100 fw-bold">Save Invoice ID</button>
                    </div>
                </div>
            </form>
        </div>
    
		<!-- Sales Management Approval 审批专区 -->
        <div class="section-title mt-4">Sales Management Approval</div>
        
        <!-- 把提示信息加回来 -->
        <?php if(session('success')): ?>
            <div class="alert alert-success fw-bold small"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger fw-bold small"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <?php if($activity->approval_status === 'Approved'): ?>
            <div class="p-3 bg-success bg-opacity-10 border border-success rounded-4 text-success">
                <h5 class="fw-bold mb-1">✔ Approved by Sales Management</h5>
                <p class="mb-0 small">Manager: <strong><?php echo e($activity->approved_by); ?></strong></p>
                <p class="mb-0 small text-muted">Signed at: <?php echo e($activity->approved_at); ?></p>
            </div>
        <?php else: ?>
            <div class="p-4 bg-light rounded-4 border">
                <p class="small text-secondary mb-3">🔒 Restricted Area: Authorized Sales Management signature required.</p>
                
                <form action="/marketing/<?php echo e($activity->id); ?>/approve" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Manager Name</label>
                        <input type="text" name="manager_name" class="form-control form-control-sm" placeholder="Enter your name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Authorization Password</label>
                        <input type="password" name="manager_password" class="form-control form-control-sm" placeholder="Enter secure password" required>
                    </div>
                    <button type="submit" class="btn btn-dark btn-sm w-100 fw-bold py-2">Approve & Sign</button>
                </form>
            </div>
        <?php endif; ?>
	
	</div>
</div>
</body>
</html><?php /**PATH C:\xampp\htdocs\marketing-app\resources\views/details.blade.php ENDPATH**/ ?>