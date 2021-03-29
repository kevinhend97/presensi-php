<?= $this->extend('layout/index') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?=base_url('include/css')?>/app.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid" id="app">
    <div class="card text-muted">
        <div class="card-body">
            <div class="row">
                <div class="col-md-7 col-xs-12">
                    <h1 class="text-muted">EVENT</h1>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="">Event Name</label>
                                <input type="text" name="eventSearch" onchange="tableReload()" placeholder="Search by Event Name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="">Location</label>
                                <input type="text" name="locationSearch" onchange="tableReload()" placeholder="Search by Event Location" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="">Date</label>
                                <input type="date" name="dateSearch" onchange="tableReload()" placeholder="Search by Event Date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="tabel_serverside" style="height:100%;" cellspacing="0" class="table display">
                            <thead>
                                <tr>
                                    <th>TIMESTAMP</th>
                                    <th>EVENT</th>
                                    <th>LOCATION</th>
                                    <th>DATE</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
            
                        </table>
                    </div>
                </div>
                <div class="col-md-5 col-xs-12">
                    <h1 class="text-muted">FORM ADD / EDIT EVENT</h1>
                    <div class="row">
                        <div class="container">
                           <div class="card">
                               <div class="card-body">
                                   <form id="formEvent">
                                       <div class="form-group">
                                           <input type="hidden" name="eventId">
                                           <label for="exampleInputEmail1">Event Name</label>
                                           <input type="text" name="eventName" class="form-control" placeholder="Enter Event Name">
                                       </div>
   
                                       <div class="form-group">
                                           <label for="exampleInputEmail1">Location</label>
                                           <input type="text" name="location" class="form-control" placeholder="Enter Location">
                                       </div>
   
                                       <div class="form-group">
                                           <label for="exampleInputEmail1">Description</label>
                                           <textarea class="form-control" name="description" rows="7" style="resize:none;" placeholder="Enter Description"></textarea>
                                       </div>
   
                                       <div class="form-group">
                                           <label for="exampleInputEmail1">Date</label>
                                           <input type="date" class="form-control" name="date" placeholder="Enter Date">
                                       </div>
   
                                       <div class="form-group">
                                           <label for="exampleInputEmail1">Start Time</label>
                                           <input type="time" class="form-control" name="startTime" placeholder="Enter Start Time">
                                       </div>
   
                                       <div class="form-group">
                                           <label for="exampleInputEmail1">End Time</label>
                                           <input type="time" class="form-control" name="endTime" placeholder="Enter End Time">
                                       </div>
                                       
                                       <button type="submit" class="btn btn-block btn-info">SUBMIT</button>
                                   </form>
                               </div>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var dataTable;
        var saveMethod;
        $(document).ready(function() {
           var date = $('[name="date"]').val();
            var event = $('[name="eventName"]').val();
            var location = $('[name="location"]').val();

            console.log(event)
            dataTable = $('#tabel_serverside').DataTable({
                "processing" : true,
                columnDefs: [{
                    targets: '_all',
                    orderable: true,
                    searchable: false
                }],
                "ordering": true,
                "info": true,
                "serverSide": true,
                "stateSave" : true,
                "bFilter": false ,
                "ajax":{
                    url :"<?php echo base_url("event/listdata"); ?>", // json datasource
                    type: "post",  // method  , by default get
                    data:{'event':event,'location':location,'date':date},
                    error: function(){  // error handling
                        $(".tabel_serverside-error").html("");
                        $("#tabel_serverside").append('<tbody class="tabel_serverside-error"><tr><th colspan="3">Data Tidak Ditemukan di Server</th></tr></tbody>');
                        $("#tabel_serverside_processing").css("display","none");
            
                    }
                }
            });

            // Validation'
            $.validator.setDefaults({
                submitHandler: function submitHandler() {
                    // eslint-disable-next-line no-alert
                    store();
                    tableReload();
                    $('#formEvent')[0].reset();
                    $("input,textarea").removeClass("is-valid");
                }
            });

            $('#formEvent').validate({
                rules:{
                    eventName: 'required',
                    location: 'required',
                    date: 'required',
                    description: 'required',
                    startTime: 'required',
                    endTime: 'required'
                },
                messages:{
                    eventName:'Please enter event name',
                    location: 'Please enter location',
                    date: 'Please enter date',
                    description: 'Please enter description',
                    startTime: 'Please enter start time',
                    endTime: 'Please enter end time'
                },
                errorElement: 'em',
                errorPlacement: function errorPlacement(error, element) {
                    error.addClass('invalid-feedback');
                    if (element.prop('type') === 'checkbox') {
                        error.insertAfter(element.parent('label'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                // eslint-disable-next-line object-shorthand
                highlight: function highlight(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                // eslint-disable-next-line object-shorthand
                unhighlight: function unhighlight(element) {
                    $(element).addClass('is-valid').removeClass('is-invalid');
                }
            });

        });

        function tableReload()
        {
            dataTable.ajax.reload();
        }

        function store()
        {
            if(saveMethod == 'update')
            {
                url = "<?=base_url('event/update/')?>";
            }
            else{
                url = "<?= base_url('event/store') ?>";
            }

            $.ajax({
                url:url,
                type:"POST",
                data:$('#formEvent').serialize(),
                dataType:"JSON",
                success:function(res)
                {
                    toastr.success('Event has been save');
                }
            })
        }

        function edit(id)
        {

        }
    </script>
</div>
<?= $this->endSection() ?>