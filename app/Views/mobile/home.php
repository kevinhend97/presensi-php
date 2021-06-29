<?= $this->extend('mobile/index') ?>

<?= $this->section('css') ?>
<style>
body {
    height: 100%;
    background: linear-gradient(180deg, #5e8b7e 50%, #fff 50%);
}

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="row">
        <div class="col-sm-12 d-flex justify-content-between">
            <p class="text-left">
                <span class="badge badge-pill badge-light time" id="time">20:00 WIB</span>
            </p>
            <p class="text-center">
                <p class="text-light text-bold">Presensi</p>
            </p>
            <p class="text-right">
                <span onclick="openProfile()" class="badge badge-pill badge-light"><i class="fas fa-user-circle"></i> Profile</span>
            </p>
        </div>
    </div>

    <div class="row justify-content-center p-2 mb-5">
        <div class="text-center">
            <img src="<?= base_url('include/img/pnc.png') ?>" class="rounded mb-3" alt="...">
            <h3><span class="badge badge-secondary"><?= strtoupper(session()->get('name')) ?></span></h3>
        </div>
    </div>

    <div class="row d-flex justify-content-center p-2"> 
        <div class="col-6" onclick="window.location = '<?= base_url('attendance/scan') ?>';">
            <div class="card">
                <div class="card-body text-center">
                    <i class="display-3 mb-2 fas fa-chart-line text-success"></i>
                    <h5>PRESENSI</h5>
                </div>
            </div>
        </div>    
        <div class="col-6">
            <div class="card" onclick="openEventUpComing();">
                <div class="card-body text-center">
                    <i class="display-3 mb-2 text-info fas fa-calendar-check"></i>
                    <h5>EVENT</h5>
                </div>
            </div>
        </div>    
        <div class="col-6">
            <div class="card" onclick="openHistory()">
                <div class="card-body text-center">
                <i class="display-3 mb-2 text-danger far fa-clock"></i>
                    <h5>HISTORY</h5>
                </div>
            </div>
        </div>   
        <div class="col-6">
            <div class="card" onclick="openEventToday();">
                <div class="card-body text-center">
                <i class="display-3 mb-2 text-warning fas fa-check-circle"></i>
                    <h5>TODAY</h5>
                </div>
            </div>
        </div>    
    </div>

    <!-- Modal All Menu -->
    <div class="modal fade modalMenu" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="menuContent">
                
            </div>
        </div>
    </div>
    <!-- End Modal All Menu -->
   
   <script>
    <?php date_default_timezone_set('Asia/Jakarta'); ?>
    var today = "<?=date('Y-m-d')?>";

    const clock = () => {
        function addZero(i) {
            if (i < 10) {
                i = "0" + i;
            }
            return i;
        }

        var d = new Date();
        var x = document.getElementById("time");
        var h = addZero(d.getHours());
        var m = addZero(d.getMinutes());
        var s = addZero(d.getSeconds());
        x.innerHTML = h + ":" + m + ":" + s;

        setTimeout("clock()",1000);
    }
    window.onload=clock;

    const openProfile = () => {
        Swal.fire({
            title: 'Aplikasi Absensi Online',
            text: "Yakin melakukan log out ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#D3D3D3',
            cancelButtonColor: '#f93232',
            confirmButtonText: 'Keluar',
            cancelButtonText: 'Tidak'
            }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('auth/logout') ?>";
            }
        })
    }

    const openEventToday = () => {
        $.ajax({
            url: "<?= base_url('event/list') ?>",
            type: "GET",
            dataType: "JSON",
            success:function(res)
            {
                const events = res.kamarApps.results;
                let filterByToday = _.filter(events, { 'date': today });
                let html = '';

                if(filterByToday.length == 0)
                {
                    Swal.fire(
                    'Ooops ...',
                    'Nothing Event Today',
                    'info'
                    )
                }
                else
                {
                    _.forEach(_.take(filterByToday, 3),function(value, key){
                        html += `<div class="card mt-4 shadow-none" style="background-color:#f1f4f6;">`;
                        html +=    `<div class="card-body">`;
                        html +=        `<h5 class="card-title">${value.event_name}</h5>`;
                        html +=        `<p class="card-text text-danger"><i class="fas fa-map-marker-alt"></i> ${value.location} - ${value.date}</p>`;
                        html +=    `</div>`;
                        html += `</div>`;
                    })
                    $('.modalMenu').modal('show')
                    $('#menuContent').html(html);
                }

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                toastr.error("Status: " + textStatus); 
                toastr.error("Error: " + errorThrown); 
            }  
        })
    }

    const openEventUpComing = () => {
        $.ajax({
            url: "<?= base_url('event/list') ?>",
            type: "GET",
            dataType: "JSON",
            success:function(res)
            {
                const events = res.kamarApps.results;
                let filterByUpcoming = _.reject(events, { 'date': today });
                let htmlUpcoming = '';

                if(filterByUpcoming.length == 0)
                {
                    Swal.fire(
                    'Ooops ...',
                    'Nothing Event Up Coming',
                    'info'
                    )
                }
                else
                {
                     _.forEach(_.take(filterByUpcoming, 3),function(valueUp, keyUp){
                        htmlUpcoming += `<div class="card mt-4 shadow-none" style="background-color:#f1f4f6;">`;
                        htmlUpcoming +=    `<div class="card-body">`;
                        htmlUpcoming +=        `<h5 class="card-title">${valueUp.event_name}</h5>`;
                        htmlUpcoming +=        `<p class="card-text text-danger"><i class="fas fa-map-marker-alt"></i> ${valueUp.location} - ${valueUp.date}</p>`;
                        htmlUpcoming +=    `</div>`;
                        htmlUpcoming += `</div>`;
                    })
                    $('.modalMenu').modal('show')
                    $('#menuContent').html(htmlUpcoming);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                toastr.error("Status: " + textStatus); 
                toastr.error("Error: " + errorThrown); 
            }  
        })
    }

    const openHistory = () => {
        $.ajax({
            url : "<?= base_url('attendance/list') ?>",
            type : "GET",
            dataType : "JSON",
            success: function(res)
            {
                let html = '';

                if(res.length > 0)
                {
                    _.forEach(_.take(res.kamarApps.results, 6),function(value, key){
                        html += `<div class="card mt-4 shadow-none" style="background-color:#f1f4f6;">`;
                        html +=    `<div class="card-body">`;
                        html +=        `<div class="float-right">`;
                        html +=            `<small class="text-muted">${value.timestamp} WIB</small>`;
                        html +=        `</div>`;
                        html +=        `<h5 class="card-title">${value.name}</h5>`;
                        html +=        `<p class="card-text ${value.status != 'PRESENCE'  ? 'text-danger' : 'text-success'}"><i class="fas fa-sign-in-alt"></i></i> ${value.status} - ${value.event_name}</p>`;
                        html +=    `</div>`;
                        html += `</div>`;
                    })

                    $('.modalMenu').modal('show')
                    $('#menuContent').html(html);
                }
                else
                {
                    Swal.fire(
                    'Ooops ...',
                    'Attendance is not found',
                    'info'
                    )
                }

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                toastr.error("Status: " + textStatus); 
                toastr.error("Error: " + errorThrown); 
            } 
        })
    }
   </script>
<?= $this->endSection() ?>