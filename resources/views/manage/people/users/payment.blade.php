@extends('manage.layouts.admin', [ 'pageTitle' => "Payment of User - {$user->formatted_phone_number}" ])

@section('content')
    <div class="row">
        <div class="col-lg-9">
    @component('manage.components.portlet', [
        'headText' => 'Payment',
        'headIcon' => 'flaticon-lock',
        'formAction' => route('manage.people.users.payment', ['id' => $user->id]),
        'formMethod' => 'post',
    ])
                <fieldset class="m-form__section">
                    <div class="form-group m-form__group row">
                        <div class="col-sm-6 m-form__group-sub">
                            <label class="form-control-label">User : {{$user->profile->full_name}}</label>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-sm-6 m-form__group-sub {{ $errors->has('paid_amount') ? 'has-danger' : '' }}">
                            <label class="form-control-label">Paid Amount *</label>
                            <input
                                type="number"
                                name="paid_amount"
                                min=0.01
                                step=0.01
                                class="form-control m-input"
                            >
                           @include('manage.components.form-control-feedback', [ 'field' => 'paid_amount' ])
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-sm-6 m-form__group-sub">
                            <label class="form-control-label">Remarks</label>
                            <textarea
                                rows = "3"
                                name="remarks"
                                class="form-control m-input"
                            ></textarea>
                        </div>
                    </div>
                </fieldset>

                @slot('formActionsLeft')
                    <button
                        type="submit"
                        class="btn btn-brand"
                    >
                        Submit
                    </button>
                    <button
                        type="reset"
                        class="btn btn-secondary"
                    >
                        Reset
                    </button>
                @endslot

                @slot('formActionsRight')
                    <a
                        class="btn btn-link m-link m--font-bold text-muted"
                        href="{{ route('manage.people.users.list') }}"
                    >
                        Cancel
                    </a>
                @endslot
            @endcomponent
        </div>
    </div>
@endsection
