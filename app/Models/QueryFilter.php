<?php

namespace App\Models;

abstract class QueryFilter
{
    public function applyOrderBy($query, $field): void
    {
        $method = 'orderBy' . ucfirst($field);

        if (method_exists($this, $method)) {
            $this->$method($query, $field);
        } else {
            $query->orderBy($field, 'ASC');
        }
    }
}
