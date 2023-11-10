<div
    id="m_aside_left"
    class="m-grid__item m-aside-left m-aside-left--skin-dark"
>
    <button
        id="m_aside_left_close_btn"
        class="m-aside-left-close m-aside-left-close--skin-dark"
    >
        <i class="la la-close"></i>
    </button>
    <div
        id="m_ver_menu"
        class="m-aside-menu m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark"
        m-menu-vertical="1"
        m-menu-scrollable="1"
        m-menu-dropdown-timeout="500"
    >
        <ul class="m-menu__nav m-menu__nav--dropdown-submenu-arrow">
            <li
                class="m-menu__item {{ active_class(if_route('manage.dashboard'), 'm-menu__item--active') }}"
                aria-haspopup="true"
            >
                <a
                    class="m-menu__link"
                    href="{{ route('manage.dashboard') }}"
                >
                    <i class="m-menu__link-icon flaticon-line-graph"></i>
                    <span class="m-menu__link-title">
                      <span class="m-menu__link-wrap">
                        <span class="m-menu__link-text">Statistics</span>
                      </span>
                    </span>
                </a>
            </li>

            @canany([
                \Src\Auth\Ability::MANAGE_ROOTS,
                \Src\Auth\Ability::MANAGE_ADMINISTRATORS,
            ])
                <li class="m-menu__section">
                    <h4 class="m-menu__section-text">People</h4>
                </li>
            @endCanany
            @canany([
                \Src\Auth\Ability::MANAGE_ROOTS,

            ])
                <li
                        class="m-menu__item {{ active_class(if_route_pattern('manage.people.admins.*'), 'm-menu__item--active') }}"
                        aria-haspopup="true"
                >

                    <a
                            class="m-menu__link m-m"
                            href="{{ route('manage.people.admins.list') }}"
                    >
                        <i class="m-menu__link-icon flaticon-users"></i>
                        <span class="m-menu__link-text">Admins</span>
                    </a>
                </li>
            @endCanany
            @canany([
                \Src\Auth\Ability::MANAGE_ROOTS,
                \Src\Auth\Ability::MANAGE_ADMINISTRATORS,
            ])
                @canany([
                    \Src\Auth\Ability::MANAGE_ROOTS,
                    \Src\Auth\Ability::MANAGE_USERS,
                ])
                <li
                        class="m-menu__item {{ active_class(if_route_pattern('manage.people.users.*'), 'm-menu__item--active') }}"
                        aria-haspopup="true"
                >
                    <a
                            class="m-menu__link m-m"
                            href="{{ route('manage.people.users.list') }}"
                    >
                        <i class="m-menu__link-icon flaticon-users"></i>
                        <span class="m-menu__link-text">Users</span>
                    </a>
                </li>
                @endCanany
            @endcan
        </ul>
    </div>
</div>
