@if ($errors->has($field))
    <p class="form-control-feedback-main" role="alert" style="color: red !important">
        {{ $errors->first($field) }}
    </p>
@else
    @foreach($errors->all() as $errorField => $error)
        @if ( isset($isArrayField) && $isArrayField == true && strpos($error, $field))
            <p class="form-control-feedback has-danger form-control-feedback" role="alert" style="color: red !important">
                {{ isset($customMessage) ? $customMessage : '' }}
            </p>
        @endif
    @endforeach
@endif
