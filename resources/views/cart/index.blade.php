@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="my-4">سبد خرید شما</h2>
        <div class="row">
            <div class="col-md-8">
                @foreach ($cartItems as $item)
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{ $item->image }}" class="img-fluid" alt="{{ $item->name }}">
                                </div>
                                <div class="col-md-8">
                                    <h5 class="card-title">{{ $item->name }}</h5>
                                    <p class="card-text">{{ $item->description }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <div class="input-group mb-3" style="direction: ltr">
                                                <button type="submit" class="btn btn-sm btn-outline-secondary">بروزرسانی</button>
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->stock }}"
                                                       class="form-control form-control-sm d-inline-block w-auto">
                                            </div>

                                        </form>
                                        <form action="{{ route('cart.delete', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                                        </form>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">مبلغ محصول: {{ number_format($item->price) }} تومان</small>
                                        @if($item->quantity > 1)
                                            <small class="text-muted">مبلغ {{ $item->quantity }} محصول: {{ number_format($item->price * $item->quantity) }} تومان</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">خلاصه سفارش</h5>
                        <ul class="list-group mb-3 p-0">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>مبلغ کل</span>
                                <strong>{{ number_format($subtotal) }} تومان</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>هزینه حمل</span>
                                <strong>{{ number_format($transportationCosts) }} تومان</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>مبلغ قابل پرداخت</span>
                                <strong>{{ number_format($total) }} تومان</strong>
                            </li>
                            <!-- Add more details if needed -->
                        </ul>
                        <form action="{{ route('cart.checkout') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="payment_method">روش پرداخت</label>
                                <select class="form-control" id="payment_method" name="payment_method">
                                    <option value="online">آنلاین</option>
                                    <option value="cash">نقدی</option>
                                    <!-- Add more payment methods if needed -->
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">پرداخت</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
