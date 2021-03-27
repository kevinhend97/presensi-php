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
    <title>Presensi - <?= $pages ?></title>
    <!-- Main styles for this application-->
    <link href="<?= base_url('include/coreui') ?>/css/style.css" rel="stylesheet">
    <!-- Toasts css -->
    <link href="<?= base_url('include/plugins') ?>/toastr.min.css" rel="stylesheet">

    <?= $this->renderSection('css') ?>
  </head>
  <body class="c-app">
    <!-- Toastr JS -->
    <script src="<?= base_url('include/plugins') ?>/toastr.min.js"></script>
    <!-- Vue JS -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

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
   

  </body>
</html>