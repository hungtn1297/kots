    <script src="{{ URL::asset('admin/bower_components/jquery/dist/jquery.min.js') }}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ URL::asset('admin/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="{{ URL::asset('admin/bower_components/metisMenu/dist/metisMenu.min.js') }}"></script>

    <!-- Custom Theme JavaScript -->
    <script src="{{ URL::asset('admin/dist/js/sb-admin-2.js') }}"></script>

    <!-- DataTables JavaScript -->
    <script src="{{ URL::asset('admin/bower_components/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('admin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        // $('#dataTables-example thead tr').clone(true).appendTo( '#dataTables-example thead' );
        // $('#dataTables-example thead tr:eq(1) th').each( function (i) {
        // var title = $(this).text();
        // $(this).html( '<input type="text" placeholder="Lọc" style="width: 60%"/>');
 
        // $( 'input', this ).on( 'keyup change', function () {
        //     if ( table.column(i).search() !== this.value ) {
        //         table
        //             .column(i)
        //             .search( this.value )
        //             .draw();
        //     }
        // } );
        // } );
        var table = $('#dataTables-example').DataTable({
                responsive: true,
                // "order": []
                // columnDefs: [ {
                //     'targets': [0], /* column index [0,1,2,3]*/
                //     'orderable': false, /* true or false */
                // }],
                "language": {
                    "decimal":        "",
                    "emptyTable":     "Không có dữ liệu",
                    "info":           "Hiển thị _START_ tới _END_ trong tổng _TOTAL_ dòng",
                    "infoEmpty":      "Hiển thị 0 tới 0 trong tổng 0 dòng",
                    "infoFiltered":   "(Lọc trong tổng _MAX_ dòng)",
                    "infoPostFix":    "",
                    "thousands":      ",",
                    "lengthMenu":     "Hiển thị _MENU_ dòng",
                    "loadingRecords": "Đang tải...",
                    "processing":     "Đang xử lí...",
                    "search":         "Tìm kiếm:",
                    "zeroRecords":    "Không tìm thấy kết quả",
                    "paginate": {
                        "first":      "Đầu",
                        "last":       "Cuối",
                        "next":       "Trang sau",
                        "previous":   "Trang trước"
                    },
                    "aria": {
                        "sortAscending":  ": Sắp xếp theo thứ tự tăng dần",
                        "sortDescending": ": Sắp xếp theo thứ tự giảm dần"
                    }
                },
                "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
            }
        });
    });


    function goBack(){
        window.history.back();
    }

    </script>