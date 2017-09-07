<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class OrderTransactionStatus extends Model
{
    const Completed = 2;

    //临时
    const Preparing = 9;
}