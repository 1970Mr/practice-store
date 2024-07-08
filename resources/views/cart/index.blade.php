@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="my-4">سبد خرید شما</h2>
        @if($cartItems->isEmpty())
            <div class="alert alert-info" role="alert">
                سبد خرید شما خالی است. لطفا محصولات مورد نظر خود را به سبد خرید اضافه کنید.
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-primary">مشاهده محصولات</a>
        @else
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

                @include('cart.partials.order-summary')
            </div>
        @endif
    </div>
@endsection
