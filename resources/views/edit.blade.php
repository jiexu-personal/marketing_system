<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Marketing Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
<div class="container" style="max-width: 600px;">
    <div class="card shadow border-0 p-4">
        <h3 class="mb-4">Edit Record #{{ $activity->id }}</h3>
        <form action="/marketing/{{ $activity->id }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label fw-bold">Salesman</label>
                <input type="text" name="salesman" class="form-control" value="{{ $activity->salesman }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Outlet Name</label>
                <input type="text" name="outlet_name" class="form-control" value="{{ $activity->outlet_name }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Grand Total Claim (RM)</label>
                <input type="number" step="0.01" name="new_opening_amount" class="form-control" value="{{ $activity->grand_total_claim }}">
                <div class="form-text text-muted">修改主金额后会自动重新核算。</div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Update Record</button>
            <a href="/dashboard" class="btn btn-secondary w-100 py-2 mt-2">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>