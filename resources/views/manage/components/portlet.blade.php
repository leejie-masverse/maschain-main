@php
    $unair = $unair ?? false;
    $responsiveMobile = $responsiveMobile ?? false;
    $responsiveMobileAndTablet = $responsiveMobile ?? false;

    $headText = $headText ?? false;
    $headIcon = $headIcon ?? false;
    $headTools = $headTools ?? false;

    $formClass = implode(' ', isset($formClass) ? (array)$formClass : [] );
    $formAction = $formAction ?? false;
    $formMethod = $formMethod ?? 'post';
    $formActionsRight = $formActionsRight ?? false;
    $formXlColOffset = $formXlColOffset ?? 1;
    $formFiles = $formFiles ?? false;
    $formId=$formId??'';
@endphp
<section class="m-portlet {{ $unair ? 'm-portlet--unair' : '' }} {{ $responsiveMobile ? 'm-portlet--responsive-mobile' : '' }} {{ $responsiveMobileAndTablet ? 'm-portlet--responsive-tablet-and-mobile' : '' }}">
    @if ($headText)
        <header class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    @if ($headIcon)
                        <span class="m-portlet__head-icon">
                            <i class="{{ $headIcon }}"></i>
                        </span>
                    @endif
                    <h3 class="m-portlet__head-text">{{ $headText }}</h3>
                </div>
            </div>
            @if ($headTools)
                <div class="m-portlet__head-tools">
                    {{ $headTools }}
                </div>
            @endif
        </header>
    @endif
    @if ($formAction)
        <form
            class="m-form {{ $formClass }}"
            method="{{ $formMethod === 'get' ? 'get' : 'post' }}"
            action="{{ $formAction }}"
            id="{{ $formId }}"
            {!! $formFiles ? 'enctype="multipart/form-data"' : ''  !!}
        >
            @unless($formMethod === 'get')
                @method($formMethod)
                @csrf
            @endif
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-xl-{{ 12 - $formXlColOffset * 2 }} offset-xl-{{ $formXlColOffset }}">
                        {{ $slot }}
                    </div>
                </div>
            </div>
            <footer class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions--solid">
                    <div class="row">
                        <div class="col-8 col-xl-{{ 6 - $formXlColOffset }} offset-xl-{{ $formXlColOffset }}">
                            {{ $formActionsLeft }}
                        </div>
                        @if ($formActionsRight)
                            <div class="col-4 col-xl-{{ 6 - $formXlColOffset }} m--align-right">
                                {{ $formActionsRight }}
                            </div>
                        @endif
                    </div>
                </div>
            </footer>
        </form>
    @else
        <div class="m-portlet__body">
            {{ $slot }}
        </div>
    @endif
</section>
