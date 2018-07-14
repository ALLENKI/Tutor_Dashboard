function initDataTable(){

    $.extend( $.fn.dataTable.defaults, {
        
        "serverSide": true,
        autoWidth: true,
        dom: '<"datatable-header"fBl><"datatable-scroll"t><"datatable-footer"ip>',
        buttons: [
            {
                extend: 'colvis',
                className: 'btn btn-default'
            }
        ],
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        drawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
        },
        preDrawCallback: function() {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
        },               
        "lengthMenu": [[100, 250, 500, -1], [100, 250, 500, "All"]],
        "pageLength": 100,
        
        customInitComplete: function(myTable,table) {

        // console.log("customInitComplete");

        $('#'+myTable+'_filter input').unbind();
        $('#'+myTable+'_filter input').bind('keyup', function(e) {
            if(e.keyCode == 13) {
                table.search( this.value ).draw();
            }
        });

        $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');

        $('.dataTables_length select').select2({
            minimumResultsForSearch: Infinity,
            width: 'auto'
        });

        }
    });

}

export { initDataTable };