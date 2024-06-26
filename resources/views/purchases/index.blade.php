@extends('layouts.app')

@section('content')
    <h2>خریدهای من</h2>
    <div class="row my-4">
        @foreach ($purchases as $purchase)
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <img src="{{ $purchase->product->image }}" class="card-img-top" alt="{{ $purchase->product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $purchase->product->name }}</h5>
                        <p class="card-text">{{ $purchase->product->description }}</p>
                        <small class="text-muted">{{ $purchase->product->price }} تومان</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
