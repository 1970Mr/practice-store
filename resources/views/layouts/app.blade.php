@inject('cart' , 'App\Services\Cart\Cart')
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فروشگاه آنلاین</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            direction: rtl;
            text-align: right;
        }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">فروشگاه آنلاین</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="تغییر ناوبری">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.index') }}">محصولات</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('purchases.index') }}">خریدها</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('transactions.index') }}">تراکنش‌ها</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('membership-plans.index') }}">پلن‌های عضویت</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cart.index') }}">
                        سبد خرید
                        @if(isset($cart) && $cart->itemCount() > 0)
                            <span class="badge bg-danger">{{ $cart->itemCount() }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-4">
    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (isset($success) || session('success'))
        Swal.fire({
            title: "@lang('Success!')",
            text: "{{ $success ?? session('success') }}",
            icon: "success"
        });
        @elseif (isset($error) || session('error'))
        Swal.fire({
            title: "@lang('Error!')",
            text: "{{ $error ?? session('error') }}",
            icon: "error"
        });
        @endif
    });
</script>
</body>
</html>
