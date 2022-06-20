<?php

namespace Weigot\Tools\Office;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\BaseWriter;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Html;
use PhpOffice\PhpSpreadsheet\Writer\Ods;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Weigot\Tools\Exception\OfficeException;

class Excel implements IExcel
{
    /**
     * 生成文件
     * @param $data
     * @param array $fields
     * @param string $fileName
     * @param string $ext
     * @param int $output
     * @throws OfficeException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function write($data, $fields = [], $fileName = "文件", $ext = ExcelTypeEnum::XLSX, $output = 1)
    {
        set_time_limit(0);
        $spreadsheet = new Spreadsheet(); // 创建新表格
        $spreadsheet->createSheet(); // 创建sheet
        $sheet = $spreadsheet->getActiveSheet(); // 获取当前sheet
        $j = 1;
        foreach ($fields as $key => $field) {
            $sheet->setCellValueByColumnAndRow($j, 1, $field);
            $sheet->getStyleByColumnAndRow($j, 1)->getFont()->setBold(true);
            $sheet->getColumnDimensionByColumn($j)->setWidth(strlen($field));// 设置列宽
            $j++;
        }
        foreach ($data as $k => $content) {
            $j = 1;
            if (empty($content)) {
                continue;
            }
            foreach ($fields as $key => $field) {
                $sheet->getCellByColumnAndRow($j, $k + 2)
                    ->setValueExplicit($content[$key], DataType::TYPE_STRING);
                $len = $sheet->getColumnDimensionByColumn($j)->getWidth();// 获取宽度
                $vLen = strlen((string)$content[$key]) + 2;
                if ($len < $vLen) {
                    $sheet->getColumnDimensionByColumn($j)->setWidth($vLen);// 设置宽度
                }
                $j++;
            }
        }
        /** @var BaseWriter $writer */
        switch (strtolower($ext)) {
            default:
            case strtolower(ExcelTypeEnum::XLSX):
                $writer = new Xlsx($spreadsheet);
                break;
            case strtolower(ExcelTypeEnum::CSV):
                $writer = new Csv($spreadsheet);
                break;
            case strtolower(ExcelTypeEnum::XLS):
                $writer = new Xls($spreadsheet);
                break;
            case strtolower(ExcelTypeEnum::HTML):
                $writer = new Html($spreadsheet);
                break;
            case strtolower(ExcelTypeEnum::ODS):
                $writer = new Ods($spreadsheet);
                break;
        }
        if ($output) { // 浏览器下载
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//浏览器输出07Excel文件
            header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');//浏览器输出浏览器名称
            header('Cache-Control: max-age=0'); //禁止缓存
            header('Access-Control-Allow-Methods:GET, POST, PUT, DELETE, HEAD, OPTIONS');
            header('Access-Control-Allow-Origin:*');
            $_savePath = "php://output";
        } else { // 下载到本地服务器
            $_fileName = iconv("utf-8", "gb2312", $fileName);//转码
            $_savePath = $_fileName . "." . strtolower($ext);
        }
        $writer->save($_savePath);
        exit();
    }

    /**
     * 读取数据
     * @param $file
     * @param string $ext
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