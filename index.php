<?php

require_once ('functions.php');

// Get DB data All
// Set '1' for start script/ Set '0' for stop script
if($_GET['is_script_start']){
    $table_db = getFilterTableAll($db, $table_name);
    print_r($table_db);
}

function getFilterTableAll($db, $table_name){
    $table_new = [];
    $table = getQuery($db, "SELECT * FROM $table_name ORDER BY year, num");
    foreach($table as $row){
        $table_new[$row['year']][] = ['num' => $row['num'],
                                      'country_ru' => $row['country_ru'],
                                      'country_en' => $row['country_en'],
                                      'country_html_id' => $row['country_html_id'],
                                      'digital_ci' => $row['digital_ci'],
                                      'global_ci' => $row['global_ci'],
                                      'innovation_i' => $row['innovation_i'],
                                      'human_di' => $row['human_di'],
                                      'gdp' => $row['gdp'],
                                      'eg_rate' => $row['eg_rate'],
                                      'gdp_person' => $row['gdp_person'],
                                      'quality_l' => $row['quality_l'],
                                      'happy_i' => $row['happy_i'],
                                      'solid_gi' => $row['solid_gi']];
    }
    return $table_new;
}

?>
