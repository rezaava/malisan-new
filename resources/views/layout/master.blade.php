<!DOCTYPE html>
<html lang="fa" dir="rtl">
@include('layout.head')
<body>
    @include('layout.header')
    <div class="header-spacer"></div>
    <div class="dashboard-wrapper">
        @if (Auth::user()->hasRole('teacher'))
            @include('layout.TeasideTe')
        @elseif (Auth::user()->hasRole('student'))
            @include('layout.asideSt')
        @endif
        <div class="main-content">
            <div class="empty-content">
                @yield('mohtava')
            </div>
            @include('layout.footer')
        </div>
    </div>
</body>
@yield('js')
</html>