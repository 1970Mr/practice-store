@extends('layouts.app')

@section('content')
    <h2>پلن‌های عضویت</h2>
    <div class="row">
        @foreach ($plans as $plan)
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $plan->name }}</h5>
                        <p class="card-text">مدت زمان: {{ $plan->duration ? $plan->duration . ' روز' : 'برای همیشه' }}</p>
                        <p class="card-text">قیمت: {{ $plan->price }} تومان</p>
                        <form action="{{ route('membership-plans.checkout') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <button type="submit" class="btn btn-primary">خرید پلن</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
