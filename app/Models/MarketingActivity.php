<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingActivity extends Model
{
    // 解除写入限制，允许我们把表单数据一次性存入数据库
    protected $guarded = [];
}