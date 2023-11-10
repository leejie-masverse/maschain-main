<header
    id="m_header"
    class="m-grid__item m-header"
    m-minimize-offset="200"
    m-minimize-mobile-offset="200"
>
    <div class="m-container m-container--fluid m-container--full-height">
        <div class="m-stack m-stack--ver m-stack--desktop">
            @include('manage.layouts.admin-header-brand')
            <div
                id="m_header_nav"
                class="m-stack__item m-stack__item--fluid m-header-head"
            >
                @include('manage.layouts.admin-header-menu')
                @include('manage.layouts.admin-header-topbar')
            </div>
        </div>
    </div>
</header>
