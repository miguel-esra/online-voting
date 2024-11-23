<div class="header">
    <div class="header-left">
        <div class="menu-icon bi bi-list"></div>
    </div>
    <div class="header-right">
        <div class="dashboard-setting user-notification" style="display: none;">
            <div class="dropdown">
                <a
                    class="dropdown-toggle no-arrow"
                    href="javascript:;"
                    data-toggle="right-sidebar"
                >
                    <i class="dw dw-settings2"></i>
                </a>
            </div>
        </div>
        
        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <span class="user-icon">
                        <?php if (!empty(get_voter())) : ?>
                            <img src="/images/users/default-avatar.png" alt="" class="ci-avatar-photo" />
                        <?php else : ?>
                            <img src="<?= get_user()->picture == null ? '/images/users/default-avatar.png' : '/images/users/' . get_user()->picture ?>" alt="" class="ci-avatar-photo" />
                        <?php endif; ?>
                    </span>
                    <?php if (!empty(get_voter())) : ?>
                        <span class="user-name ci-user-name"><?= get_voter()->name ?></span>
                    <?php else : ?>
                        <span class="user-name ci-user-name"><?= get_user()->name ?></span>
                    <?php endif; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    <a class="dropdown-item" href="<?= route_to('user.profile'); ?>">
                        <i class="dw dw-user1"></i> Perfil
                    </a>
                    <a class="dropdown-item" href="<?= route_to('user.logout') ?>">
                        <i class="dw dw-logout"></i> Salir
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
