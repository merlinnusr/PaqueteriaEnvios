<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
    @include('includes.head')
    <style>
        .dagpacket_orange {
            background: #ff8000;
        }

        .dagpacket_purple {
            background: #01297c;
        }

    </style>
</head>

<body style="background-color: #ebebeb;">
    <div>
        @include('includes.navbar')

        <main id="main" class="container-xxl">
            @yield('content')
        </main>
        <footer class="row">
            @include('includes.footer')
            @stack('scripts')

        </footer>
    </div>
</body>

</html>
