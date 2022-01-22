<?php

namespace app\common\lib;

use PhpOffice\PhpSpreadsheet\Chart\Axis;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\GridLines;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Excel
{
    /**
     * Excel导出
     *
     * @param array $datas 导出数据，格式['A1' => 'XXXX公司报表', 'B1' => '序号']
     * @param string $fileName 导出文件名称
     * @param string $format 导出文件类型
     * @param array $options 操作选项，例如：
     *                           bool   print       设置打印格式
     *                           string freezePane  锁定行数，例如表头为第一行，则锁定表头输入A2
     *                           array  setARGB     设置背景色，例如['A1', 'C1']
     *                           array  setWidth    设置宽度，例如['A' => 30, 'C' => 20]
     *                           bool   setBorder   设置单元格边框
     *                           array  mergeCells  设置合并单元格，例如['A1:J1' => 'A1:J1']
     *                           array  formula     设置公式，例如['F2' => '=IF(D2>0,E42/D2,0)']
     *                           array  format      设置格式，整列设置，例如['A' => 'General']
     *                           array  alignCenter 设置居中样式，例如['A1', 'A2']
     *                           array  bold        设置加粗样式，例如['A1', 'A2']
     *                           string savePath    保存路径，设置后则文件保存到服务器，不通过浏览器下载
     * @param array $options = [
     *                           [
     *                           'column' =>'字段名',
     *                           'name' => '列名',
     *                           'width' => '列宽度',
     *                           'height' => '行高',
     *                           'color' => '颜色',
     *                           'size' => '字体大小',
     *                           'font' => '字体',
     *                           'image' => '是否是图片',
     *                           'border' => '边框',
     *                           'dataType' => 'n数字 ',
     *                           ]
     *
     * ]
     * @return bool
     */
    public function exportSheelExcel($datas, $options = [], $fileName = '', $format = 'Xlsx', $type = 0)
    {
        set_time_limit(0);
        //初始化
        $spreadsheet = new Spreadsheet();
        // $fileName    = iconv('utf-8', 'gb2312', $fileName);//文件名称
        //设置标题
        $spreadsheet->getActiveSheet()->setTitle($fileName);
        $filename = $fileName . '_' . date('YmdHis');
        $cellNum = count($options);

        /* 设置默认文字居中 */
        $styleArray = [
            'alignment' => [
                'horizontal' => 'left',
                'vertical' => 'left',
            ],
        ];
        $spreadsheet->getDefaultStyle()->applyFromArray($styleArray);
        /* 设置Excel Sheet */
        $spreadsheet->setActiveSheetIndex(0);
        $cellName = [
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z',
            'AA',
            'AB',
            'AC',
            'AD',
            'AE',
            'AF',
            'AG',
            'AH',
            'AI',
            'AJ',
            'AK',
            'AL',
            'AM',
            'AN',
            'AO',
            'AP',
            'AQ',
            'AR',
            'AS',
            'AT',
            'AU',
            'AV',
            'AW',
            'AX',
            'AY',
            'AZ'
        ];

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', $fileName);
        //设置行高
        $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(30);
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setName('Arial')->setSize(20);
        ;
        //设置行高
        $spreadsheet->getActiveSheet()->getRowDimension('A1')->setRowHeight(30);
        //合并单元格
        $spreadsheet->getActiveSheet()->mergeCells('A1:' . $cellName[$cellNum - 1] . '1');
        //默认水平居中
        $styleArray = [
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
        $color = [
            'Black' => 'FF000000',
            'White' => 'FFFFFFFF',
            'Red' => 'FFFF0000',
            'Red1' => 'FF800000',//COLOR_DARKRED
            'Green' => 'FF00FF00',
            'Green1' => 'FF008000',//COLOR_DARKGREEN
            'Blue' => 'FF0000FF',
            'Blue1' => 'FF000080',//COLOR_DARKBLUE
            'Yellow' => 'FFFFFF00',
            'Yellow1' => 'FF808000',//COLOR_DARKYELLOW
        ];

        //设置excel第2行数据
        foreach ($options as $key => $val) {
            $column = $cellName[$key] . '2';
            //设置表头
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue($column, $val['name']);
            //设置列宽
            if (isset($val['width']) && !empty($val['width'])) {
                $spreadsheet->getActiveSheet()->getColumnDimension($cellName[$key])->setWidth($val['width']);
            } else {
                $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);//设置默认列宽为
            }
            //设置字体 粗体
            $spreadsheet->getActiveSheet()->getStyle($column)->getFont()->setBold(true);
            //设置行高
            if (!empty($val['height'])) {
                $spreadsheet->getActiveSheet()->getRowDimension($column)->setRowHeight($val['height']);
                //设置默认行高 $spreadsheet->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);
            }
            //设置颜色
            if (!empty($val['color']) && isset($color[$val['color']])) {
                $spreadsheet->getActiveSheet()->getStyle($column)->getFont()->getColor()->setARGB($color[$val['color']]);
            } else {
                $spreadsheet->getActiveSheet()->getStyle($column)->getFont()->getColor()->setARGB('FF000000');
            }
        }
        $yieldData = $this->yieldData($datas);
        $i = 0;
        foreach ($yieldData as $val) {
            for ($j = 0; $j < $cellNum; $j++) {
                //$spreadsheet->setActiveSheetIndex(0)->setCellValue($cellName[$j].($i+3),' '.$val[$options[$j]['column']].' ');

                //数据类型
                $dataType = isset($options[$j]['dataType']) ? $options[$j]['dataType'] : 's';
                switch ($dataType) {
                    case 'n'://数字
                        $spreadsheet->setActiveSheetIndex(0)->setCellValueExplicit($cellName[$j] . ($i + 3), $val[$options[$j]['column']], $dataType);
                        break;
                    case 'str2num':
                        $spreadsheet->setActiveSheetIndex(0)->setCellValueExplicit($cellName[$j] . ($i + 3), $val[$options[$j]['column']], $dataType);
                        break;
                    case 'str':
                        $spreadsheet->setActiveSheetIndex(0)->setCellValueExplicit($cellName[$j] . ($i + 3), $val[$options[$j]['column']], $dataType);
                        break;
                    case 's':
                    case 'inlineStr':
                        $spreadsheet->setActiveSheetIndex(0)->setCellValueExplicit($cellName[$j] . ($i + 3), $val[$options[$j]['column']], $dataType);
                        break;
                    case 'null':
                        $spreadsheet->setActiveSheetIndex(0)->setCellValueExplicit($cellName[$j] . ($i + 3), $val[$options[$j]['column']], $dataType);
                        break;
                    case 'f':
                    default:
                        $spreadsheet->setActiveSheetIndex(0)->setCellValueExplicit($cellName[$j] . ($i + 3), $val[$options[$j]['column']], $dataType);
                        break;
                }
            }
            $i++;
        }
        header('pragma:public');
        if ($format == 'Xlsx') {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        } elseif ($format == 'Xls') {
            header('Content-Type: application/vnd.ms-excel');
        }
        // type等于1直接下载
        if ($type) {
            $objWriter = new Xlsx($spreadsheet);
            $objWriter->setPreCalculateFormulas(false);
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=" . $filename . '.' . strtolower($format));
            header('Cache-Control: max-age=0');//禁止缓存
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-Transfer-Encoding:binary");
            header("Expires: 0");
            ob_clean();
            ob_start();
            $objWriter->save('php://output');
            /* 释放内存 */
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
            ob_end_flush();
            return true;
        } else {
            $objWriter = IOFactory::createWriter($spreadsheet, $format);
            $rootPath = app()->getRootPath();
            $savePath = '/upload' . '/exportExcel/' . $filename . '.' . strtolower($format);
            $a = $rootPath . 'public' . $savePath;
            $objWriter->save($a);
            /* 释放内存 */
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
            ob_end_flush();
            return $savePath;
        }
    }


    public function yieldData($data)
    {
        foreach ($data as $val) {
            yield $val;
        }
    }

    //导入
    public function importExcel()
    {
        set_time_limit(0);
        //文件上传导入
        // $fileController=new FileController();
        $res = self::uploadFileImport();
        if ($res['code']) {
            $data = $res['data'];
            //修正路径
            $filename = 'upload/' . str_replace('\\', '/', $data);
            //进行读取
            $spreadsheet = IOFactory::load($filename);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            array_shift($sheetData);  //删除标题;
            array_shift($sheetData);  //删除标题;
            return $sheetData;
        } else {
            return '';
        }
    }

    //文件上传,导入文件专用,数据不入库
    public function uploadFileImport()
    {
        // 获取表单上传文件
        $file = \request()->file('file');
        $return = array('status' => 1, 'info' => '上传成功', 'data' => []);
        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $savename = \think\facade\Filesystem::disk('public')->putFile('importExcel', $file);
            return self::setResAr(1, '上传成功', $savename);
        }
        return self::setResAr(0, '上传失败');
    }

    public function setResAr($code = 0, $msg = '', $data = array())
    {
        return ['code' => $code, 'msg' => $msg, 'data' => $data];
    }
    
    /**
     * 柱状图
     * @param array $data
     * @param string $fileName
     * @param string $startCall
     * @param string $topTitle
     * @param string $cartTitle
     * @param string $sheet
     * @param string $chart
     * @param int $row
     * @param string $topLeftPosition
     * @param string $bottomRightPosition
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function barSheet($data=[], $fileName='bar', $startCall='B15', $topTitle='', $cartTitle='投票数', $sheet='Q1', $chart='chart1', $row=2, $topLeftPosition='A3', $bottomRightPosition='H14')
    {
        if (empty($data)) {
            $data= [
                ['', '2010'],
                ['Q1', 12],
                ['Q2', 56],
                ['Q3', 52],
                ['Q4', 30],
            ];
            $row = 2;
            $sheet='Q1';
        }
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet()->mergeCells('A1:H1');
        $worksheet->getRowDimension('1')->setRowHeight(20);
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'D3D5F5',
                ],
                'endColor' => [
                    'argb' => '8389E0',
                ],
            ],
        ];
        $worksheet->getStyle('A1')->applyFromArray($styleArray);
        $worksheet ->setCellValue('A1', $topTitle);
        
        $worksheet ->setTitle($sheet);
//        $worksheet1 = $spreadsheet->createSheet();
//        $worksheet1->setTitle('Another sheet');
//        如果您有一个简单的1-d数组，并希望将其写为一列，则以下内容会将其转换为结构正确的2-d数组
//        $columnArray = array_chunk($data2, 1);
        $worksheet->fromArray($data, null, $startCall) ;
        //->fromArray( $columnArray, NULL, 'A4' );
        
        
        $j = substr($startCall, 1);
        
        $topLabel = ['A','B','C','D','E','F','G','H','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        
        $pointCount = count($data)-1;
        
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THICK,
                    'color' => ['argb' => '808080'],
                ],
            ],
        ];
        
//        $worksheet->getStyle("B15:C19")->applyFromArray($styleArray);
        
        // Set the Labels for each data series we want to plot
        $dataSeriesLabels1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheet.'!$'.$topLabel[$row-1].'$' .($j), null, 1),
        ];
        
        // Set the X-Axis Labels
        $xAxisTickValues1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheet.'!$'.$topLabel[$row].'$'.($j+1).':$'.$topLabel[$row-1].'$' .($pointCount+$j), null, $pointCount),
            // Q1 to Q4
        ];
        
        // Set the Data values for each data series we want to plot
        $dataSeriesValues1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $sheet.'!$'.$topLabel[$row].'$'.($j+1).':$' .$topLabel[$row].'$'.($pointCount+$j), null, $pointCount),
        ];
        
        // Build the dataseries
        $series1 =
            new DataSeries(
                DataSeries::TYPE_BARCHART,  // plotType
                DataSeries::GROUPING_STACKED,                   // plotGrouping (Pie charts don't have any grouping)
                range(0, count($dataSeriesValues1) - 1), // plotOrder
                $dataSeriesLabels1, // plotLabel
                $xAxisTickValues1, // plotCategory
                $dataSeriesValues1 // plotValues
            );
        // Set additional dataseries parameters
        //     Make it a horizontal bar rather than a vertical column graph
        $series1->setPlotDirection(DataSeries::DIRECTION_BAR);
        
        
        // Set the series in the plot area
        $plotArea1 = new PlotArea(null, [$series1]);
        
        // Set the chart legend
        $legend1 = new Legend(Legend::POSITION_RIGHT, null, false);
        
        $title1 = new Title($cartTitle);
        
        $yAxisLabel = new Title('Value ($k)');
        
        // Create the chart
        $chart1 = new Chart(
            $chart, // name
            $title1, // title
            null,
//            $legend1, // legend
            $plotArea1, // plotArea
            true, // plotVisibleOnly
            'gap', // displayBlanksAs
            null, // xAxisLabel
            null   // yAxisLabel - Pie charts don't have a Y-Axis
//            $yAxisLabel
        );
        
        // Set the position where the chart should appear in the worksheet
        $chart1->setTopLeftPosition($topLeftPosition);
        $chart1->setBottomRightPosition($bottomRightPosition);
        
        // Add the chart to the worksheet
        $worksheet->addChart($chart1);
        
        // Save Excel 2007 file
        //		self::export($spreadsheet,$fileName,true);
        //		$spreadsheet->disconnectWorksheets();
        //		unset($spreadsheet);
        //		exit;
        
        //文件名
        $fileName='柱状图_';
        $fileName =  $fileName.date('YmdHis');
        $fileType='Xlsx';
        $writer = IOFactory::createWriter($spreadsheet, $fileType);
        $writer->setIncludeCharts(true);
        
//        if ($fileType == 'Excel2007' || $fileType == 'Xlsx') {
//            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//            header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
//            header('Cache-Control: max-age=0');
//        } else { //Excel5
//            header('Content-Type: application/vnd.ms-excel');
//            header('Content-Disposition: attachment;filename="'.$fileName.'.xls"');
//            header('Cache-Control: max-age=0');
//        }
//        //清空缓存区
//        ob_end_clean();
//        //输出到表格
//        $writer->save('php://output');
//
//        $spreadsheet->disconnectWorksheets();
//        unset($spreadsheet);
//        exit;
    
        $format = 'Xlsx';
        header('pragma:public');
        if ($format == 'Xlsx') {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        } elseif ($format == 'Xls') {
            header('Content-Type: application/vnd.ms-excel');
        }
        
        $rootPath = app()->getRootPath();
        $savePath = '/upload' . '/exportExcel/' . $fileName . '.' . strtolower($format);
        $a = $rootPath . 'public' . $savePath;
        $writer->save($a);
        /* 释放内存 */
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
        ob_end_flush();
        return $savePath;
    }
    
    /**
     * 饼状图(大)
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function line()
    {
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $data=[
            ['', '2010', '2011', '2012'],
            ['Q1', 12, 15, 21],
            ['Q2', 56, 73, 86],
            ['Q3', 52, 61, 69],
            ['Q4', 30, 32, 60],
        ];
        // fetch data into array and put at A1
        $worksheet->fromArray($data, null, "A1", true);

        // set the type of data to numeric
        $worksheet->getStyle("A1:D".sizeof(array_keys($data)))->getNumberFormat()->setFormatCode('0');

        // set data series label
        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$1', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$1', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$D$1', null, 1),
        ];

        // set x axis tick values
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$2:$A$5', null, 4),
        ];

        // set data series values
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$2:$B$5', null, 4),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$2:$C$5', null, 4),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$D$2:$D$5', null, 4),
        ];

        // set the series
        $series = new DataSeries(
//            DataSeries::TYPE_LINECHART, // plotType   line
            DataSeries::TYPE_PIECHART, // plotType
            null,   //pie is null
//            DataSeries::GROUPING_STANDARD, // plotGrouping
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues        // plotValues
        );

        // Set the series in the plot area
        $layout1 = new Layout();
        $layout1->setShowVal(true);
        $layout1->setShowPercent(true);
        $plotArea = new PlotArea($layout1, [$series]);

        // Set the chart legend
        $legend = new Legend(Legend::POSITION_BOTTOM, null, false);
        
        $title = new Title('Trend Line Chart');
        $yaxis = new Axis();
        $xaxis = new Axis();
        $yaxis->setAxisOptionsProperties('none', null, null, null, null, null, null, null);
        $xaxis->setAxisOptionsProperties('low', null, null, null, null, null, 0, 0, null, null);
        $grid = new GridLines();
        $grid->setLineColorProperties("white");

        // Create the chart
        $chart = new Chart(
            'chart1', // name
            $title, // title
            $legend, // legend
            $plotArea, // plotArea
            true, // plotVisibleOnly
            'gap', // displayBlanksAs
            null, // xAxisLabel
            null, // yAxisLabel
            $yaxis,
            $xaxis,
            $grid
        );

        // Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition('B12');
        $chart->setBottomRightPosition('N30');

        // Add the chart to the worksheet
        $spreadsheet->getActiveSheet()->addChart($chart);
        $filename='line_';
        $fileType='Xlsx';
        $writer = IOFactory::createWriter($spreadsheet, $fileType);

//        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);
        
        //文件名
        $filename =  $filename.date('YmdHis');
        if ($fileType == 'Excel2007' || $fileType == 'Xlsx') {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
            header('Cache-Control: max-age=0');
        } else { //Excel5
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
            header('Cache-Control: max-age=0');
        }
        //清空缓存区
        ob_end_clean();
        //输出到表格
        $writer->save('php://output');
        
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
        exit;
    }
    
    /**
     * 饼状图
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function pie()
    {
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->fromArray(
            [
                ['', '2010', '2011', '2012'],
                ['Q1', 12, 15, 21],
                ['Q2', 56, 73, 86],
                ['Q3', 52, 61, 69],
                ['Q4', 30, 32, 60],
            ]
        );
        
        // Set the Labels for each data series we want to plot
        $dataSeriesLabels1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$1', null, 1), // 2011
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$1', null, 1), // 2012
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$D$1', null, 1), // 2013
        ];
        
        // Set the X-Axis Labels
        $xAxisTickValues1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$2:$A$5', null, 4), // Q1 to Q4
        ];
        
        // Set the Data values for each data series we want to plot
        $dataSeriesValues1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$2:$B$5', null, 4),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$2:$C$5', null, 4),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$D$2:$D$5', null, 4),
        ];
        
        // Build the dataseries
        $series1 = [
            new DataSeries(
                DataSeries::TYPE_PIECHART, // plotType
                null, // plotGrouping (Pie charts don't have any grouping)
                range(0, count($dataSeriesValues1) - 1), // plotOrder
                $dataSeriesLabels1, // plotLabel
                $xAxisTickValues1, // plotCategory
                $dataSeriesValues1 // plotValues
            )
        ];
        
        // Set up a layout object for the Pie chart
        $layout1 = new Layout();
        $layout1->setShowVal(true);
        $layout1->setShowPercent(true);
        
        // Set the series in the plot area
        $plotArea1 = new PlotArea($layout1, $series1);
        
        // Set the chart legend
        $legend1 = new Legend(Legend::POSITION_RIGHT, null, false);
        
        $title1 = new Title('Test Pie Chart');
        
        $yAxisLabel = new Title('Value ($k)');
        // Create the chart
        $chart1 = new Chart(
            'chart1', // name
            $title1, // title
            $legend1, // legend
            $plotArea1, // plotArea
            true, // plotVisibleOnly
            'gap', // displayBlanksAs
            null, // xAxisLabel
//            null   // yAxisLabel - Pie charts don't have a Y-Axis
            $yAxisLabel
        );
        
        // Set the position where the chart should appear in the worksheet
        $chart1->setTopLeftPosition('A7');
        $chart1->setBottomRightPosition('H20');
        
        // Add the chart to the worksheet
        $worksheet->addChart($chart1);
        
        // Save Excel 2007 file
        $filename='pie_';
        $fileType='Xlsx';
        $writer = IOFactory::createWriter($spreadsheet, $fileType);
        $writer->setIncludeCharts(true);
        
        //文件名
        $filename =  $filename.date('YmdHis');
        if ($fileType == 'Excel2007' || $fileType == 'Xlsx') {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
            header('Cache-Control: max-age=0');
        } else { //Excel5
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
            header('Cache-Control: max-age=0');
        }
        //清空缓存区
        ob_end_clean();
        //输出到表格
        $writer->save('php://output');
        
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
        exit;
    }
}
