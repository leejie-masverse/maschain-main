@extends('manage.layouts.admin', [ 'pageTitle' => "Show User - {$user->email}" ])

@section('content')
    @component('manage.components.portlet', [
        'headText' => 'User',
        'headIcon' => 'flaticon-people',
    ])
        @slot('headTools')
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a
                        class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill"
                        href="{{ route('manage.store.bookings.list', ["search" => $user->formatted_phone_number]) }}"
                    >
                      <span>
                            <i class="fa flaticon-bag"></i>
                            <span>View Bookings</span>
                      </span>
                    </a>
                </li>
{{--                <li class="m-portlet__nav-item">--}}
{{--                    <a--}}
{{--                        class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill"--}}
{{--                        href="{{ route('manage.reward.reward_transactions.list', ["search" => $user->email]) }}"--}}
{{--                    >--}}
{{--                      <span>--}}
{{--                            <i class="fa flaticon-gift"></i>--}}
{{--                            <span>View Rewards</span>--}}
{{--                      </span>--}}
{{--                    </a>--}}
{{--                </li>--}}
            </ul>
        @endslot
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5>Wallet Balance</h5>
                        <h3>RM {{ number_format((float)$stats['wallet_balance'], 2, '.', '') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5>Total Profit Gained</h5>
                        <h3>RM {{ number_format((float)$stats['total_commission_amount'], 2, '.', '') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5>Withdrew Amount</h5>
                        <h3>RM {{ number_format((float)$stats['withdrew_amount'], 2, '.', '') }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6 col-xs-12">
                <div class="card m-3">
                    <div class="bf-light card-body">
                        <h4 class="card-title">Referral Performance</h4>
                        @if($user->isPartner())
                        <div class="d-flex form-group">
                            <label style="width: 180px"><strong>Master Partner: </strong></label>
                            <div>{{ $stats['referred_mp'] }}</div>
                        </div>
                        <div class="d-flex form-group">
                            <label style="width: 180px"><strong>Partner: </strong></label>
                            <div>{{ $stats['referred_partner'] }}</div>
                        </div>
                        @endif
                        <div class="d-flex form-group">
                            <label style="width: 180px"><strong>User: </strong></label>
                            <div>{{ $stats['referred_l1'] }}</div>
                        </div>
                        <div class="d-flex form-group">
                            <label style="width: 180px"><strong>Lord: </strong></label>
                            <div>{{ $stats['referred_l2'] }}</div>
                        </div>
                        <div class="d-flex form-group">
                            <label style="width: 180px"><strong>Baron: </strong></label>
                            <div>{{ $stats['referred_l3'] }}</div>
                        </div>
                        <div class="d-flex form-group">
                            <label style="width: 180px"><strong>Duke: </strong></label>
                            <div>{{ $stats['referred_l4'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xs-12">
                <div class="card m-3">
                    <div class="bf-light card-body">
                        <h4 class="card-title">Sales</h4>
                        <div class="d-flex form-group">
                            <label style="width: 240px"><strong>Total Order: </strong></label>
                            <div>{{ $stats['sales_count_total'] }}</div>
                        </div>
                        <div class="d-flex form-group">
                            <label style="width: 240px"><strong>Total Spend: </strong></label>
                            <div>RM {{ number_format((float)$stats['sales_total'], 2, '.', '') }}</div>
                        </div>
                        <div class="d-flex form-group">
                            <label style="width: 240px"><strong>Total Spend / Month: </strong></label>
                            <div>RM {{ number_format((float)$stats['sales_per_month'], 2, '.', '') }}</div>
                        </div>
                        <div class="d-flex form-group">
                            <label style="width: 240px"><strong>Total Spend / Order: </strong></label>
                            <div>RM {{ number_format((float)$stats['sales_per_order'], 2, '.', '') }}</div>
                        </div>
                        <div class="d-flex form-group">
                            <label style="width: 240px"><strong>Pod Purchased (unit): </strong></label>
                            <div>{{ $stats['purchased_pod'] }}</div>
                        </div>
                        <div class="d-flex form-group">
                            <label style="width: 240px"><strong>Pod Purchased / Month (unit): </strong></label>
                            <div>{{ number_format((float)$stats['purchased_pod_per_month'], 1, '.', '') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
{{--        <div class="mt-5">--}}
{{--            <div>--}}
{{--                @if($order->products !== null && count($order->products) > 0)--}}
{{--                    <h3>Order Products</h3>--}}
{{--                    <div class="table-responsive my-4">--}}
{{--                        <table class="table table-hover table-bordered m-table--border-accent">--}}
{{--                            <thead>--}}
{{--                            <tr>--}}
{{--                                <th class="">Product</th>--}}
{{--                                <th class="">Bundled Product</th>--}}
{{--                                <th class="">Quantity</th>--}}
{{--                                <th class="">Total Amount (RM)</th>--}}
{{--                                <th class="">Action</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            @foreach($order->products as $orderProduct)--}}
{{--                                <tr class="">--}}
{{--                                    <td class="">--}}
{{--                                        <a target="_blank" href="{{ route('manage.store.product.products.edit', ['id' => $orderProduct->product_id]) }}">--}}
{{--                                            {{ $orderProduct->product->name }}--}}
{{--                                        </a>--}}
{{--                                    </td>--}}
{{--                                    <td class="">--}}
{{--                                        <a target="_blank" href="{{ route('manage.store.product.products.edit', ['id' => $orderProduct->bundled_product_id]) }}">--}}
{{--                                            {{ $orderProduct->mainProduct ? $orderProduct->mainProduct->name : '-' }}--}}
{{--                                        </a>--}}
{{--                                    </td>--}}
{{--                                    <td class="">--}}
{{--                                        {{ $orderProduct->quantity }}--}}
{{--                                    </td>--}}
{{--                                    <td class="">--}}
{{--                                        {{ $orderProduct->total_amount == null ? 'FREE' : "RM $orderProduct->total_amount" }}--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        @if($order->type !== \Src\Store\Order\Order::TYPE_NORMAL)--}}
{{--                                            <a--}}
{{--                                                class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"--}}
{{--                                                href="{{ route('manage.store.order.order_products.edit', [ 'id' => $orderProduct->id ]) }}"--}}
{{--                                                title="Edit"--}}
{{--                                            >--}}
{{--                                                <i class="la la-edit"></i>--}}
{{--                                            </a>--}}
{{--                                        @endif--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                @endif--}}
{{--            </div>--}}
{{--        </div>--}}
    @endcomponent
@endsection

