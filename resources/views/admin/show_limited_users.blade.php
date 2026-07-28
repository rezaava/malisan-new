@extends('layout.master')

@section('title')
ملیسان | کاربران محدود شده
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('css/style-index.css') }}">
@endsection


@section('mohtava')

<div class="container">

    <h2>کاربران محدود شده</h2>

    <table>

        <thead>
            <tr>
                <th>نام و نام خانوادگی</th>
                <th>پروفایل</th>
                <th>شماره تماس</th>

                {{--
                <th>فعالیت‌ها</th>
                <th>رزومه</th>
                --}}

                <th>رفع محدودیت</th>
            </tr>
        </thead>


        <tbody>

            @foreach($users as $user)

                <tr>

                    <td>
                        {{ $user->name }} {{ $user->family }}
                    </td>


                    <td>
                        @if($user->profile)
                            <img src="{{ asset($user->profile) }}" alt="profile">
                        @else
                            بدون پروفایل
                        @endif
                    </td>


                    <td>
                        {{ $user->phone }}
                    </td>


                    {{--
                    <td>
                        {{ $user->activities }}
                    </td>

                    <td>
                        {{ $user->resume }}
                    </td>
                    --}}


                    <td>

                        <form action="{{ route('unlimit-user', $user->id) }}" method="POST">

                            @csrf

                            <button type="submit">
                                رفع محدودیت
                            </button>

                        </form>

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection