<footer class="m-grid__item m-footer">
    <div class="m-container m-container--fluid m-container--full-height m-page__container">
        <div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
            <div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last">
                @php
                    $copyrightYear = 2020;
                    $thisYear = $copyrightYear;
                @endphp
                <span class="m-footer__copyright">
                    {{ $copyrightYear }}

                    &copy; {{ config('app.copyright_by') }} by
                    <a
                        class="m-link"
                        href="{{ config('app.powered_by_url') }}"
                        target="_blank"
                    >
                        {{ config('app.powered_by') }}
                    </a>
                </span>
            </div>
        </div>
    </div>
</footer>
