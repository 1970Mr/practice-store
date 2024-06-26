@extends('layouts.app')

@section('content')
    <h2>سبد خرید</h2>
    @if ($cartItems->isEmpty())
        <p>سبد خرید شما خالی است.</p>
    @else
        <table class="table table-striped">
            <thead>
            <tr>
                <th>محصول</th>
                <th>تعداد</th>
                <th>قیمت</th>
                <th>مجموع</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($cartItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->product->price }} تومان</td>
                    <td>{{ $item->product->price * $item->quantity }} تومان</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <form action="/cart/checkout" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">پرداخت</button>
        </form>
    @endif
@endsection
