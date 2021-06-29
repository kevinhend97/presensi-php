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

    <div class="row header">
        <div class="col-sm-12 d-flex justify-content-between">
            <p class="text-left">
                <span class="badge badge-pill badge-light time" id="time">20:00 WIB</span>
            </p>
            <p class="text-center">
                <p class="text-light text-bold">Scan</p>
            </p>
            <p class="text-right">
                <span class="badge badge-pill badge-light"><i class="fas fa-user-circle"></i> Profile</span>
            </p>
        </div>
    </div>

    <div class="row d-flex justify-content-between">
        <div style="width: 500px" id="reader"></div>
    </div>

    <div class="row d-flex justify-content-between">
        <div class="back text-center mt-3 p-5">
            <h2>Scan QR Code</h2>
            <img src="<?= base_url('include/img/undraw_Location_search.svg') ?>" class="img-fluid mb-4" alt="">
            <p>Scan QR Code Untuk dapat melakukan Absensi</p>
            <button type="button" onclick="window.location = '<?= base_url('mobile') ?>';" class="btn btn-danger btn-lg btn-block">BACK TO HOME</button>
        </div>
    </div>

    
   <script src="<?= base_url('include/plugins/scan_qrcode.min.js') ?>"></script>
   <script>
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


    // QR CODE SCANNER

    const html5QrCode = new Html5Qrcode("reader");
    const qrCodeSuccessCallback = message => { 
        scan(message);
    }
    const config = { fps: 10, qrbox: 250 };
    
    // If you want to prefer back camera
    html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);

    const scan = (eventCode) => {
        $.ajax({
            url : "<?= base_url('attendance/presence') ?>"+`?p=${eventCode}`,
            type: "GET",
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function(data)
            {
                if(data.success == true)
                {
                    Swal.fire(
                    'Yeaaaaayy ...',
                    data.message,
                    'success'
                    )
                }
                else
                {
                    Swal.fire(
                    'Ooops ...',
                    data.message,
                    'warning'
                    )
                }

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                Swal.fire(
                    'Ooops ...',
                    'Server 505 Bad Gateway',
                    'error'
                    )

            }
        });
    }
   </script>
<?= $this->endSection() ?>