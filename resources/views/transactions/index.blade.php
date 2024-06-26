@extends('layouts.app')

@section('content')
    <h2>تراکنش‌ها</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>شماره تراکنش</th>
            <th>مقدار</th>
            <th>وضعیت</th>
            <th>تاریخ</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->amount }} تومان</td>
                <td>{{ $transaction->status }}</td>
                <td>{{ $transaction->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
