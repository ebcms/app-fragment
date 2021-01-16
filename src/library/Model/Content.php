<?php

declare(strict_types=1);

namespace App\Ebcms\Fragment\Model;

use Ebcms\Database\Model;

class Content extends Model
{
    public function getTable(): string
    {
        return 'ebcms_fragment_content';
    }
}
