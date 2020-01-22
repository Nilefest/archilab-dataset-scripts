<?php

require_once ('functions.php');
require_once ('export_excel.php');

if(isset($_POST['get_data'])){
    // Set '1' for start script/ Set '0' for stop script
    if(!isset($_POST['filter_country']) || !isset($_POST['filter_title']) || !isset($_POST['filter_year'])){
        echo '0';
        exit();
    }
    if(1){
        $filter_country = $_POST['filter_country'];
        $filter_title = $_POST['filter_title'];
        $filter_year = $_POST['filter_year'];
        $filter_lang = $_POST['filter_lang'];

        if(empty($filter_country) || empty($filter_title) || empty($filter_year)){
            echo '0';
            exit();
        }

        $data_filter_country = get_data_filter_country($db, $sql, $table_name, $filter_country, $filter_title, $filter_year, $filter_lang);
        
        if(isset($_POST['export_excel'])){
            
            $array_export = get_array_for_export($data_filter_country, $filter_title, $filter_year, $filter_lang);
            echo exportTableDataset($array_export, $file_name_new, $dir_export_file, $filter_lang);
        }
        else{
            echo json_encode($data_filter_country);
        }
    }
}

// Functions
function get_filter_country_sql($table_name, $filter_country, $filter_title, $filter_year){
    
    $sql = "SELECT `year`, `country_ru`, `country_en`, `country_html_id`";
    foreach($filter_title as $title){
        $sql .= ", `$title`";
    }
    $sql .= " FROM `$table_name` WHERE `country_html_id` IN (";

    foreach($filter_country as $country_html_id){
        $sql .= "'$country_html_id', ";
    }
    $sql = mb_substr($sql, 0, -2);
    $sql .= ") AND `year` IN (";
    foreach($filter_year as $year){
        $sql .= "'$year', ";
    }
    $sql = mb_substr($sql, 0, -2);
    $sql .= ") ORDER BY `year`, `country_html_id` ASC";
    
    return $sql;
}

function get_data_filter_country($db, $sql, $table_name, $filter_country, $filter_title, $filter_year, $filter_lang){
    $title_name_lang = [
        'num_format' => ['ru' => ',',
                         'en' => '.'],
        'nd' => ['ru' => 'н/д',
                 'en' => 'n/d'],
        'country' => ['ru' => 'Страна',
                       'en' => 'The country'],
        'digital_ci' => ['n_round' => '3',
                         'ru' => 'Индекс цифровой конкурентоспособности',
                         'en' => 'Digital Competitiveness Index'],
        'global_ci' => ['n_round' => '1',
                        'ru' => 'Индекс глобальной конкурентоспособности 4.0',
                        'en' => 'Global Competitiveness Index 4.0'],
        'innovation_i' => ['n_round' => '2',
                           'ru' => 'Индекс инноваций',
                           'en' => 'Innovation Index'],
        'human_di' => ['n_round' => '3',
                       'ru' => 'Индекс человеческого развития',
                       'en' => 'Human development index'],
        'gdp' => ['n_round' => '3',
                  'ru' => 'ВВП',
                  'en' => 'GDP'],
        'eg_rate' => ['n_round' => '3',
                      'ru' => 'Темп экономического роста',
                      'en' => 'Economic growth rate'],
        'gdp_person' => ['n_round' => '3',
                         'ru' => 'ВВП на душу населения',
                         'en' => 'GDP per capita'],
        'quality_l' => ['n_round' => '2',
                        'ru' => 'Индекс качества жизни',
                        'en' => 'Quality of Life Index'],
        'happy_i' => ['n_round' => '3',
                      'ru' => 'Индекс счастья',
                      'en' => 'Happiness index'],
        'solid_gi' => ['n_round' => '1',
                       'ru' => 'Индекс устойчивого развития',
                       'en' => 'Sustainability Index']
    ];
    $sql = get_filter_country_sql($table_name, $filter_country, $filter_title, $filter_year, $filter_lang);
    $data_rezult = getQueryObj($db, $sql);
    
    // THEAD 1
    $table1['thead'][0] = [''];
    $table1['thead'][1] = [$title_name_lang['country'][$filter_lang]];
    foreach($filter_title as $title_key => $title){
        // TRow 1
        $table1['thead'][0][] = $title_name_lang[$title][$filter_lang];
        
        foreach($filter_year as $year_key => $year){
            // TRow 2
            if($year < date('Y') and ($title == 'gdp' or $title == 'eg_rate' or $title == 'gdp_person'))
                $table1['thead'][1][] = $year . '*';
            else
                $table1['thead'][1][] = $year;
        }
        
    }
    
    // THEAD 2
    $table2['thead'][0] = [''];
    $table2['thead'][1] = [$title_name_lang['country'][$filter_lang]];
    foreach($filter_year as $title_key => $year){
        // TRow 1
        $table2['thead'][0][] = $year;
        
        foreach($filter_title as $year_key => $title){
            // TRow 2
            if($year < date('Y') and ($title == 'gdp' or $title == 'eg_rate' or $title == 'gdp_person'))
                $table2['thead'][1][] = $title_name_lang[$title][$filter_lang] . '*';
            else
                $table2['thead'][1][] = $title_name_lang[$title][$filter_lang];
        }
    }
    
    // TBODY
    $col_name_lang = "country_" . $filter_lang;
    foreach($data_rezult as $row_key => $row){
        
        // TBODY 1
        if(!isset($table1[$row->country_html_id]))
            $table1['tbody'][$row->country_html_id]['country'] = $row->$col_name_lang;
        
        // TBODY 2
        if(!isset($table2[$row->country_html_id]))
            $table2['tbody'][$row->country_html_id]['country'] = $row->$col_name_lang;
        
        // TBODY TRow
        foreach($filter_title as $title_key => $title){
            $this_value = $row->$title;
            if($this_value == '') $this_value = $title_name_lang['nd'][$filter_lang];
            else $this_value = number_format($this_value, $title_name_lang[$title]['n_round'], $title_name_lang['num_format'][$filter_lang], '') . ' ';
                
            // TRow for TBODY 1
            $table1['tbody'][$row->country_html_id]['data'][$title][$row->year] = $this_value;
            
            // TRow for TBODY 2
            $table2['tbody'][$row->country_html_id]['data'][$row->year][$title] = $this_value;
            
        }
    }
    
    // Create TBODY 1
    foreach($table1['tbody'] as $country_key => $country_tr){
        $tr_new = [$country_tr['country']];
        foreach($country_tr['data'] as $data){
            foreach($data as $value){
                $tr_new[] = $value;
            }
        }
        unset($table1['tbody'][$country_key]);
        $table1['tbody'][] = $tr_new;
    }
    
    // Create TBODY 2
    foreach($table2['tbody'] as $country_key => $country_tr){
        $tr_new = [$country_tr['country']];
        foreach($country_tr['data'] as $data){
            foreach($data as $value){
                $tr_new[] = $value;
            }
        }
        unset($table2['tbody'][$country_key]);
        $table2['tbody'][] = $tr_new;
    }
    
    return [$table1, $table2];
}

// Function for Export table
function get_array_for_export($data_filter_country, $filter_title, $filter_year, $filter_lang = 'ru'){
    $title_name_lang = [
        'page_1' => ['ru' => 'По показателям',
                     'en' => 'By subjects'],
        'page_2' => ['ru' => 'По годам',
                     'en' => 'By years'],
    ];
    $title_name_lang['page_1'][$filter_lang];
    
    $array_export = [['title' => $title_name_lang['page_1'][$filter_lang], 'colspan' => count($filter_year), 'data' => []], 
                     ['title' => $title_name_lang['page_2'][$filter_lang], 'colspan' => count($filter_title), 'data' => []]];
    
    // TABLE 1
    $data1 = [];
    // Add 1sf row
    for($i_cell = 0; $i_cell <= $array_export[1]['colspan']; $i_cell++){
        $data1[0][] = $data_filter_country[0]['thead'][0][$i_cell];
        
        if($i_cell > 0){
            for($j_colspan = 0; $j_colspan < $array_export[0]['colspan'] - 1; $j_colspan++){
                $data1[0][] = '';
            }
        }
    }
    // Add 2sf row
    $data1[1] = $data_filter_country[0]['thead'][1];
    
    // Add other row
    foreach($data_filter_country[0]['tbody'] as $tbody_row){
        $data1[] = $tbody_row;
    }
    $array_export[0]['data'] = $data1;
    
    // TABLE 2
    $data2 = [];
    // Add 1sf row
    for($i_cell = 0; $i_cell <= $array_export[0]['colspan']; $i_cell++){
        $data2[0][] = $data_filter_country[1]['thead'][0][$i_cell];
        
        if($i_cell > 0){
            for($j_colspan = 0; $j_colspan < $array_export[1]['colspan'] - 1; $j_colspan++){
                $data2[0][] = '';
            }
        }
    }
    // Add 2sf row
    $data2[1] = $data_filter_country[1]['thead'][1];
    
    // Add other row
    foreach($data_filter_country[1]['tbody'] as $tbody_row){
        $data2[] = $tbody_row;
    }
    $array_export[1]['data'] = $data2;
    
    return $array_export;
}

?>