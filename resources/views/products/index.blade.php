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
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">افزودن به سبد</button>
                                </form>
                            </div>
                            @if ($product->hasCoupon())
                                <small class="text-muted">
                                    <span style="text-decoration: line-through;">{{ number_format($product->price) }} تومان</span>
                                    <br>
                                    <span>{{ number_format($product->discountedPrice()) }} تومان</span>
                                </small>
                            @else
                                <small class="text-muted">{{ number_format($product->price) }} تومان</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
