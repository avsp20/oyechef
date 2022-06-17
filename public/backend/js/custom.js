$(function() {
//datatables default settings
    $.extend($.fn.dataTable.defaults, {
        autoWidth: false,
        order: [],
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': '&lt;%lt;', 'last': '&gt;%gt;', 'next': '&gt;', 'previous': '&lt;' }
        },
        buttons: {
            dom: {
              button: {
                  className: 'btn btn-default'
              }
            },
            buttons: [
            'copyHtml5',
            'csvHtml5',
            'pdfHtml5'
            ]
        },
        "pageLength": 25,
        "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ]
    });
});