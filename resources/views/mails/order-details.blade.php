<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Order Details')</title>
</head>
<body>
<h1>سلام، {{ $user->name }}</h1>
<p>سفارش شما با موفقیت انجام شد.</p>
<p><strong>شماره سفارش:</strong> {{ $order->id }}</p>
<p><strong>مبلغ سفارش:</strong> {{ number_format($order->amount) }}</p>
<p>
    <a href="{{ route('orders.show', $order->id) }}">برای مشاهده سفارش خود اینجا کلیک کنید</a>
</p>
<p>از خرید شما متشکریم!</p>
</body>
</html>
