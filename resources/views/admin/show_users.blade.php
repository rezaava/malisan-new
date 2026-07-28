@extends('layout.master')

@section('title')
ملیسان | مدیریت کاربران
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('css/style-index.css') }}">
@endsection


@section('mohtava')

<!--در این کد ها فعلا بخش فعالیت و رزومه  کامنت هستند-->
<div class="container">

    <h2>لیست کاربران</h2>

    <table>
        <thead>
            <tr>
                <th>شناسه</th>
                <th>نام</th>
                <th>نام خانوادگی</th>
                <th>کد ملی</th>
                <th>نقش</th>

                {{-- 
                <th>فعالیت‌ها</th>
                <th>رزومه</th>
                --}}

                <th>وضعیت</th>
                <th>عملیات</th>
            </tr>
        </thead>

        <tbody>

            @foreach($users as $user)

                <tr>
                    <td>{{ $user->id }}</td>

                    <td>{{ $user->name }}</td>

                    <td>{{ $user->family }}</td>

                    <td>{{ $user->personal }}</td>

                    <td>{{ $user->role }}</td>


                    {{--
                    <td>
                        {{ $user->activities }}
                    </td>

                    <td>
                        {{ $user->resume }}
                    </td>
                    --}}


                    <td>
                        @if($user->active)
                            فعال
                        @else
                            محدود شده
                        @endif
                    </td>


                    <td>

                        @if($user->active)

                            <form action="{{ route('limit-user', $user->id) }}" method="POST">

                                @csrf

                                <button type="submit">
                                    محدود کردن کاربر
                                </button>

                            </form>

                        @else

                            <span>
                                کاربر محدود شده
                            </span>

                        @endif

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection