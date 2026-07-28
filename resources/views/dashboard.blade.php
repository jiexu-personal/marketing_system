<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walfood iOS Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f2f2f7; font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", sans-serif; color: #1c1c1e; }
        
        .ios-navbar { 
            background: rgba(255, 255, 255, 0.7) !important; 
            backdrop-filter: blur(15px); 
            -webkit-backdrop-filter: blur(15px);
            position: sticky; 
            top: 0; 
            z-index: 1000; 
            border-bottom: 1px solid rgba(0,0,0,0.05); 
        }

        .ios-card { 
            background: #ffffff; 
            border-radius: 16px; 
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); 
            border: none; 
            overflow: hidden; 
        }

        .page-header { 
            font-weight: 700; 
            font-size: 1.75rem; 
            letter-spacing: -1px; 
        }

        .btn-ios { 
            background-color: #007aff; 
            color: white; 
            border-radius: 20px; 
            font-weight: 600; 
            padding: 6px 18px; 
            border: none; 
        }
        .btn-ios:hover { background-color: #005bb5; color: white; }

        .table thead th { background: #fff; color: #8e8e93; font-weight: 500; font-size: 0.8rem; border-bottom: 1px solid #e5e5ea; padding: 12px; }
        .table tbody td { padding: 12px; vertical-align: middle; border-bottom: 1px solid #e5e5ea; font-size: 0.85rem; }

        .text-ios-blue { color: #007aff; font-weight: 500; }
        .text-ios-red { color: #ff3b30; font-weight: 600; }
        .text-ios-gray { color: #8e8e93; }

        /* --- 手机端响应式魔法适配 --- */
        @media (max-width: 768px) {
            .page-header { font-size: 1.4rem; }
            .container { padding-left: 10px; padding-right: 10px; }
            
            /* 在小屏幕手机上，把传统的横向大表格伪装成卡片流，防止排版挤压 */
            .table-responsive { border: none; }
            
            /* 让表格文字在手机上更紧凑 */
            .table tbody td, .table thead th {
                padding: 10px 8px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>

<nav class="ios-navbar py-2 px-3">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <a href="/" class="btn btn-outline-secondary btn-sm rounded-pill px-3" style="font-size: 0.8rem;">← Form</a>
        <span class="fw-bold small">Walfood Admin</span>
        
        <div class="form-check form-switch m-0" style="transform: scale(0.9);">
            <input class="form-check-input" type="checkbox" id="autoRefreshSwitch" checked>
            <label class="form-check-label small fw-bold" for="autoRefreshSwitch" style="font-size: 0.75rem;">Auto (5s)</label>
        </div>
    </div>
</nav>

<div class="container mt-3 mb-5" style="max-width: 1300px;">
    <div class="d-flex justify-content-between align-items-end mb-3 px-1">
        <h1 class="page-header m-0">Applications</h1>
        <a href="/export-marketing-data" class="btn btn-ios btn-sm text-decoration-none">Export Data</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success fw-bold small">{{ session('success') }}</div>
    @endif

    <div class="ios-card">
        <div class="table-responsive">
            <table class="table table-hover table-borderless align-middle mb-0">
                <thead>
                    <tr>
                        <th class="px-3">ID</th>
                        <th>Date</th>
                        <th>Salesman</th>
                        <th>Outlet</th>
                        <th class="text-end">Total (RM)</th>
                        <th class="text-center px-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activities as $activity)
                    <tr>
                        <td class="px-3 text-ios-gray">#{{ $activity->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($activity->apply_date)->format('M d') }}</td>
                        <td class="text-ios-blue text-truncate" style="max-width: 90px;">{{ $activity->salesman }}</td>
                        <td class="fw-medium text-truncate" style="max-width: 100px;">{{ $activity->outlet_name }}</td>
                        <td class="text-ios-red text-end fw-bold">{{ number_format($activity->grand_total_claim, 2) }}</td>
                        <td class="text-center px-3 text-nowrap">
                            <a href="/marketing/{{ $activity->id }}/details" class="btn btn-sm btn-light border rounded-pill px-2 text-secondary" style="font-size: 0.75rem;">Details</a>
                            <a href="/marketing/{{ $activity->id }}/edit" class="btn btn-sm btn-light border rounded-pill px-2 text-primary" style="font-size: 0.75rem;">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($activities->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center py-5 text-ios-gray">No marketing forms submitted yet.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let refreshTimer = null;
    const toggleSwitch = document.getElementById('autoRefreshSwitch');

    function startAutoRefresh() {
        refreshTimer = setInterval(() => {
            if (toggleSwitch.checked) {
                location.reload();
            }
        }, 5000);
    }

    toggleSwitch.addEventListener('change', function() {
        if (this.checked) {
            startAutoRefresh();
        } else {
            clearInterval(refreshTimer);
        }
    });

    startAutoRefresh();
</script>
</body>
</html>