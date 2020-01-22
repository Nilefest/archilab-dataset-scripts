<?php 
require_once ('functions.php');

// Data DB refresh by Excel
// Set '1' for start script/ Set '0' for stop script
if(1){ 
    deleteTable($db, $table_name);
    createTableFilterCountry($db, $table_name);
    $table_excel = getDataFilterCountry($file_path_data_set_all_data);
    $db->query("DELETE FROM $table_name");
    insertFilterTableAll($db, $table_name, $table_excel);
    
    echo "DATA UPDATE: <hr><pre>";
    print_r($table_excel);
}

function deleteTable($db, $table_name){
    $db->query("DROP TABLE `filter_country`");
}

function createTableFilterCountry($db, $table_name){
    $sql = "CREATE TABLE `filter_country` (`id` int(11) NOT NULL,`year` int(11) NOT NULL,`num` int(11) NOT NULL,`country_ru` varchar(250) NOT NULL,`country_en` varchar(250) NOT NULL,`country_html_id` varchar(250) NOT NULL,`digital_ci` varchar(250) NOT NULL DEFAULT '0',`global_ci` varchar(250) NOT NULL DEFAULT '0',`innovation_i` varchar(250) NOT NULL DEFAULT '0',`human_di` varchar(250) NOT NULL DEFAULT '0',`gdp` varchar(250) NOT NULL DEFAULT '0',`eg_rate` varchar(250) NOT NULL DEFAULT '0',`gdp_person` varchar(250) NOT NULL DEFAULT '0',`quality_l` varchar(250) NOT NULL DEFAULT '0',`happy_i` varchar(250) NOT NULL DEFAULT '0',`solid_gi` varchar(250) NOT NULL DEFAULT '0') ENGINE=InnoDB DEFAULT CHARSET=utf8;ALTER TABLE `filter_country`ADD PRIMARY KEY (`id`);ALTER TABLE `filter_country`MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;";
    $db->query($sql);
}

function getDataFilterCountry($file_path){
    $table = [];

    require_once ('PHPExcel/Classes/PHPExcel/IOFactory.php');

    $xls = PHPExcel_IOFactory::load($file_path);
    $sheet_count = $xls->getSheetCount();
    for($sheet_n = 0; $sheet_n < $sheet_count; $sheet_n++){
        $xls->setActiveSheetIndex($sheet_n);
        $sheet = $xls->getActiveSheet();

        $sheet_title = $sheet->getTitle(); // get YEAR by sheet name

        $table[$sheet_title] = [];

        $row_count = $sheet->getHighestRow();
        for ($row_n = 3; $row_n <= $row_count; $row_n++) {

            if($sheet->getCellByColumnAndRow(0, $row_n)->getValue() == '') continue;

            // Get all COUNTRY DATA by row
            $table[$sheet_title][] = ['num' => $sheet->getCellByColumnAndRow(0, $row_n)->getValue(),
                                      'country_ru' => $sheet->getCellByColumnAndRow(1, $row_n)->getValue(),
                                      'country_en' => $sheet->getCellByColumnAndRow(2, $row_n)->getValue(),
                                      'country_html_id' => $sheet->getCellByColumnAndRow(3, $row_n)->getValue(),
                                      'digital_ci' => $sheet->getCellByColumnAndRow(4, $row_n)->getValue(),
                                      'global_ci' => $sheet->getCellByColumnAndRow(5, $row_n)->getValue(),
                                      'innovation_i' => $sheet->getCellByColumnAndRow(6, $row_n)->getValue(),
                                      'human_di' => $sheet->getCellByColumnAndRow(7, $row_n)->getValue(),
                                      'gdp' => $sheet->getCellByColumnAndRow(8, $row_n)->getValue(),
                                      'eg_rate' => $sheet->getCellByColumnAndRow(9, $row_n)->getValue(),
                                      'gdp_person' => $sheet->getCellByColumnAndRow(10, $row_n)->getValue(),
                                      'quality_l' => $sheet->getCellByColumnAndRow(11, $row_n)->getValue(),
                                      'happy_i' => $sheet->getCellByColumnAndRow(12, $row_n)->getValue(),
                                      'solid_gi' => $sheet->getCellByColumnAndRow(13, $row_n)->getValue()];
        }
    }
    return $table;
}

function insertFilterTableAll($db, $table_name, $data_all = []){
    foreach($data_all as $year => $sheet){
        foreach($sheet as $row){
            $db->query("INSERT INTO $table_name (year, num, country_ru, country_en, country_html_id, digital_ci, global_ci, innovation_i, human_di, gdp, eg_rate, gdp_person, quality_l, happy_i, solid_gi) VALUES ('" 
                       . $year . "', '" 
                       . $row['num'] . "', '" 
                       . $row['country_ru'] . "', '" 
                       . $row['country_en'] . "', '" 
                       . $row['country_html_id'] . "', '" 
                       . $row['digital_ci'] . "', '" 
                       . $row['global_ci'] . "', '" 
                       . $row['innovation_i'] . "', '" 
                       . $row['human_di'] . "', '" 
                       . $row['gdp'] . "', '" 
                       . $row['eg_rate'] . "', '" 
                       . $row['gdp_person'] . "', '" 
                       . $row['quality_l'] . "', '" 
                       . $row['happy_i'] . "', '" 
                       . $row['solid_gi'] . "')");
        }
    }
}

?>