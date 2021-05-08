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
                    <h1 class="text-muted">MEMBER</h1>
                    <div class="table-responsive">
                        <table id="tabel_serverside" style="height:100%;" cellspacing="0" class="table display">
                            <thead>
                                <tr>
                                    <th>TIMESTAMP</th>
                                    <th>NAME</th>
                                    <th>USERNAME</th>
                                    <th>GENDER</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
            
                        </table>
                    </div>
                </div>
                <div class="col-md-5 col-xs-12">
                    <h1 class="text-muted">FORM ADD / EDIT MEMBER</h1>
                    <div class="row">
                        <div class="container">
                           <div class="card">
                               <div class="card-body">
                                   <form id="formMember" method="post">
                                       <div class="form-group">
                                           <input type="hidden" name="userId">
                                           <label for="exampleInputEmail1">Name</label>
                                           <input type="text" name="name" class="form-control" placeholder="Enter Name">
                                       </div>

                                       <div class="form-group">
                                           <label for="exampleInputEmail1">Gender</label>
                                           <select name="gender" class="form-control">
                                            <option value="">--- CHOOSE GENDER ---</option>
                                            <option value="1">MALE</option>
                                            <option value="2">FEMALE</option>
                                           </select>
                                       </div>
   
                                       <div class="form-group">
                                           <label for="exampleInputEmail1">Username</label>
                                           <input type="text" name="username" class="form-control" placeholder="Enter Username">
                                       </div>

                                       <div class="form-group">
                                           <label for="exampleInputEmail1">Password</label>
                                           <input type="password" name="password" class="form-control" placeholder="Enter Password">
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
                "ajax":{
                    url :"<?php echo base_url("users/listdata"); ?>", // json datasource
                    type: "post",  // method  , by default get
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
                    $('#formMember')[0].reset();
                    $("input,textarea").removeClass("is-valid");
                }
            });

            $('#formMember').validate({
                rules:{
                    name: 'required',
                    gender: 'required',
                    username: 'required',
                    password: {
                        required: true,
                        minlength: 8
                    }
                },
                messages:{
                    name:'Please enter name',
                    gender: 'Please enter gender',
                    username: 'Please enter username',
                    password: {
                        required: 'Please provide a password',
                        minlength: 'Your password must be at least 8 characters long'
                    }
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

        var tableReload = () =>{
            dataTable.ajax.reload();
        }

        function store(){
            if(saveMethod == 'update')
            {
                url = "<?=base_url('users/update/')?>";
            }
            else{
                url = "<?= base_url('users/store') ?>";
            }

            $.ajax({
                url:url,
                type:"POST",
                data:$('#formMember').serialize(),
                dataType:"JSON",
                success:function(res)
                {
                    toastr.success('Member has been save');
                  
                }
            })
        }

       var destroy = (id) => {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                $.ajax({
                    url:"<?= base_url('users/destroy') ?>",
                    type:"POST",
                    data:{userId:id},
                    dataType:"JSON",
                    success:function(resp)
                    {
                        if(resp.success == true)
                        {
                            tableReload();
                            toastr.success(resp.message);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                            toastr.error("Status: " + textStatus); 
                            toastr.error("Error: " + errorThrown); 
                    }       
                })
                }
            })
        }

       var edit = (id) => {
           $.ajax({
               url:"<?= base_url('users/edit') ?>",
               type:"POST",
               data:{userId:id},
               dataType:"JSON",
               success:function(resp)
               {
                   saveMethod = "update";
                   $('[name="userId"]').val(resp.kamarApps.results.userId);
                   $('[name="name"]').val(resp.kamarApps.results.name);
                   $('[name="gender"]').val(resp.kamarApps.results.gender);
                   $('[name="username"]').val(resp.kamarApps.results.username);
               },
               error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    toastr.error("Status: " + textStatus); 
                    toastr.error("Error: " + errorThrown); 
                }
           })
       }
    </script>
</div>
<?= $this->endSection() ?>