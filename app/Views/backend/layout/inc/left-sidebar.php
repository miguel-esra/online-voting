<div class="left-side-bar">
    <div class="brand-logo">
        <a href="<?= (!empty(get_voter())) ? route_to('user.home') : route_to('admin.home') ?>" style="padding-left: 2em;">
            <img src="/images/blog/<?= get_settings()->blog_logo ?>" alt="" class="dark-logo" />
            <img src="/images/blog/<?= get_settings()->blog_logo ?>" alt="" class="light-logo" />
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                <?php if (!empty(get_voter())) : ?>
                <li>
                    <a href="<?= route_to('user.home'); ?>" class="dropdown-toggle no-arrow <?= current_route_name() == 'user.home' ? 'active' : '' ?>">
                        <span class="micon dw dw-home"></span>
                        <span class="mtext">Votación</span>
                    </a>
                </li>
                <li>
                    <a href="<?= route_to('user.my.vote'); ?>" class="dropdown-toggle no-arrow <?= current_route_name() == 'user.my.vote' ? 'active' : '' ?>">
                        <span class="micon dw dw-clipboard1"></span>
                        <span class="mtext">Mi Voto</span>
                    </a>
                </li>
                <?php else : ?>
                <li>
                    <a href="<?= route_to('admin.home'); ?>" class="dropdown-toggle no-arrow <?= current_route_name() == 'admin.home' ? 'active' : '' ?>">
                        <span class="micon dw dw-analytics-11"></span>
                        <span class="mtext">Resultados</span>
                    </a>
                </li>
                <li>
                    <a href="<?= route_to('admin.participants'); ?>" class="dropdown-toggle no-arrow <?= current_route_name() == 'admin.participants' ? 'active' : '' ?>">
                        <span class="micon dw dw-group"></span>
                        <span class="mtext">Participantes</span>
                    </a>
                </li>
                <?php endif; ?>
                <!-- <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon dw dw-newspaper"></span
                        ><span class="mtext">Posts</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="">All Posts</a></li>
                        <li><a href="">Add New</a></li>
                    </ul>
                </li> -->
                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <div class="sidebar-small-cap">Ajustes</div>
                </li>
                <li>
                    <?php if (!empty(get_voter())) : ?>
                    <a href="<?= route_to('user.profile'); ?>" class="dropdown-toggle no-arrow <?= current_route_name() == 'user.profile' ? 'active' : '' ?>">
                    <?php else : ?>
                    <a href="<?= route_to('admin.profile'); ?>" class="dropdown-toggle no-arrow <?= current_route_name() == 'admin.profile' ? 'active' : '' ?>">
                    <?php endif; ?>
                        <span class="micon dw dw-user"></span>
                        <span class="mtext">Perfil</span>
                    </a>
                </li>
                <li>
                    <?php if (!empty(get_voter())) : ?>
                    <a href="<?= route_to('user.settings'); ?>" class="dropdown-toggle no-arrow <?= current_route_name() == 'user.settings' ? 'active' : '' ?>">
                    <?php else : ?>
                    <a href="<?= route_to('admin.settings'); ?>" class="dropdown-toggle no-arrow <?= current_route_name() == 'admin.settings' ? 'active' : '' ?>">
                    <?php endif; ?>
                        <span class="micon dw dw-settings"></span>
                        <span class="mtext">Ajustes</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
