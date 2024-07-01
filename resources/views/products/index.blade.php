@extends('layouts.app')

@section('content')
    <div class="row">
        @foreach ($products as $product)
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ $product->description }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <form action="{{ route('cart-items.add') }}" method="POST" class="ms-3">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">افزودن به سبد</button>
                                </form>
                                <form action="{{ route('products.checkout') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">خرید مستقیم</button>
                                </form>
                            </div>
                            <small class="text-muted">{{ number_format($product->price) }} تومان</small>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
