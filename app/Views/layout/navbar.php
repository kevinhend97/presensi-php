<header class="c-header c-header-dark bg-info c-header-fixed">
        <ul class="c-header-nav d-md-down-none">
          <?php if(session('role') == 1): ?>
          <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="<?=base_url('event')?>">Events</a></li>
          <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="<?=base_url('users')?>">Members</a></li>
          <?php endif ?>
          <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="<?=base_url('dashboard')?>">Dashboard</a></li>
          <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="<?=base_url('attendance')?>">Attedances</a></li>
        </ul>
        <ul class="c-header-nav mfs-auto">
          <li class="c-header-nav-item dropdown mr-2"><a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
              <?= ucfirst(session()->get('name')) ?> &nbsp;
              <div class="c-avatar"><img class="c-avatar-img" src="<?= base_url('include/coreui') ?>/assets/img/avatars/6.jpg" alt="user@email.com"></div>
            </a>
            <div class="dropdown-menu dropdown-menu-right pt-0">
              <div class="dropdown-header bg-light py-2"><strong>Account</strong></div>
                <a class="dropdown-item" href="javascript:void(0)" onclick="logout();">
                <svg class="c-icon mfe-2">
                  <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-account-logout"></use>
                </svg> Logout</a>
            </div>
          </li>
          
        </ul>
        
      </header>

      <script>
        const logout = () => {
          Swal.fire({
            title: 'Are you sure?',
            text: "You will quit from this application",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Log Out!'
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = "<?= base_url('auth/logout') ?>";
            }
          })
        }

      </script>