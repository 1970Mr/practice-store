<div class="col-md-4">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">خلاصه سفارش</h5>
            <ul class="list-group mb-3 p-0">
                @foreach($cost->getCostSummary() as $description => $costValue)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>@lang($description)</span>
                        <strong>{{ number_format($costValue) }} تومان</strong>
                    </li>
                @endforeach
                <li class="list-group-item d-flex justify-content-between">
                    <span>مبلغ قابل پرداخت</span>
                    <strong>{{ number_format($cost->calculateTotalCost()) }} تومان</strong>
                </li>
            </ul>
            @if(session('coupon'))
                <div class="alert alert-success">
                    <p>کد تخفیف "{{ session('coupon.code') }}" اعمال شد.</p>
{{--                    <ul class="small">--}}
{{--                        <li>--}}
{{--                            <span>میزان تخفیف: </span>--}}
{{--                            <span>{{ session('coupon.percent') }} درصد</span>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <span>حداکثر مقدار تخفیف: </span>--}}
{{--                            <span>{{ number_format(session('coupon.limit')) }} تومان</span>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
                    <form action="{{ route('coupon.remove') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">حذف کد تخفیف</button>
                    </form>
                </div>
            @else
                <form action="{{ route('coupon.apply') }}" method="POST" class="mb-3">
                    @csrf
                    <div class="input-group" dir="ltr">
                        <input type="text" name="coupon_code" class="form-control" placeholder="کد تخفیف خود را وارد کنید">
                        <button type="submit" class="btn btn-outline-secondary">اعمال</button>
                    </div>
                </form>
            @endif
            <form action="{{ route('transactions.checkout') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="payment_method" class="mb-2">روش پرداخت</label>
                    <select class="form-control" id="payment_method" name="payment_method">
                        @foreach($paymentMethods as $paymentMethod)
                            <option value="{{ $paymentMethod }}" @if($paymentMethod === 'online') @endif>@lang($paymentMethod)</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3" id="gateway_options">
                    <label for="payment_gateway" class="mb-2">انتخاب درگاه پرداخت</label>
                    <select class="form-control" id="payment_gateway" name="payment_gateway">
                        @foreach($paymentGateways as $paymentGateway)
                            <option value="{{ $paymentGateway }}">@lang($paymentGateway)</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3 d-none" id="card_to_card_info">
                    <label for="card_number" class="mb-2">شماره کارت مقصد</label>
                    <input type="text" class="form-control" id="card_number" value="1234-5678-9012-3456" readonly>
                    <label for="card_number" class="my-2">نام صاحب کارت</label>
                    <input type="text" class="form-control" id="card_number" value="آقا یا خانم فلانی" readonly>
                </div>
                <button type="submit" class="btn btn-primary btn-block">ثبت سفارش</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const paymentMethodSelect = document.getElementById('payment_method');
        const gatewayOptions = document.getElementById('gateway_options');
        const cardToCardInfo = document.getElementById('card_to_card_info');

        paymentMethodSelect.addEventListener('change', function () {
            if (this.value === 'online') {
                gatewayOptions.classList.remove('d-none');
                cardToCardInfo.classList.add('d-none');
            } else if (this.value === 'card_to_card') {
                cardToCardInfo.classList.remove('d-none');
                gatewayOptions.classList.add('d-none');
            } else {
                gatewayOptions.classList.add('d-none');
                cardToCardInfo.classList.add('d-none');
            }
        });
    });
</script>
