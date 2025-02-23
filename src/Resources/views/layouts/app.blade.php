<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> ورودیا @yield('title')</title>

    {{--  Styles  --}}
    <link rel="stylesheet" href="{{asset('css/bootstrap.rtl.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/all.css')}}">
    <link rel="stylesheet" href="{{asset('css/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

</head>
<body>

    <main class="container">
        @yield('content')
    </main>

    {{--  Javascript  --}}
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script>
        @foreach(["message", "success", "error", "info", "warning"] as $err)
            @if( Session::has($err) )
                toastr.options = { "progressBar": true };
                toastr["{{ $err }}"]("{{ session($err) }}");
            @endif
        @endforeach
    </script>

    @yield('scripts')
</body>
</html>
