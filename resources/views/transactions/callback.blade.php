@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header {{ $status === 'success' ? 'bg-success' : 'bg-danger' }} text-white">
            نتیجه پرداخت
        </div>
        <div class="card-body">
            @if ($status === 'success')
                <h5 class="card-title">پرداخت موفق</h5>
                <p class="card-text">پرداخت شما با موفقیت انجام شد.</p>
                <p class="card-text">شناسه تراکنش: {{ ltrim($transaction_id, '0') }}</p>
                <a href="{{ route('home') }}" class="btn btn-primary">بازگشت به صفحه اصلی</a>
            @else
                <h5 class="card-title">پرداخت ناموفق</h5>
                <p class="card-text">پرداخت شما با مشکل مواجه شد. لطفا مجددا تلاش کنید.</p>
                <a href="{{ route('home') }}" class="btn btn-primary">بازگشت به صفحه اصلی</a>
            @endif
        </div>
    </div>
@endsection
