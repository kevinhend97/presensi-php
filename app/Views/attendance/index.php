<?= $this->extend('layout/index') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?=base_url('include/css')?>/app.css">

<style>
.circular-menu {
  position: fixed;
  bottom: 4em;
  right: 1em;
}

.circular-menu .floating-btn {
  display: block;
  width: 3.5em;
  height: 3.5em;
  border-radius: 50%;
  background-color: hsl(217, 89%, 61%);
  box-shadow: 0 2px 5px 0 hsla(0, 0%, 0%, .26);  
  color: hsl(0, 0%, 100%);
  text-align: center;
  line-height: 3.9;
  cursor: pointer;
  outline: 0;
}

.circular-menu.active .floating-btn {
  box-shadow: inset 0 0 3px hsla(0, 0%, 0%, .3);
}

.circular-menu .floating-btn:active {
  box-shadow: 0 4px 8px 0 hsla(0, 0%, 0%, .4);
}

.circular-menu .floating-btn i {
  font-size: 1.3em;
  transition: transform .2s;  
}

.circular-menu.active .floating-btn i {
  transform: rotate(-45deg);
}

.circular-menu:after {
  display: block;
  content: ' ';
  width: 3.5em;
  height: 3.5em;
  border-radius: 50%;
  position: absolute;
  top: 0;
  right: 0;
  z-index: -2;
  background-color: hsl(217, 89%, 61%);
  transition: all .3s ease;
}

.circular-menu.active:after {
  transform: scale3d(5.5, 5.5, 1);
  transition-timing-function: cubic-bezier(.68, 1.55, .265, 1);
}

.circular-menu .items-wrapper {
  padding: 0;
  margin: 0;
}

.circular-menu .menu-item {
  position: absolute;
  top: .2em;
  right: .2em;
  z-index: -1;
  display: block;
  text-decoration: none;
  color: hsl(0, 0%, 100%);
  font-size: 1em;
  width: 3em;
  height: 3em;
  border-radius: 50%;
  text-align: center;
  line-height: 3;
  background-color: hsla(0,0%,0%,.1);
  transition: transform .3s ease, background .2s ease;
}

.circular-menu .menu-item:hover {
  background-color: hsla(0,0%,0%,.3);
}

.circular-menu.active .menu-item {
  transition-timing-function: cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.circular-menu.active .menu-item:nth-child(1) {
  transform: translate3d(1em,-7em,0);
}

.circular-menu.active .menu-item:nth-child(2) {
  transform: translate3d(-3.5em,-6.3em,0);
}

.circular-menu.active .menu-item:nth-child(3) {
  transform: translate3d(-6.5em,-3.2em,0);
}

.circular-menu.active .menu-item:nth-child(4) {
  transform: translate3d(-7em,1em,0);
}

/**
 * The other theme for this menu
 */

.circular-menu.circular-menu-left {
  right: auto; 
  left: 1em;
}

.circular-menu.circular-menu-left .floating-btn {
  background-color: hsl(217, 89%, 61%);
}

.circular-menu.circular-menu-left:after {
  background-color: hsl(217, 89%, 61%);
}

.circular-menu.circular-menu-left.active .floating-btn i {
  transform: rotate(90deg);
}

.circular-menu.circular-menu-left.active .menu-item:nth-child(1) {
  transform: translate3d(-1em,-7em,0);
}

.circular-menu.circular-menu-left.active .menu-item:nth-child(2) {
  transform: translate3d(3.5em,-6.3em,0);
}

.circular-menu.circular-menu-left.active .menu-item:nth-child(3) {
  transform: translate3d(6.5em,-3.2em,0);
}

.circular-menu.circular-menu-left.active .menu-item:nth-child(4) {
  transform: translate3d(7em,1em,0);
}


.select2-hidden-accessible{
  border-radius: 50px !important;
}


</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid" id="app">
    <div class="card text-muted">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                    <h1 class="text-muted">ATTENDANCE</h1>
                    <div class="table-responsive">
                        <table id="tabel_serverside"  class="table">
                            <thead>
                                <tr>
                                    <th>TIMESTAMP</th>
                                    <th>NAME</th>
                                    <th>EVENT</th>
                                    <th>LOCATION</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>                    
                </div>
            </div>
        </div>
    </div>

    <!-- Float Button -->
    <div id="circularMenu" class="circular-menu">

        <a class="floating-btn" onclick="document.getElementById('circularMenu').classList.toggle('active');">
            <i class="fa fa-bars"></i>
        </a>

        <menu class="items-wrapper">
            <a href="javascript:void(0)" onclick="info();" class="menu-item fa fa-info-circle"></a>
            <a href="javascript:void(0)" onclick="tableReload()" class="menu-item fa fa-sync-alt"></a>
            <a href="javascript:void(0)" onclick="openModal()" class="menu-item fa fa-save"></a>
            <a href="javascript:void(0)" onclick="reportProgress()" class="menu-item fa fa-desktop"></a>
        </menu>

    </div>
    <!-- //Float Button -->

   <!-- Modal -->
    <div class="modal fade" id="modalAttendace" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Presence</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="form" type="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="">Nama</label>
                <select name="user" class="form-control" id="name">
                </select>
              </div>
              <div class="form-group">
                <label for="">Event</label>
                <select name="event" class="form-control" id="event">
                </select>
              </div>
              <div class="form-group">
                <label for="">Status</label>
                <select name="status" class="form-control" id="status">
                </select>
              </div>
              <div class="form-group">
                <label for="">Message</label>
                <textarea name="message" style="resize:none;" placeholder="Message" class="form-control" id="" cols="30" rows="10"></textarea>
              </div>
              <div class="form-group">
                <label for="">Attachment</label>
               <input type="file" accept=".jpg,.png,.jpeg/image" name="upload" class="form-control" id="">
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
            </form>
        </div>
      </div>
    </div>
    <!-- // Modal -->

    <!-- Modal Report -->
   
    <!-- Modal -->
      <div class="modal fade" id="modalReport" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Report</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form id="formReport">
                <div class="form-group">
                  <label for="">Start Date</label>
                  <input type="date" name="startDate" class="form-control" id="startDate">
                </div>
                <div class="form-group">
                  <label for="">End Date</label>
                  <input type="date" name="endDate" class="form-control" id="endDate">
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" onclick="getReport();" class="btn btn-primary">Get Report</button>
            </div>
          </div>
        </div>
      </div>
    <!-- End Modal Report -->

    <script>
      var dataTable;
        var saveMethod;

        $(() => {
          dataTable = $('#tabel_serverside').DataTable( {
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
                  "order" :[[0, "desc"]], 
                  "info":true,
                  "lengthChange": false,
                  "serverSide": true,
                  "responsive":true,
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

          setInterval( function () {
            tableReload();
          }, 150000 );
          
        });

        const tableReload = () => {
          dataTable.ajax.reload();
        }

        const info = () => {
            Swal.fire({
                icon:'info',
                title: 'Aplikasi Presensi HMTLink',
                text:'Aplikasi Presensi Online Politeknik Negeri Cilacap',
                allowOutsideClick: () => {
                    const popup = Swal.getPopup()
                    popup.classList.remove('swal2-show')
                    setTimeout(() => {
                    popup.classList.add('animate__animated', 'animate__headShake')
                    })
                    setTimeout(() => {
                    popup.classList.remove('animate__animated', 'animate__headShake')
                    }, 500)
                    return false
                }
            })
        }

        const save = () => {
          var formData = new FormData($('#form')[0]);

          $.ajax({
              url : "<?= base_url('attendance/store') ?>",
              type: "POST",
              data: formData,
              contentType: false,
              processData: false,
              dataType: "JSON",
              success: function(data)
              {
      
                  if(data.success == true) //if success close modal and reload ajax table
                  {
                      $('#modalAttendace').modal('hide');
                      dataTable.ajax.reload();
                  }
      
              },
              error: function (jqXHR, textStatus, errorThrown)
              {
                  alert('Error adding / update data');
                  $('#btnSave').text('save'); //change button text
                  $('#btnSave').attr('disabled',false); //set button enable 
      
              }
          });
        }
       
      const openModal = () => {
        $('#form')[0].reset();
        $('#modalAttendace').modal('show');

        $('#name').select2({
          theme: "bootstrap4",
            placeholder: "Choose Name",
            width: 'auto',
            dropdownAutoWidth: true,
            allowClear: true,
            ajax: { 
              url: "<?php echo base_url('users/listSelect') ?>",
              type: "post",
              dataType: 'json',
              delay: 250,
              data: function (params) {
                return {
                  searchTerm: params.term // search term
                };
              },
              processResults: function (response) {
                return {
                    results: response
                };
              },
              cache: true
            }
        });

        $('#event').select2({
          theme: "bootstrap4",
            placeholder: "Choose  Event",
            width: 'auto',
            dropdownAutoWidth: true,
            allowClear: true,
            ajax: { 
              url: "<?php echo base_url('event/eventSelect') ?>",
              type: "post",
              dataType: 'json',
              delay: 250,
              data: function (params) {
                return {
                  searchTerm: params.term // search term
                };
              },
              processResults: function (response) {
                return {
                    results: response
                };
              },
              cache: true
            }
        });

        $('#status').select2({
          theme: "bootstrap4",
            placeholder: "Choose  Status",
            width: 'auto',
            dropdownAutoWidth: true,
            allowClear: true,
            ajax: { 
              url: "<?php echo base_url('attendance/attendanceStatus') ?>",
              type: "post",
              dataType: 'json',
              delay: 250,
              data: function (params) {
                return {
                  searchTerm: params.term // search term
                };
              },
              processResults: function (response) {
                return {
                    results: response
                };
              },
              cache: true
            }
        });


          // Validation'
          $.validator.setDefaults({
                submitHandler: function submitHandler() {
                    // eslint-disable-next-line no-alert
                    save();
                    tableReload();
                    $('#form')[0].reset();
                    $("input,textarea").removeClass("is-valid");
                }
            });

            $('#form').validate({
                rules:{
                    user: 'required',
                    event: 'required',
                    status: 'required',
                    upload: {
                      required: true,
                      accept: "application/.pdf, image/jpeg, image/png, image/jpg"
                    },
                    message: {
                      required:true,
                      maxlength:2000
                    }
                },
                messages:{
                    user:'Please choose name',
                    event: 'Please choose event',
                    status: 'Please choose status',
                    message: {
                      required: 'Please enter message',
                      maxlength: 'Max Length is 2000'
                    },
                    upload:{
                      required: 'please input file',
                      accept:'input just png, jpg, png and pdf'
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
      }

      const show = (id) => {
        $.ajax({
          url: "<?= base_url('attendance/getById') ?>"+"/"+id,
          type:"GET",
          dataType:"json",
          success:function(res)
          {

            if(res.attachment == null)
            {
              urlImage = '<?= base_url('include/img/undraw_Online_calendar.svg') ?>'
            }
            else
            {
              urlImage = '<?= base_url('upload/') ?>'+"/"+res.attachment
            }

            console.log(res);
            Swal.fire({
              title: res.attendanceStatus,
              text: res.message,
              imageUrl: urlImage,
              imageAlt: 'Custom image',
            })
          }
        })

      }

      const reportProgress = () =>
      {
        $('#modalReport').modal('show');
      }

      const getReport = () => {
        let startDate = $('[name="startDate"]').val();
        let endDate = $('[name="endDate"]').val();
        
        if(startDate == '')
        {
          toastr.error("Start Date is empty"); 
        }
        else if(endDate == '')
        {
          toastr.error("End Date is empty"); 
        }
        else
        {
          $("<iframe>")                             // create a new iframe element
          .hide()                               // make it invisible
          .attr("src", "<?= base_url('attendance/report') ?>"+`?start=${ startDate }&end=${ endDate }`) // point the iframe to the page you want to print
          .appendTo("body"); 
        }
      }
    
       
    </script>
</div>
<?= $this->endSection() ?>