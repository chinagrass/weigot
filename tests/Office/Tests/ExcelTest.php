<?php

namespace Office\Tests;

use PHPUnit\Framework\TestCase;
use Weigot\Tools\Office\Excel;
use Weigot\Tools\Office\ExcelTypeEnum;

class ExcelTest extends TestCase
{
    public function testWrite()
    {
        $field = [
            "field1",
            "field2",
            "field3",
            "field4",
        ];
        function data($field)
        {
            for ($i = 0; $i < 10; $i++) {
                $data = [];
                foreach ($field as $v) {
                    $data[] = $v . "_" . $i;
                }
                yield $data;
            }
        }

        $data = data($field);
        try {
            Excel::write($data, $field, './test', ExcelTypeEnum::XLSX, 0);
        } catch (\Throwable $e) {
            $e->getMessage();
        }

    }
}