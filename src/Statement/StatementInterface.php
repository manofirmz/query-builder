<?php
declare(strict_types=1);

namespace QueryBuilder\Statement;

interface StatementInterface
{
    /**
     * @return string
     */
    public function getStatement();

    /**
     * @return array
     */
    public function getParams();
}
