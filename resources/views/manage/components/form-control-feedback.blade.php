@if ($errors->has($field))
    <p class="form-control-feedback" role="alert">
        {{ $errors->first($field) }}
    </p>
@else
    @foreach($errors->all() as $errorField => $error)
        @if ( isset($isArrayField) && $isArrayField == true && strpos($error, $field))
            <p class="form-control-feedback has-danger form-control-feedback" role="alert" style="color: red">
                {{ isset($customMessage) ? $customMessage : '' }}
            </p>
        @endif
    @endforeach
@endif
