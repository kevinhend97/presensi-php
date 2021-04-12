<!DOCTYPE html>
<html lang="en">
  <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Presensi - <?= $pages ?></title>
    <!-- Main styles for this application-->
    <link href="<?= base_url('include/coreui') ?>/css/style.css" rel="stylesheet">
    <!-- Toasts css -->
    <link href="<?= base_url('include/plugins') ?>/toastr.min.css" rel="stylesheet">
    <!-- Font Core UI -->
    <link href="<?= base_url('include/coreui') ?>/vendors/@coreui/icons/css/free.min.css" rel="stylesheet">

    <!-- Datatable -->
    <script src="<?= base_url('include/plugins') ?>/DataTables/datatables.min.css"></script>

    <!-- style -->
    <link rel="stylesheet" href="<?= base_url('include/css') ?>/style.css">

    <?= $this->renderSection('css') ?>
  </head>
  <body class="c-app">
    <!-- Jquery -->
    <script src="<?= base_url('include/plugins') ?>/jquery.js"></script>
    <!-- Datatable -->
    <script src="<?= base_url('include/plugins') ?>/DataTables/datatables.min.js"></script>
    <!-- Toastr JS -->
    <script src="<?= base_url('include/plugins') ?>/toastr.min.js"></script>
    <!-- Swal -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <?= $this->renderSection('js') ?>
    <div class="c-wrapper">
        <?= $this->include('layout/navbar') ?>
      <div class="c-body">
        <main class="c-main">
          <div class="container-fluid">
            <div class="fade-in">
            <?= $this->renderSection('content') ?>
            </div>
          </div>
        </main>
      </div>
      <?= $this->include('layout/footer') ?>
    </div>
   
    <!-- CoreUI and necessary plugins-->
   
    <!--[if IE]><!-->
    <script src="<?= base_url('include/coreui') ?>/vendors/@coreui/icons/js/svgxuse.min.js"></script>
    <!--<![endif]-->
    <!-- Plugins and scripts required by this view-->
 
    <script src="<?= base_url('include/coreui') ?>/vendors/@coreui/utils/js/coreui-utils.js"></script>

    <script src="<?= base_url('include/coreui') ?>/vendors/jquery-validation/js/jquery.validate.js"></script>
  </body>
</html>