<?php 

$dir_export_file = __DIR__ . '/files/export/';
$file_name_new = 'data-set_' . time() . '.xls';

// Delete old files (30 min ago)
deleteOldFiles($dir_export_file, 30);

// Functions for Export
function exportTableDataset($data, $file_name, $dir_export_file){
    
    require_once ('PHPExcel/Classes/PHPExcel.php');
    require_once ('PHPExcel/Classes/PHPExcel/Writer/Excel2007.php');

    $xls = new PHPExcel();
    
    $xls->setActiveSheetIndex(0);
    $sheet = createSheetTable1($xls, $data[0]);
    
    $xls->createSheet();
    $xls->setActiveSheetIndex(1);
    $sheet = createSheetTable1($xls, $data[1]);

    $xls->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save($dir_export_file . $file_name);

    
    return $file_name;
}

function deleteOldFiles($dir_path, $n_minute_ago = 1){
    $file_name_one_day_ago = 'data-set_' . (time() - $n_minute_ago * 60) . '.xls';
    
    $export_files = scandir($dir_path);
    unset($export_files[0]);
    unset($export_files[1]);
    
    foreach($export_files as $file_name){
        if($file_name < $file_name_one_day_ago){
            unlink($dir_path . $file_name);
        }
    }
}

function createSheetTable1($xls, $data){
    
    $table = $data['data'];
    $colspan = $data['colspan'] - 1;
    $title = $data['title'];
    
    // Get style
    $style_center = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            'wrap' => true,
        ),
        'fill' => array(
            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
            'color'   => array('rgb' => 'ccdaf0')
        ),
        'borders' => array(
            'top' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array(
                '	rgb' => '808080'
                )
            ),
            'left' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array(
                '	rgb' => '808080'
                )
            ),
            'bottom' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array(
                '	rgb' => '808080'
                )
            ),
            'right' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array(
                    'rgb' => '808080'
                )
            )
        ),
        'numberformat ' => array(
            'code' => PHPExcel_Style_NumberFormat::FORMAT_TEXT,
        ),
    );
    $style_data = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        ),
        'numberformat ' => array(
            'code' => PHPExcel_Style_NumberFormat::FORMAT_TEXT,
        ),
    );
    
    // Get Sheet
    $sheet = $xls->getActiveSheet();
    $sheet->setTitle($title);
    
    // Set MERGE
    $sheet->mergeCells('A1:A2');
    
    for($n_col = 0; $n_col < count($table[0]); $n_col++){
        if($table[0][$n_col] != '' and $n_col > 0){
            $merge = PHPExcel_Cell::stringFromColumnIndex($n_col) . '1:' . PHPExcel_Cell::stringFromColumnIndex($n_col + $colspan) . '1';
            $sheet->mergeCells($merge);
            $sheet->getStyle($merge)->applyFromArray($style_center);
        }
        $sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($n_col))->setAutoSize(true);
        
        $merge = PHPExcel_Cell::stringFromColumnIndex($n_col) . '2:' . PHPExcel_Cell::stringFromColumnIndex($n_col) . '2';
        $sheet->getStyle($merge)->applyFromArray($style_center);
    }
    
    // Style THEAD
    $merge = 'A1:' . PHPExcel_Cell::stringFromColumnIndex(count($table[0]) - 1) . '2';
    $sheet->getStyle($merge)->applyFromArray($style_center);
    
    // Style TBODY
    $merge = 'B3:' . PHPExcel_Cell::stringFromColumnIndex(count($table[0]) - 1) . count($table);
    $sheet->getStyle($merge)->applyFromArray($style_data);
    
    $sheet->getRowDimension(1)->setRowHeight(50);
    
    
    // Add DATA
    $table[0][0] = $table[1][0]; // Add country title
    $xls->getActiveSheet()->fromArray(
        $table,
        NULL,
        'A1'
    );
    
    return $sheet;
}

?>