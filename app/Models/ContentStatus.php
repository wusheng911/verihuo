<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentStatus extends Model
{
    // 已经发布
    const PUBLISHED = 1;

    // 编辑中
    const EDITING = 2;

    // 其他待讨论

}
