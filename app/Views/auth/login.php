<!DOCTYPE html>
<!--
* CoreUI Pro based Bootstrap Admin Template
* @version v3.2.0
* @link https://coreui.io/pro/
* Copyright (c) 2020 creativeLabs Łukasz Holeczek
* License (https://coreui.io/pro/license)
-->
<html lang="en">
  <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>LOGIN PRESENSI</title>
    <!-- Main styles for this application-->
    <link href="<?= base_url('include/coreui') ?>/css/style.css" rel="stylesheet">

    <!-- style -->
    <link rel="stylesheet" href="<?= base_url('include/css') ?>/style.css">

    <!-- Font Core UI -->
    <link href="<?= base_url('include/coreui') ?>/vendors/@coreui/icons/css/free.min.css" rel="stylesheet">

  </head>
  <body class="c-app flex-row align-items-center">
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.13.1/lodash.min.js"></script>
    <!-- Jquery -->
    <script src="<?= base_url('include/plugins') ?>/jquery.js"></script>
    <!-- Swal -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card-group">
              <div class="card p-5">
                <img src="<?= base_url('include/bg/20945183.jpg') ?>" alt="">
              </div>
              <div class="card p-5 bg-primary text-light">
                <div class="card-body">
                 
                  <h3>SELAMAT DATANG,</h3>
                  <h4>Aplikasi Presensi Politeknik Negeri Cilacap</h4>
                 
                  <form id="form">
                    <div class="form-group has-icon mt-3 mb-4">
                      <span class="cil-user form-control-feedback"></span>
                      <input type="text" name="username" class="form-control" placeholder="Username">
                    </div>
  
                    <div class="form-group has-icon mt-3 mb-4">
                      <span class="cil-lock-locked form-control-feedback"></span>
                      <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                   
                    <div class="row">
                      <div class="col-12">
                        <button type="submit" class="btn btn-warning btn-block btn-radius mb-3 mt-2 p-2" type="button">Sign In</button>
                        <a href="<?= base_url('dashboard') ?>"><button class="btn btn-Primary btn-block btn-radius-border mb-3 p-2 mt-2" type="button">Forget Password</button></a>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
    <!-- CoreUI and necessary plugins-->
    <script src="<?= base_url('include/coreui') ?>/vendors/@coreui/coreui-pro/js/coreui.bundle.min.js"></script>
    <!--[if IE]><!-->
    <script src="<?= base_url('include/coreui') ?>/vendors/@coreui/icons/js/svgxuse.min.js"></script>
    <!--<![endif]-->
    <!-- Validation js -->
    <script src="<?= base_url('include/coreui') ?>/vendors/jquery-validation/js/jquery.validate.js"></script>
  </body>
</html>

<script>
  $(function(){
    // Validation'
    $.validator.setDefaults({
        submitHandler: function submitHandler() {
            // eslint-disable-next-line no-alert
            login();
            $('#form')[0].reset();
            $("input,textarea").removeClass("is-valid");
        }
    });

    $('#form').validate({
        rules:{
            username: 'required',
            password: 'required'
        },
        messages:{
          username:'Please enter Username',
          password: 'Please enter Password'
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
  })

  let login = () => {
    $.ajax({
      url: "<?= base_url('auth/auth') ?>",
      type:"POST",
      data:$('#form').serialize(),
      dataType:"JSON",
      success:function(res)
      {
        if(res.status == 200)
        {
          window.location = "<?= base_url('dashboard') ?>";
        }
        else
        {
          Swal.fire(
            'Oops,',
            res.message,
            'warning'
          )
        }
      }
    })
  }

</script>