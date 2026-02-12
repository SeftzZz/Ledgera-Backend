                <?php $uri = service('uri')->getSegment(1); ?>
                <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                    <div class="app-brand demo">
                        <a href="<?= base_url('dashboard') ?>" class="app-brand-link">
                            <img src="<?= base_url('assets/img/Logo-32.png') ?>" />
                            <span class="app-brand-text demo menu-text fw-bold">Ledgera</span>
                        </a>

                        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                          <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
                          <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
                        </a>
                    </div>

                    <div class="menu-inner-shadow"></div>

                    <ul class="menu-inner py-1">
                        <li class="menu-item <?= ($uri=='dashboard')?'active':'' ?>">
                            <a href="<?= base_url('dashboard') ?>" class="menu-link">
                                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                                <div data-i18n="Dashboards">Dashboards</div>
                            </a>
                        </li>

                        <li class="menu-item <?= ($uri=='coa')?'active':'' ?>">
                            <a href="<?= base_url('coa') ?>" class="menu-link">
                                <i class="menu-icon tf-icons ti ti-list-numbers"></i>
                                <div data-i18n="COA/Akun Perkiraan">COA/Akun Perkiraan</div>
                            </a>
                        </li>

                        <li class="menu-item <?= ($uri=='equity')?'active':'' ?>">
                            <a href="<?= base_url('equity') ?>" class="menu-link">
                                <i class="menu-icon tf-icons ti ti-wallet"></i>
                                <div data-i18n="Equity">Equity</div>
                            </a>
                        </li>

                        <?php if (hasPermission('users.view')): ?>
                        <li class="menu-item <?= ($uri=='users')?'active':'' ?>">
                            <a href="<?= base_url('users') ?>" class="menu-link">
                              <i class="menu-icon tf-icons ti ti-users"></i>
                              <div data-i18n="Users">Users</div>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <li class="menu-item">
                            <a href="<?= base_url('logout') ?>" class="menu-link">
                                <i class="menu-icon tf-icons ti ti-logout"></i>
                                <div data-i18n="Logout">Logout</div>
                            </a>
                        </li>
                    </ul>
                </aside>
