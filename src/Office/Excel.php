<?php

namespace Weigot\Tools\Office;

use PhpOffice\PhpSpreadsheet\IOFactory;

class Excel implements IExcel
{
    public static function write()
    {
        // TODO: Implement export() method.
    }

    /**
     * 读取数据
     * @param $file文件地址
     * @param string $ext文件后缀
     * @param int $offset
     * @return \Generator
     * @throws OfficeException
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public static function read($file, $ext = ExcelTypeEnum::XLSX, $offset = 1)
    {
        $reader = IOFactory::createReader($ext);
        $reader->setReadDataOnly(TRUE);
        if ($ext == ExcelTypeEnum::CSV) {
            $reader->setInputEncoding('GBK');
        }
        $spreadsheet = $reader->load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow(); // 总行数
        $lines = $highestRow - $offset;// 有效行数
        $highestColumn = $worksheet->getHighestColumn(); // 总列数
        if ($lines <= 0) {
            throw new OfficeException('表格中没有数据');
        }
        // 获取列数
        $num = 0;
        foreach (str_split($highestColumn) as $column) {
            $num *= 26;
            $num += (ord($column) - 64);
        }
        for ($row = $offset + 1; $row <= $highestRow; $row++) {
            $data = [];
            for ($i = 1; $i <= $num; $i++) {
                $data[] = $worksheet->getCellByColumnAndRow($i, $row)->getValue();
            }
            yield $data;
        }
    }
}