<div class="left-side-bar">
    <div class="brand-logo">
        <a href="<?= route_to('user.home') ?>" style="padding-left: 2em;">
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
                <li>
                    <a href="<?= route_to('user.home'); ?>" class="dropdown-toggle no-arrow <?= current_route_name() == 'user.home' ? 'active' : '' ?>">
                        <span class="micon dw dw-home"></span>
                        <span class="mtext">Votación</span>
                    </a>
                </li>
                <li>
                    <a href="<?= route_to('user.my.vote'); ?>" class="dropdown-toggle no-arrow <?= current_route_name() == 'categories' ? 'active' : '' ?>">
                        <span class="micon dw dw-clipboard1"></span>
                        <span class="mtext">Mi Voto</span>
                    </a>
                </li>
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
                    <a href="<?= route_to('user.profile'); ?>" class="dropdown-toggle no-arrow <?= current_route_name() == 'user.profile' ? 'active' : '' ?>">
                        <span class="micon dw dw-user"></span>
                        <span class="mtext">Perfil</span>
                    </a>
                </li>
                <li>
                    <a href="<?= route_to('user.settings'); ?>" class="dropdown-toggle no-arrow <?= current_route_name() == 'user.settings' ? 'active' : '' ?>">
                        <span class="micon dw dw-settings"></span>
                        <span class="mtext">Ajustes</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
