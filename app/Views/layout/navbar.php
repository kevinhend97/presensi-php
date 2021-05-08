<header class="c-header c-header-dark bg-info c-header-fixed">
        <ul class="c-header-nav d-md-down-none">
          <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="<?=base_url('dashboard')?>">Dashboard</a></li>
          <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="<?=base_url('event')?>">Events</a></li>
          <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="<?=base_url('attendance')?>">Attedances</a></li>
          <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="<?=base_url('users')?>">Members</a></li>
        </ul>
        <ul class="c-header-nav mfs-auto">
          <li class="c-header-nav-item dropdown mr-2"><a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
              <?= ucfirst(session()->get('name')) ?> &nbsp;
              <div class="c-avatar"><img class="c-avatar-img" src="<?= base_url('include/coreui') ?>/assets/img/avatars/6.jpg" alt="user@email.com"></div>
            </a>
          </li>
        </ul>
      </header>