<?php
declare(strict_types=1);

namespace QueryBuilder\Expression;

interface ParamsInterface 
{
    /**
     * @return array
     */
    public function getParams();
}
