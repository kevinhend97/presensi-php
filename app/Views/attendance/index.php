<?= $this->extend('layout/index') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?=base_url('include/css')?>/app.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid" id="app">
    <div class="card text-muted">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <h1 class="text-muted">ATTENDANCE</h1>
                    <table id="tabel_serverside"  class="table">
                        <thead>
                            <tr>
                                <th>TIMESTAMP</th>
                                <th>NAME</th>
                                <th>EVENT</th>
                                <th>LOCATION</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>

    <script>
        var dataTable;
        var saveMethod;

        $(() => {
            var dataTable = $('#tabel_serverside').DataTable( {
                "processing" : true,
                "oLanguage": {
                    "sLengthMenu": "Tampilkan _MENU_ data per halaman",
                    "sSearch": "Pencarian: ",
                    "sZeroRecords": "Maaf, tidak ada data yang ditemukan",
                    "sInfo": "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                    "sInfoEmpty": "Menampilkan 0 s/d 0 dari 0 data",
                    "sInfoFiltered": "(di filter dari _MAX_ total data)",
                    "oPaginate": {
                        "sFirst": "<<",
                        "sLast": ">>",
                        "sPrevious": "<",
                        "sNext": ">"
                    }
                },
                columnDefs: [{
                        targets: [0],
                        orderable: false
                    }],
                    "ordering": true,
                    "info": true,
                    "serverSide": true,
                    "stateSave" : true,
                "scrollX": true,
                "ajax":{
                    url :"<?php echo base_url("attendance/listData"); ?>", // json datasource
                    type: "post",  // method  , by default get
                    error: function(){  // error handling
                        $(".tabel_serverside-error").html("");
                        $("#tabel_serverside").append('<tbody class="tabel_serverside-error"><tr><th colspan="3">Data Tidak Ditemukan di Server</th></tr></tbody>');
                        $("#tabel_serverside_processing").css("display","none");
            
                    }
                }
            });
        });
       
    </script>
</div>
<?= $this->endSection() ?>