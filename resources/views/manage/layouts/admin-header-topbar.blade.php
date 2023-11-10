<div
    id="m_header_topbar"
    class="m-topbar m-stack m-stack--ver m-stack--general m-stack--fluid"
>
    <div class="m-stack__item m-topbar__nav-wrapper">
        <ul class="m-topbar__nav m-nav m-nav--inline">
            <li
                class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light"
                m-dropdown-toggle="click"
            >
                <button class="m-nav__link m-dropdown__toggle">
                    <span class="m-nav__link-icon m-topbar__usericon">
                        <i class="flaticon-user-ok"></i>
                    </span>
                </button>
                <div class="m-dropdown__wrapper">
                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                    <div class="m-dropdown__inner">
                        <div class="m-dropdown__header m--align-center">
                            <div class="m-card-user">
                                <div class="m-card-user__pic">
                                    <img
                                        class="m--img-rounded m--marginless"
                                        src="{{ isset(auth()->guard('web')->user()->portrait)?auth()->guard('web')->user()->portrait->url:'' }}"
                                    >
                                </div>
                                <div class="m-card-user__details">
                                    <span class="m-card-user__name m--font-weight-500">{{ auth()->guard('web')->user()->profile ? auth()->guard('web')->user()->profile->full_name : '' }}</span>
                                    <span class="m-card-user__email m--font-weight-300">{{ auth()->guard('web')->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="m-dropdown__body">
                            <div class="m-dropdown__content">
                                <ul class="m-nav m-nav--skin-light">
                                    <li class="m-nav__section m--hide">
                                        <span class="m-nav__section-text">Section</span>
                                    </li>
                                    <li class="m-nav__item">
                                        <a class="m-nav__link" href="{{route('manage.account.profile.edit')}}">
                                            <i class="m-nav__link-icon flaticon-profile-1"></i>
                                            <span class="m-nav__link-title">
                                          <span class="m-nav__link-wrap">
                                            <span class="m-nav__link-text">Profile</span>
                                          </span>
                                        </span>
                                        </a>
                                    </li>
                                    <li class="m-nav__item">
                                        <a class="m-nav__link" href="{{route('manage.account.profile.edit-password')}}">
                                            <i class="m-nav__link-icon flaticon-lock"></i>
                                            <span class="m-nav__link-text">Password</span>
                                        </a>
                                    </li>
                                    <li class="m-nav__separator m-nav__separator--fit"></li>
                                    <li class="m-nav__item">
                                        <button
                                            class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder"
                                            form="logout-form"
                                        >
                                            Logout
                                        </button>
                                    </li>
                                </ul>
                                <form
                                    id="logout-form"
                                    class="m-form d-none"
                                    method="post"
                                    action="{{ route('manage.account.logout') }}"
                                >
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
