@extends('backend.layouts.app')

@section('content')

<div class="card">
    <form class="" action="" id="sort_orders" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('All Orders') }}</h5>
            </div>

            <div class="dropdown mb-2 mb-md-0">
                <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                    {{translate('Bulk Action')}}
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#" onclick="bulk_delete()"> {{translate('Delete selection')}}</a>
                </div>
            </div>

            <div class="col-lg-2 ml-auto">
                <select class="form-control aiz-selectpicker" name="delivery_status" id="delivery_status">
                    <option value="">{{translate('Filter by Delivery Status')}}</option>
                    <option value="pending" @if ($delivery_status == 'pending') selected @endif>{{translate('Pending')}}</option>
                    <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>{{translate('Confirmed')}}</option>
                    <option value="picked_up" @if ($delivery_status == 'picked_up') selected @endif>{{translate('Picked Up')}}</option>
                    <option value="on_the_way" @if ($delivery_status == 'on_the_way') selected @endif>{{translate('On The Way')}}</option>
                    <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>{{translate('Delivered')}}</option>
                    <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>{{translate('Cancel')}}</option>
                </select>
            </div>
            <div class="col-lg-2 ml-auto">
                <select class="form-control aiz-selectpicker" name="payment_status" id="payment_status">
                    <option value="">{{translate('Filter by Payment Status')}}</option>
                    <option value="paid"  @isset($payment_status) @if($payment_status == 'paid') selected @endif @endisset>{{translate('Paid')}}</option>
                    <option value="unpaid"  @isset($payment_status) @if($payment_status == 'unpaid') selected @endif @endisset>{{translate('Un-Paid')}}</option>
                </select>
              </div>
            <div class="col-lg-2">
                <div class="form-group mb-0">
                    <input type="text" class="aiz-date-range form-control" value="{{ $date }}" name="date" placeholder="{{ translate('Filter by date') }}" data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true" autocomplete="off">
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group mb-0">
                    <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type Order code & hit Enter') }}">
                </div>
            </div>
            <div class="col-auto">
                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">{{ translate('Filter') }}</button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <!--<th>#</th>-->
                        <th>
                            <div class="form-group">
                                <div class="aiz-checkbox-inline">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="check-all">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                            </div>
                        </th>
                        <th>{{ translate('Order Code') }}</th>
                        <th data-breakpoints="md">{{ translate('Num. of Products') }}</th>
                        <th data-breakpoints="md">{{ translate('Customer') }}</th>
                        <th data-breakpoints="md">{{ translate('Seller') }}</th>
                        <th data-breakpoints="md">{{ translate('Amount') }}</th>
                        <th data-breakpoints="md">{{ translate('Delivery Status') }}</th>
                        <th data-breakpoints="md">{{ translate('Payment method') }}</th>
                        <th data-breakpoints="md">{{ translate('Payment Status') }}</th>
                        @if (addon_is_activated('refund_request'))
                        <th>{{ translate('Refund') }}</th>
                        @endif
                        <th class="text-right" width="15%">{{translate('options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $key => $order)
                    <tr>
                        <td>
                            <div class="form-group">
                                <div class="aiz-checkbox-inline">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="check-one" name="id[]" value="{{$order->id}}">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{ $order->code }}@if($order->viewed == 0) <span class="badge badge-inline badge-info">{{translate('New')}}</span>@endif
                        </td>
                        <td>
                            {{ count($order->orderDetails) }}
                        </td>
                        <td>
                            @if ($order->user != null)
                                {{ $order->user->name }}
                            @else
                                Guest ({{ $order->guest_id }})
                            @endif
                        </td>
                        <td>
                            @if($order->shop)
                                {{ $order->shop->name }}
                            @else
                                {{ translate('Inhouse Order') }}
                            @endif
                        </td>
                        <td>
                            {{ single_price($order->grand_total) }}
                        </td>
                        <td>
                            {{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}
                        </td>
                        <td>
                            {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}
                        </td>
                        <td>
                            @if ($order->payment_status == 'paid')
                            <span class="badge badge-inline badge-success">{{translate('Paid')}}</span>
                            @else
                            <span class="badge badge-inline badge-danger">{{translate('Unpaid')}}</span>
                            @endif
                        </td>
                        @if (addon_is_activated('refund_request'))
                        <td>
                            @if (count($order->refund_requests) > 0)
                                {{ count($order->refund_requests) }} {{ translate('Refund') }}
                            @else
                                {{ translate('No Refund') }}
                            @endif
                        </td>
                        @endif
                        <td class="text-right">

                            @can('view_order_details')
                                @php
                                    $order_detail_route = route('orders.show', encrypt($order->id));
                                    if(Route::currentRouteName() == 'seller_orders.index') {
                                        $order_detail_route = route('seller_orders.show', encrypt($order->id));
                                    }
                                    else if(Route::currentRouteName() == 'pick_up_point.index') {
                                        $order_detail_route = route('pick_up_point.order_show', encrypt($order->id));
                                    }
                                    if(Route::currentRouteName() == 'inhouse_orders.index') {
                                        $order_detail_route = route('inhouse_orders.show', encrypt($order->id));
                                    }
                                @endphp
                                 <a data-toggle="modal" data-target=".bd-example-modal-lg{{$order->id}}" class="btn btn-soft-info btn-icon btn-circle btn-sm"  title="Quick View">
                                    <i class="las la-eye"></i>
                                </a>

<!----amgad--->

<div style="overflow: auto" class="modal w-100 fade bd-example-modal-lg{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content p-3">


        <div class="card">
            <div class="card-header">
                <h1 class="h2 fs-16 mb-0">{{ translate('Order Details') }}</h1>
            </div>
            <div class="card-body">
                <div class="row gutters-5">
                    <div class="col text-md-left text-center">
                    </div>
                    @php
                        $delivery_status = $order->delivery_status;
                        $payment_status = $order->payment_status;
                        $admin_user_id = App\Models\User::where('user_type', 'admin')->first()->id;
                    @endphp

                    <!--Assign Delivery Boy-->
                    @if ($order->seller_id == $admin_user_id || get_setting('product_manage_by_admin') == 1)

                        @if (addon_is_activated('delivery_boy'))
                            <div class="col-md-3 ml-auto">
                                <label for="assign_deliver_boy">{{ translate('Assign Deliver Boy') }}</label>
                                @if (($delivery_status == 'pending' || $delivery_status == 'confirmed' || $delivery_status == 'picked_up') && auth()->user()->can('assign_delivery_boy_for_orders'))
                                    <select class="form-control aiz-selectpicker" data-live-search="true"
                                        data-minimum-results-for-search="Infinity" id="assign_deliver_boy">
                                        <option value="">{{ translate('Select Delivery Boy') }}</option>
                                        @foreach ($delivery_boys as $delivery_boy)
                                            <option value="{{ $delivery_boy->id }}"
                                                @if ($order->assign_delivery_boy == $delivery_boy->id) selected @endif>
                                                {{ $delivery_boy->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" class="form-control" value="{{ optional($order->delivery_boy)->name }}"
                                        disabled>
                                @endif
                            </div>
                        @endif

                        <div class="col-md-3 ml-auto">
                            <label for="update_payment_status">{{ translate('Payment Status') }}</label>
                            @if (auth()->user()->can('update_order_payment_status'))
                                <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                                    id="update_payment_status">
                                    <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>
                                        {{ translate('Unpaid') }}
                                    </option>
                                    <option value="paid" @if ($payment_status == 'paid') selected @endif>
                                        {{ translate('Paid') }}
                                    </option>
                                </select>
                            @else
                                <input type="text" class="form-control" value="{{ $payment_status }}" disabled>
                            @endif
                        </div>
                        <div class="col-md-3 ml-auto">
                            <label for="update_delivery_status">{{ translate('Delivery Status') }}</label>
                            @if (auth()->user()->can('update_order_delivery_status') && $delivery_status != 'delivered' && $delivery_status != 'cancelled')
                                <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                                    id="update_delivery_status">
                                    <option value="pending" @if ($delivery_status == 'pending') selected @endif>
                                        {{ translate('Pending') }}
                                    </option>
                                    <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>
                                        {{ translate('Confirmed') }}
                                    </option>
                                    <option value="picked_up" @if ($delivery_status == 'picked_up') selected @endif>
                                        {{ translate('Picked Up') }}
                                    </option>
                                    <option value="on_the_way" @if ($delivery_status == 'on_the_way') selected @endif>
                                        {{ translate('On The Way') }}
                                    </option>
                                    <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>
                                        {{ translate('Delivered') }}
                                    </option>
                                    <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>
                                        {{ translate('Cancel') }}
                                    </option>
                                </select>
                            @else
                                <input type="text" class="form-control" value="{{ $delivery_status }}" disabled>
                            @endif
                        </div>
                        <div class="col-md-3 ml-auto">
                            <label for="update_tracking_code">
                                {{ translate('Tracking Code (optional)') }}
                            </label>
                            <input type="text" class="form-control" id="update_tracking_code"
                                value="{{ $order->tracking_code }}">
                        </div>
                    @endif
                </div>
                <div class="mb-3">
                    @php
                        $removedXML = '<?xml version="1.0" encoding="UTF-8"?>';
                    @endphp
                    {!! str_replace($removedXML, '', QrCode::size(100)->generate($order->code)) !!}
                </div>
                <div class="row gutters-5">
                    <div class="col text-md-left text-center">
                        @if(json_decode($order->shipping_address))
                            <address>
                                <strong class="text-main">
                                    {{ json_decode($order->shipping_address)->name }}
                                </strong><br>
                                {{ json_decode($order->shipping_address)->email }}<br>
                                {{ json_decode($order->shipping_address)->phone }}<br>
                                {{ json_decode($order->shipping_address)->address }}, {{ json_decode($order->shipping_address)->city }}, {{ json_decode($order->shipping_address)->postal_code }}<br>
                                {{ json_decode($order->shipping_address)->country }}
                            </address>
                        @else
                            <address>
                                <strong class="text-main">
                                    {{ $order->user->name }}
                                </strong><br>
                                {{ $order->user->email }}<br>
                                {{ $order->user->phone }}<br>
                            </address>
                        @endif
                        @if ($order->manual_payment && is_array(json_decode($order->manual_payment_data, true)))
                            <br>
                            <strong class="text-main">{{ translate('Payment Information') }}</strong><br>
                            {{ translate('Name') }}: {{ json_decode($order->manual_payment_data)->name }},
                            {{ translate('Amount') }}:
                            {{ single_price(json_decode($order->manual_payment_data)->amount) }},
                            {{ translate('TRX ID') }}: {{ json_decode($order->manual_payment_data)->trx_id }}
                            <br>
                            <a href="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" target="_blank">
                                <img src="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" alt=""
                                    height="100">
                            </a>
                        @endif
                    </div>
                    <div class="col-md-4 ml-auto">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="text-main text-bold">{{ translate('Order #') }}</td>
                                    <td class="text-info text-bold text-right"> {{ $order->code }}</td>
                                </tr>
                                <tr>
                                    <td class="text-main text-bold">{{ translate('Order Status') }}</td>
                                    <td class="text-right">
                                        @if ($delivery_status == 'delivered')
                                            <span class="badge badge-inline badge-success">
                                                {{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}
                                            </span>
                                        @else
                                            <span class="badge badge-inline badge-info">
                                                {{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-main text-bold">{{ translate('Order Date') }} </td>
                                    <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-main text-bold">
                                        {{ translate('Total amount') }}
                                    </td>
                                    <td class="text-right">
                                        {{ single_price($order->grand_total) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-main text-bold">{{ translate('Payment method') }}</td>
                                    <td class="text-right">
                                        {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-main text-bold">{{ translate('Additional Info') }}</td>
                                    <td class="text-right">{{ $order->additional_info }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr class="new-section-sm bord-no">
                <div class="row">
                    <div class="col-lg-12 table-responsive">
                        <table class="">
                            <thead>
                                <tr class="bg-trans-dark">
                                    <th data-breakpoints="lg" class="min-col">#</th>
                                    <th width="10%">{{ translate('Photo') }}</th>
                                    <th class="text-uppercase">{{ translate('Description') }}</th>
                                    <th data-breakpoints="lg" class="text-uppercase">{{ translate('Delivery Type') }}</th>
                                    <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                        {{ translate('Qty') }}
                                    </th>
                                    <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                        {{ translate('Price') }}</th>
                                    <th data-breakpoints="lg" class="min-col text-uppercase text-right">
                                        {{ translate('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderDetails as $key => $orderDetail)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                                <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank">
                                                    <img height="50" src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}">
                                                </a>
                                            @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                                <a href="{{ route('auction-product', $orderDetail->product->slug) }}" target="_blank">
                                                    <img height="50" src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}">
                                                </a>
                                            @else
                                                <strong>{{ translate('N/A') }}</strong>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                                <strong>
                                                    <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank"
                                                        class="text-muted">
                                                        {{ $orderDetail->product->getTranslation('name') }}
                                                    </a>
                                                </strong>
                                                <small>
                                                    {{ $orderDetail->variation }}
                                                </small>
                                                <br>
                                                <small>
                                                    @php
                                                        $product_stock = json_decode($orderDetail->product->stocks->first(), true);
                                                    @endphp
                                                    {{translate('SKU')}}: {{ $product_stock['sku'] }}
                                                </small>
                                            @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                                <strong>
                                                    <a href="{{ route('auction-product', $orderDetail->product->slug) }}" target="_blank"
                                                        class="text-muted">
                                                        {{ $orderDetail->product->getTranslation('name') }}
                                                    </a>
                                                </strong>
                                            @else
                                                <strong>{{ translate('Product Unavailable') }}</strong>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
                                                {{ translate('Home Delivery') }}
                                            @elseif ($order->shipping_type == 'pickup_point')
                                                @if ($order->pickup_point != null)
                                                    {{ $order->pickup_point->getTranslation('name') }}
                                                    ({{ translate('Pickup Point') }})
                                                @else
                                                    {{ translate('Pickup Point') }}
                                                @endif
                                            @elseif($order->shipping_type == 'carrier')
                                                @if ($order->carrier != null)
                                                    {{ $order->carrier->name }} ({{ translate('Carrier') }})
                                                    <br>
                                                    {{ translate('Transit Time').' - '.$order->carrier->transit_time.' '.translate('days') }}
                                                @else
                                                    {{ translate('Carrier') }}
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ $orderDetail->quantity }}
                                        </td>
                                        <td class="text-center">
                                            {{ single_price($orderDetail->price / $orderDetail->quantity) }}
                                        </td>
                                        <td class="text-center">
                                            {{ single_price($orderDetail->price) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="clearfix float-right">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>
                                    <strong class="text-muted">{{ translate('Sub Total') }} :</strong>
                                </td>
                                <td>
                                    {{ single_price($order->orderDetails->sum('price')) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong class="text-muted">{{ translate('Tax') }} :</strong>
                                </td>
                                <td>
                                    {{ single_price($order->orderDetails->sum('tax')) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong class="text-muted">{{ translate('Shipping') }} :</strong>
                                </td>
                                <td>
                                    {{ single_price($order->orderDetails->sum('shipping_cost')) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong class="text-muted">{{ translate('Coupon') }} :</strong>
                                </td>
                                <td>
                                    {{ single_price($order->coupon_discount) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong class="text-muted">{{ translate('TOTAL') }} :</strong>
                                </td>
                                <td class="text-muted h5">
                                    {{ single_price($order->grand_total) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="no-print text-right">
                        <a href="{{ route('invoice.download', $order->id) }}" type="button" class="btn btn-icon btn-light"><i
                                class="las la-print"></i></a>
                    </div>
                </div>

            </div>
        </div>


      </div>
    </div>
  </div>




<!---- end amgad--->

                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ $order_detail_route }}" title="{{ translate('View') }}">
                                    <i class="las la-eye"></i>
                                </a>
                            @endcan
                            <a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="{{ route('invoice.download', $order->id) }}" title="{{ translate('Download Invoice') }}">
                                <i class="las la-download"></i>
                            </a>
                            @can('delete_order')
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('orders.destroy', $order->id)}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="aiz-pagination">
                {{ $orders->appends(request()->input())->links() }}
            </div>

        </div>
    </form>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        $(document).on("change", ".check-all", function() {
            if(this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

//        function change_status() {
//            var data = new FormData($('#order_form')[0]);
//            $.ajax({
//                headers: {
//                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                },
//                url: "{{route('bulk-order-status')}}",
//                type: 'POST',
//                data: data,
//                cache: false,
//                contentType: false,
//                processData: false,
//                success: function (response) {
//                    if(response == 1) {
//                        location.reload();
//                    }
//                }
//            });
//        }

        function bulk_delete() {
            var data = new FormData($('#sort_orders')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-order-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        location.reload();
                    }
                }
            });
        }
    </script>

<script type="text/javascript">
    $('#assign_deliver_boy').on('change', function() {
        var order_id = {{ $order->id }};
        var delivery_boy = $('#assign_deliver_boy').val();
        $.post('{{ route('orders.delivery-boy-assign') }}', {
            _token: '{{ @csrf_token() }}',
            order_id: order_id,
            delivery_boy: delivery_boy
        }, function(data) {
            AIZ.plugins.notify('success', '{{ translate('Delivery boy has been assigned') }}');
        });
    });
    $('#update_delivery_status').on('change', function() {
        var order_id = {{ $order->id }};
        var status = $('#update_delivery_status').val();
        $.post('{{ route('orders.update_delivery_status') }}', {
            _token: '{{ @csrf_token() }}',
            order_id: order_id,
            status: status
        }, function(data) {
            AIZ.plugins.notify('success', '{{ translate('Delivery status has been updated') }}');
        });
    });
    $('#update_payment_status').on('change', function() {
        var order_id = {{ $order->id }};
        var status = $('#update_payment_status').val();
        $.post('{{ route('orders.update_payment_status') }}', {
            _token: '{{ @csrf_token() }}',
            order_id: order_id,
            status: status
        }, function(data) {
            AIZ.plugins.notify('success', '{{ translate('Payment status has been updated') }}');
        });
    });
    $('#update_tracking_code').on('change', function() {
        var order_id = {{ $order->id }};
        var tracking_code = $('#update_tracking_code').val();
        $.post('{{ route('orders.update_tracking_code') }}', {
            _token: '{{ @csrf_token() }}',
            order_id: order_id,
            tracking_code: tracking_code
        }, function(data) {
            AIZ.plugins.notify('success', '{{ translate('Order tracking code has been updated') }}');
        });
    });
</script>
@endsection
