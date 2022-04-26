/**
 * Created by Stuart on 23/7/2018.
 */





table = $('#example2').DataTable({
    language: {
        processing: "En curso...",
        search: "Buscar:",
        paginate: {
            first:      "Primero",
            previous:   "Anterior",
            next:       "Siguiente",
            last:       "Último"
        },
    },
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": false,
    "info": true,
    "order": [[ 0, "desc" ]], // orden de los resultados primero columna 0 los IN y luego por año columna 3
});
/*

$(document).ready(function () {

$('#example2').dataTable( {
    "ajax": "program/search",
    "sAjaxDataProp": "programs",
    "Columns":[
                { 'programs': 'id' },
                { 'programs': 'detail' }, //or { data: 'MONTH', title: 'Month' }`
                { 'programs': 'name' },
                { 'programs': 'status' },
                { 'programs': 'period_id' }
            ]

} ); });*/
/*
$.ajax({

    type: "GET",
    url: 'program/search',
    dataType: 'json',
    dataSrc: "programs",
    success: function (obj, textstatus) {

        $('#example2').DataTable({
            data: obj,
            columns: [
                { 'programs': 'id' },
                { 'programs': 'detail' }, //or { data: 'MONTH', title: 'Month' }`
                { 'programs': 'name' },
                { 'programs': 'status' },
                { 'programs': 'period_id' }
            ]
        });


    },
    error: function (obj, textstatus) {
        alert(obj.msg);
    }
});
*/

