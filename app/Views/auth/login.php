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
    <link rel="stylesheet" href="<?=base_url('include/css')?>/style.css">
  </head>
  <body class="c-app flex-row align-items-center">
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@0.12.0/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.13.1/lodash.min.js"></script>

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card-group">
            <div class="card p-4">
              <div class="card-body">
                <div class="text-center">
                    <img src="<?= base_url('include/img/s2p.png') ?>" width="180" height="100" alt="">
                    <h3>PRESENSI</h3>
                </div>
                <svg class="c-icon">
                    <use xlink:href="<?= base_url('include/coreui') ?>/vendors/@coreui/icons/svg/free.svg#cil-envelope-closed"></use>
                </svg>
                <label for="">Username</label> 
                <div class="input-group mb-3">
                  <input class="form-control" type="text" placeholder="Username">
                </div>
                <svg class="c-icon">
                    <use xlink:href="<?= base_url('include/coreui') ?>/vendors/@coreui/icons/svg/free.svg#cil-lock-locked"></use>
                </svg>
                <label for="">Password</label> 
                <div class="input-group mb-4">
                  <input class="form-control" type="password" placeholder="Password">
                </div>
                <div class="row">
                  <div class="col-12">
                    <a href="<?= base_url('dashboard') ?>"><button class="btn btn-info btn-block" type="button">Sign In</button></a>
                  </div>
                </div>
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
  </body>
</html>