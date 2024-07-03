@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="my-4">جزئیات سفارش شماره: {{ $order->id }}</h2>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">محصولات سفارش</h5>
                <ul>
                    @foreach ($order->products as $product)
                        <li>
                            <div class="d-flex">
                                <div>{{ $product->name }}</div>
                                <div>: {{ $product->pivot->quantity }} عدد</div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>مبلغ کل: {{ number_format($order->amount) }} تومان</strong>
                    </div>
                    <div>
                        <strong>هزینه حمل: {{ number_format(App\Models\Order::TRANSPORTATION_COSTS) }} تومان</strong>
                    </div>
                    <div>
                        <strong>مبلغ قابل پرداخت: {{ number_format($order->amount + App\Models\Order::TRANSPORTATION_COSTS) }} تومان</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">اطلاعات تراکنش</h5>
                @foreach ($order->transactions as $transaction)
                    <div class="mb-3">
                        <strong>کد داخلی: </strong>{{ $transaction->internal_code }}<br>
                        <strong>شناسه تراکنش: </strong>{{ $transaction->transaction_id ?? 'ناموجود' }}<br>
                        <strong>مبلغ: </strong>{{ number_format($transaction->amount) }} تومان<br>
                        <strong>روش پرداخت: </strong>{{ __($transaction->payment_method) }}<br>
                        <strong>درگاه پرداخت: </strong>{{ __($transaction->gateway ?? 'ناموجود') }}<br>
                        <strong>کد مرجع: </strong>{{ $transaction->reference_id ?? 'ناموجود' }}<br>
                        <strong>وضعیت: </strong>{{ __($transaction->status) }}<br>
                        <strong>تاریخ ایجاد: </strong>{{ $transaction->created_at->format('Y-m-d H:i') }}<br>
                    </div>
                @endforeach
            </div>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-primary mt-3">بازگشت به سفارشات</a>
    </div>
@endsection
