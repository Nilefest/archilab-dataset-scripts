// Functions for SelectAll and UnselectAll
$('#select-all-1').click(function () {
    $('.data-body__selections-1 input:checkbox').prop('checked', true);
});

$('#unselect-all-1').click(function () {
    $('.data-body__selections-1 input:checkbox').prop('checked', false);
});

$('#select-all-2').click(function () {
    $('.data-body__selections-2 .country_show input:checkbox').prop('checked', true);
});

$('#unselect-all-2').click(function () {
    $('.data-body__selections-2 input:checkbox').prop('checked', false);
});

$('#select-all-3').click(function () {
    $('.data-body__selections-3 input:checkbox').prop('checked', true);
});

$('#unselect-all-3').click(function () {
    $('.data-body__selections-3 input:checkbox').prop('checked', false);
});

$('#select-all-4').click(function () {
    $('.data-body__selections-4 input:checkbox').prop('checked', true);
});

$('#unselect-all-4').click(function () {
    $('.data-body__selections-4 input:checkbox').prop('checked', false);
});

// Functions for sort in table
// $('body').on('click', '#all-country', function () {
//     if ($(this).is(':checked')) {
//         $('#db__fgc input:checkbox').prop('checked', true);
//     } else {
//         $('#db__fgc input:checkbox').prop('checked', false);
//     }
// });

$('body').on('click', '#industry-4', function () {
    if ($(this).is(':checked')) {
        $('#db__industry-l input:checkbox').prop('checked', true);
    } else {
        $('#db__industry-l input:checkbox').prop('checked', false);
    }
});

$('body').on('click', '#knowledge-economy', function () {
    if ($(this).is(':checked')) {
        $('#db__knowledge-economy-l input:checkbox').prop('checked', true);
    } else {
        $('#db__knowledge-economy-l input:checkbox').prop('checked', false);
    }
});

$('body').on('click', '#economic-growth', function () {
    if ($(this).is(':checked')) {
        $('#db__economic-growth-l input:checkbox').prop('checked', true);
    } else {
        $('#db__economic-growth-l input:checkbox').prop('checked', false);
    }
});

$('body').on('click', '#sustainable-d', function () {
    if ($(this).is(':checked')) {
        $('#db__sustainable-d-l input:checkbox').prop('checked', true);
    } else {
        $('#db__sustainable-d-l input:checkbox').prop('checked', false);
    }
});

$('body').on('click', '#period', function () {
    if ($(this).is(':checked')) {
        $('#time-periods input:checkbox').prop('checked', true);
    } else {
        $('#time-periods input:checkbox').prop('checked', false);
    }
});

$('body').on('click', '.db-btn_sheet', function () {
    $('label.db-btn').removeClass('db-btn_active');
    $('label.db-btn[for="' + $(this).attr('id') + '"]').addClass('db-btn_active');
});


// Functions for Filter 
$('#data-nav__tab2').click(function () {
    filter_list_country();
});

let filter_country = [];
let filter_title = [];
let filter_year = [];

$('.data-nav__tab').on('change', function () {
    $('label.data-nav__step').removeClass('dnts_active');
    $('label.data-nav__step[for="' + $(this).attr('id') + '"]').addClass('dnts_active');
    $('span.data-nav__one-step').html($('label.data-nav__step[for="' + $(this).attr('id') + '"]').html());
});

function filter_list_country() {

    if ($('span.data-nav__one-step').html() == 1) {
        $('.country_name').find('input').prop('checked', false);
    }

    // скрыть ВСЕ страны
    $('.country_name').hide(0);
    $('.country_show').removeClass('country_show');

    // Простись по каждому из инпутов с категорией стран и отобразить отмеченные
    $('.country_type').each(function (index) {
        if ($(this).is(':checked')) {
            $('.class_' + $(this).attr('id')).show(0);
            $('.class_' + $(this).attr('id')).addClass('country_show');
        }

    });

    let count_country = $(".country_show").length;
    $('.count_country').html(count_country);

}

$('#data-nav__tab3, #data-nav__tab4, #data-nav__tab5').click(function () {
    filter_ws_country();
});

function filter_ws_country() {
    filter_country = [];
    // скрыть ВСЕ страны
    $('.dws__list_country').html('');

    // Простись по каждому из инпутов страны и отобразить отмеченные
    $('.country-name_check').each(function (index) {
        if ($(this).is(':checked')) {
            $('.dws__list_country').append('<p class="dws__country">' + $(this).attr('data-nameRu') + '</p>');
            filter_country.push($(this).attr('name'));
        }

    });
}

$('#data-nav__tab4, #data-nav__tab5').click(function () {
    filter_ws_title();
});

$('#data-nav__tab5').click(function () {
    filter_ws_year();
    getDataTable();
});

function filter_ws_title() {

    filter_title = [];
    // скрыть ВСЕ страны
    $('.dws__list_title').html('');

    // Простись по каждому из инпутов с темами для таблицы и отобразить отмеченные
    $('.title-name_check').each(function (index) {
        if ($(this).is(':checked')) {
            $('.dws__list_title').append('<p class="dws__one-topic">' + $(this).attr('data-titleRu') + '</p>');
            filter_title.push($(this).attr('name'));
        }

    });

}

function filter_ws_year() {

    filter_year = [];

    $('.year-name_check').each(function (index) {
        if ($(this).is(':checked')) {
            filter_year.push($(this).attr('name'));
        }

    });

}

// Fet filter rezult by server
function getDataTable() {
    $.ajax({
        url: '/script/dataset_filter/get_data_by_filter.php',
        type: "POST",
        data: {
            get_data: 1,
            filter_country: filter_country,
            filter_title: filter_title,
            filter_year: filter_year,
            filter_lang: filter_lang
        },
        success: function (data) {

            if (data != '0') {
                data = JSON.parse(data);
                tableRefresh(data);
            } else {
                console.log(data);
                // Clear table
                $('#db-sheet-1 thead').html('');
                $('#db-sheet-1 tbody').html('');

                $('#db-sheet-2 thead').html('');
                $('#db-sheet-2 tbody').html('');

                alert('Введены не все данные');
            }
        },

        error: function (msg) {
            console.log('ERR: ' + msg);
        }
    });
}

// Functions for Create table
function tableRefresh(data) {

    //console.log(data);

    // Get colspan for html blocks by filter_year / filter_title
    let colspan1 = filter_year.length;
    let colspan2 = filter_title.length;

    // Create html blocks
    let thead1_data = getDataThead(data['0']['thead'], colspan1);
    let tbody1_data = getDataTbody(data['0']['tbody']);

    let thead2_data = getDataThead(data['1']['thead'], colspan2);
    let tbody2_data = getDataTbody(data['1']['tbody']);

    // Set to page
    $('#db-sheet-1 thead').html(thead1_data);
    $('#db-sheet-1 tbody').html(tbody1_data);

    $('#db-sheet-2 thead').html(thead2_data);
    $('#db-sheet-2 tbody').html(tbody2_data);

    // Set sort function
    const getSort = ({
        target
    }) => {
        const order = (target.dataset.order = -(target.dataset.order || -1));
        const index = [...target.parentNode.cells].indexOf(target);
        const collator = new Intl.Collator(['en', 'ru'], {
            numeric: true
        });
        const comparator = (index, order) => (a, b) => order * collator.compare(
            a.children[index].innerHTML,
            b.children[index].innerHTML
        );

        for (const tBody of target.closest('table').tBodies)
            tBody.append(...[...tBody.rows].sort(comparator(index, order)));
        for (const cell of target.parentNode.cells)
            cell.classList.toggle('sorted', cell === target);
    };
    document.querySelectorAll('.table_sort thead th').forEach(tableTH => tableTH.addEventListener('click', () => getSort(event)));
}

function getDataThead(thead_data, colspan) {
    // THEAD
    let thead_html = "<tr>";
    // TRow 1st for THead
    thead_data[0].forEach(function (data_cell) {
        if (data_cell != '')
            thead_html += "<td colspan='" + colspan + "'>";
        else
            thead_html += "<td>";
        thead_html += data_cell + "</td>";
    });
    thead_html += "</tr><tr>";
    // TRow 2nd for THead
    thead_data[1].forEach(function (data_cell) {
        thead_html += "<th>" + data_cell + "</th>";
    });
    thead_html += "</tr>";
    return thead_html;
}

function getDataTbody(tbody_data) {
    // TBODY
    let tbody_html = "";
    tbody_data.forEach(function (data_row) {
        tbody_html += "<tr>";
        data_row.forEach(function (data_cell) {
            tbody_html += "<td>" + data_cell + "</td>";
        });
        tbody_html += "</tr>";
    });
    return tbody_html;
}

// Function for export to Excel
$('#download-sheet').click(function () {

    $.ajax({
        url: '/script/dataset_filter/get_data_by_filter.php',
        type: "POST",
        data: {
            get_data: 1,
            export_excel: 1,
            filter_country: filter_country,
            filter_title: filter_title,
            filter_year: filter_year,
            filter_lang: filter_lang
        },
        success: function (data) {

            if (data != '0') {
                console.log(data);
                
                download_file('/script/dataset_filter/files/export/' + data, data)

            } else {
                console.log(data);
                alert('Введены не все данные');
            }
        },

        error: function (msg) {
            console.log('ERR: ' + msg);
        }
    });
});

// Download file
function download_file(url, name) {
    var link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', name);
    link.click();
    return false;
}
