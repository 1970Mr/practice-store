@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="my-4">سفارشات شما</h2>
        @if($orders->isEmpty())
            <div class="alert alert-info" role="alert">
                شما هیچ سفارشی ندارید.
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-primary">مشاهده محصولات</a>
        @else
            <div class="row">
                <div class="col-md-12">
                    @foreach ($orders as $order)
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">سفارش شماره: {{ $order->id }}</h5>
                                <p class="card-text">تاریخ سفارش: {{ $order->created_at->format('Y-m-d H:i') }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
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
                                    </div>
                                    <div>
                                        <strong>مبلغ کل: {{ number_format($order->amount) }} تومان</strong>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-3">
                                    <div>
                                        <strong>هزینه حمل: {{ number_format(App\Models\Order::TRANSPORTATION_COSTS) }} تومان</strong>
                                    </div>
                                    <div>
                                        <strong>مبلغ قابل پرداخت: {{ number_format($order->amount + App\Models\Order::TRANSPORTATION_COSTS) }} تومان</strong>
                                    </div>
                                </div>
                                <div class="mt-3">
                                        @foreach ($order->transactions as $transaction)
                                        <div>
                                            <strong>وضعیت پرداخت:</strong> {{ __($transaction->status) }}<br>
                                        </div>
                                    @endforeach
                                </div>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary mt-3">جزئیات سفارش</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
