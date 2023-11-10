@extends('manage.layouts.admin', [ 'pageTitle' => 'Withdrawal Transaction Overview' ])

@section('content')
    @component('manage.components.portlet', [
        'headText' => 'Withdrawal Transaction',
        'headIcon' => 'fas fa-money-bill',
    ])

            @include('manage.components.table-filter', [ 'target' => 'categories-filter-quick-sidebar' ])
            <form>
                @csrf
            </form>
            <div class="table-responsive my-4">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th class="">ID</th>
                        <th class="">Withdrawal Amount</th>
                        <th class="">Stats</th>
                        <th class="">Request By</th>
                        <th class="">Bank Account</th>
                        <th class="">Request At</th>
                        <th class="">Status</th>
                        <th class="">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($withdrawalTransactions as $withdrawalTransaction)
                        @php
                            $approveId = "approve-withdrawal-transaction-{$withdrawalTransaction->id}-form";
                            $rejectId = "reject-withdrawal-transaction-{$withdrawalTransaction->id}-form";
                        @endphp
                        <tr class="">
                            <td class="">
                                {{ $withdrawalTransaction->id }}
                            </td>
                            <td class="">
                                RM {{ number_format((double)$withdrawalTransaction->amount,2,'.',',') }}
                            </td>
                            <td class="">
                                Wallet Balance: <span class="strong">RM {{ number_format((double)$withdrawalTransaction->user->getWalletBalance(),2,'.',',') }}</span><br>
                                Total: <span>RM {{ number_format((double)$withdrawalTransaction->user->getTotalCommissionAmount(),2,'.',',') }}</span><br>
                                Withdrew: <span>RM {{ number_format((double)$withdrawalTransaction->user->getWithdrewAmount(),2,'.',',') }}</span>
                            </td>
                            <td class="">
                                {{ $withdrawalTransaction->user->profile->full_name }}<br>
                                @if($withdrawalTransaction->user->hasUploadedFrontIc())
                                    <a class="mb-3" href="{{ $withdrawalTransaction->user->icFront->url }}" target="_blank">Front IC</a>
{{--                                    @include('manage.components.modal-image', [--}}
{{--                                        "imageUrl" => $withdrawalTransaction->user->icFront->url,--}}
{{--                                        "title" => 'Front IC.',--}}
{{--                                        "modalId" => "#modal-front-{{ $withdrawalTransaction->id }}",--}}
{{--                                    ])--}}
{{--                                    <button class="btn mb-3" data-toggle="modal" data-target="#modal-front-{{ $withdrawalTransaction->id }}">Front IC</button>--}}
{{--                                    @include('manage.components.modal-image', [--}}
{{--                                        "imageUrl" => $withdrawalTransaction->user->icFront->url,--}}
{{--                                        "title" => 'Front IC.',--}}
{{--                                        "modalId" => "#modal-front-{{ $withdrawalTransaction->id }}",--}}
{{--                                    ])--}}
                                @else
                                    <span class="text-danger">No uploaded front IC</span>
                                @endif
                                <br>

                                @if($withdrawalTransaction->user->hasUploadedBackIc())
                                    <a class="mb-3" href="{{ $withdrawalTransaction->user->icBack->url }}" target="_blank">Back IC</a>

{{--                                    <button class="btn mb-3" data-toggle="modal" data-target="#modal-back-{{ $withdrawalTransaction->id }}">back IC</button>--}}
{{--                                    @include('manage.components.modal-image', [--}}
{{--                                        "imageUrl" => $withdrawalTransaction->user->icBack->url,--}}
{{--                                        "title" => 'Back IC.',--}}
{{--                                        "modalId" => "#modal-back-{{ $withdrawalTransaction->id }}",--}}
{{--                                    ])--}}
                                @else
                                    <span class="text-danger">No uploaded back IC</span>
                                @endif

                            </td>
                            <td class="">
                                @if($withdrawalTransaction->user->bankAccount)
                                    {{ $withdrawalTransaction->user->bankAccount->bank->name }}<br>
                                    {{ $withdrawalTransaction->user->bankAccount->account_number }}<br>
                                    {{ $withdrawalTransaction->user->bankAccount->account_holder_name }}<br>
                                @endif
                            </td>
                            <td class="">
                                {{ \Carbon\Carbon::parse($withdrawalTransaction->created_at)->toDayDateTimeString() }}
                            </td>
                            <td class="">
                                {{ $withdrawalTransaction->status }}
                            </td>
                            <td class="">
{{--                                <a--}}
{{--                                    class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"--}}
{{--                                    href=""--}}
{{--                                    title="View"--}}
{{--                                >--}}
{{--                                    <i class="la la-eye"></i>--}}
{{--                                </a>--}}
                                @if($withdrawalTransaction->status == \Src\Referral\ReferralWithdrawalTransaction::STATUS_PENDING)
                                    <button
                                        class="btn m-btn m-btn--hover-success m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="Approve"
                                        form="{{ $approveId }}"
                                    >
                                        <i class="la la-check-square"></i>
                                    </button>
                                    <form
                                        id="{{ $approveId }}"
                                        class="m-form d-none"
                                        method="post"
                                        action="{{ route('manage.account.affiliate.approve', [ 'id' => $withdrawalTransaction->id ]) }}"
                                    >
                                        @csrf
                                    </form>
                                    <button
                                        class="btn m-btn m-btn--hover-success m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="Reject"
                                        form="{{ $rejectId }}"
                                    >
                                        <i class="la la-warning"></i>
                                    </button>
                                    <form
                                        id="{{ $rejectId }}"
                                        class="m-form d-none"
                                        method="post"
                                        action="{{ route('manage.account.affiliate.reject', [ 'id' => $withdrawalTransaction->id ]) }}"
                                    >
                                        @csrf
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6">
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                @include('manage.components.pagination', [ 'results' => $withdrawalTransactions ])
                                @include('manage.components.pagination-counter', ['results' => $withdrawalTransactions])
                            </div>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>

            @endcomponent
@endsection

@push('quick-sidebar')
    @component('manage.components.table-filter-quick-sidebar', [
        'id' => 'categories-filter-quick-sidebar'
    ])
        <fieldset class="m-form__section m-form__section--first">
            <div class="form-group m-form__group">
                <label>Search</label>
                <input
                    type="text"
                    class="form-control m-input"
                    name="search"
                    value="{{ request('search') }}"
                >
            </div>
            <div class="form-group m-form__group">
                <label>Status</label>
                <div class="m-checkbox-list">
                    @foreach($availableStatus as $status)
                        <label class="m-checkbox">
                            <input
                                type="radio"
                                name="status"
                                value="{{ $status }}"
                            >
                            {{ $status }}
                            <span></span>
                        </label>
                    @endforeach
                </div>
            </div>
        </fieldset>
    @endcomponent
@endpush
