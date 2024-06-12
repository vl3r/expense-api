<?php

declare(strict_types=1);

namespace App\Interfaces\Helpers;

final class QueryHelper
{
    public static function offset(int $page, int $limit): int
    {
        return ($page - 1) * $limit;
    }
}
