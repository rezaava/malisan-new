@extends('layout.master')

@section('title')
ملیسان | کاربران
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('css/style-index.css') }}">

<style>
    .limited-users-page {
        padding: 35px 20px;
        direction: rtl;
    }

    .limited-users-card {
        max-width: 1200px;
        margin: 0 auto;
        background: #fff;
        border-radius: 18px;
        padding: 28px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.07);
        border: 1px solid #eee;
    }

    .limited-users-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .limited-users-title {
        margin: 0;
        font-size: 24px;
        font-weight: 800;
        color: #222;
    }

    .limited-users-count {
        background: #fff1f1;
        color: #d93636;
        padding: 8px 15px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 700;
    }

    .limited-users-table-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #eee;
        border-radius: 14px;
    }

    .limited-users-table {
        width: 100%;
        min-width: 750px;
        border-collapse: collapse;
        background: #fff;
    }

    .limited-users-table thead {
        background: #f7f8fa;
    }

    .limited-users-table th {
        padding: 16px 14px;
        color: #555;
        font-size: 14px;
        font-weight: 800;
        text-align: center;
        white-space: nowrap;
        border-bottom: 1px solid #eee;
    }

    .limited-users-table td {
        padding: 14px;
        text-align: center;
        color: #333;
        font-size: 14px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }

    .limited-users-table tbody tr {
        transition: 0.2s ease;
    }

    .limited-users-table tbody tr:hover {
        background: #fafafa;
    }

    .limited-users-table tbody tr:last-child td {
        border-bottom: none;
    }

    .user-name {
        font-weight: 700;
        color: #222;
    }

    .user-phone {
        direction: ltr;
        display: inline-block;
        color: #555;
    }

    .profile-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .profile-image {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #eee;
    }

    .profile-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 16px;
        border-radius: 9px;
        background: #f1f5ff;
        color: #3157c8;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        transition: 0.2s ease;
    }

    .profile-link:hover {
        background: #3157c8;
        color: #fff;
        transform: translateY(-1px);
    }

    .limit-form {
        margin: 0;
    }

    .limit-button {
        border: none;
        min-width: 125px;
        padding: 10px 17px;
        border-radius: 9px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .limit-button:hover {
        transform: translateY(-1px);
    }

    .limit-button.limited {
        background: #fff1f1;
        color: #d93636;
    }

    .limit-button.limited:hover {
        background: #d93636;
        color: #fff;
    }

    .limit-button.unlimited {
        background: #eaf8f0;
        color: #16834a;
    }

    .limit-button.unlimited:hover {
        background: #16834a;
        color: #fff;
    }

    .limit-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .empty-users {
        text-align: center;
        padding: 45px 20px;
        color: #888;
        font-size: 15px;
    }

    .ajax-message {
        position: fixed;
        top: 25px;
        left: 25px;
        z-index: 9999;
        padding: 13px 20px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: 0.25s ease;
    }

    .ajax-message.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .ajax-message.success {
        background: #eaf8f0;
        color: #16834a;
        border: 1px solid #c8ecd8;
    }

    .ajax-message.error {
        background: #fff1f1;
        color: #d93636;
        border: 1px solid #f2cccc;
    }

    @media (max-width: 768px) {
        .limited-users-page {
            padding: 20px 12px;
        }

        .limited-users-card {
            padding: 18px 12px;
            border-radius: 14px;
        }

        .limited-users-title {
            font-size: 20px;
        }

        .limited-users-header {
            margin-bottom: 18px;
        }
    }
</style>
@endsection

@section('mohtava')

<div class="limited-users-page">

    <div class="limited-users-card">

        <div class="limited-users-header">

            <h2 class="limited-users-title">
                مدیریت کاربران
            </h2>

            <div class="limited-users-count" id="usersCount">
                تعداد کاربران: {{ $users->count() }}
            </div>

        </div>

        <div class="limited-users-table-wrapper">

            <table class="limited-users-table">

                <thead>
                    <tr>
                        <th>نام و نام خانوادگی</th>
                        <th>پروفایل</th>
                        <th>شماره تماس</th>
                        <th>وضعیت محدودیت</th>
                    </tr>
                </thead>

                <tbody id="usersTableBody">

                    @forelse($users as $user)

                        <tr id="user-row-{{ $user->id }}">

                            <td>
                                <span class="user-name">
                                    {{ $user->name }} {{ $user->family }}
                                </span>
                            </td>

                            <td>

                                <div class="profile-wrapper">

                                    @if($user->profile)
                                        <img
                                            src="{{ asset($user->profile) }}"
                                            alt="profile"
                                            class="profile-image"
                                        >
                                    @endif

                                    <a
                                        href="{{ route('studentProfile', $user->id) }}"
                                        class="profile-link"
                                    >
                                        مشاهده پروفایل
                                    </a>

                                </div>

                            </td>

                            <td>
                                <span class="user-phone">
                                    {{ $user->mobile }}
                                </span>
                            </td>

                            <td>

                                <form
                                    action="{{ route('toggle-limit-user', $user->id) }}"
                                    method="POST"
                                    class="limit-form"
                                    data-user-id="{{ $user->id }}"
                                >

                                    @csrf

                                    @if($user->limited)

                                        <button
                                            type="submit"
                                            class="limit-button limited"
                                        >
                                            محدود شده
                                        </button>

                                    @else

                                        <button
                                            type="submit"
                                            class="limit-button unlimited"
                                        >
                                            محدود کردن
                                        </button>

                                    @endif

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr id="empty-row">

                            <td colspan="4">

                                <div class="empty-users">
                                    در حال حاضر کاربری وجود ندارد.
                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<div id="ajaxMessage" class="ajax-message"></div>

@endsection

@section('js')

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const forms = document.querySelectorAll('.limit-form');
        const message = document.getElementById('ajaxMessage');

        function showMessage(text, type) {

            message.textContent = text;

            message.className =
                'ajax-message ' + type + ' show';

            setTimeout(function () {

                message.classList.remove('show');

            }, 2500);
        }

        forms.forEach(function (form) {

            form.addEventListener('submit', function (event) {

                event.preventDefault();

                const button =
                    form.querySelector('.limit-button');

                if (!button) {
                    return;
                }

                button.disabled = true;

                const originalText =
                    button.textContent;

                button.textContent = 'در حال انجام...';

                fetch(form.action, {

                    method: 'POST',

                    headers: {

                        'X-CSRF-TOKEN':
                            '{{ csrf_token() }}',

                        'Accept':
                            'application/json',

                        'X-Requested-With':
                            'XMLHttpRequest'
                    },

                    body: new FormData(form)

                })
                .then(function (response) {

                    if (!response.ok) {
                        throw new Error('Request failed');
                    }

                    return response.json();

                })
                .then(function (data) {

                    if (!data.success) {
                        throw new Error(
                            data.message ||
                            'خطا در انجام عملیات'
                        );
                    }

                    /*
                     * اگر limited = 1 باشد
                     * یعنی کاربر محدود شده است.
                     */

                    if (data.limited) {

                        button.textContent =
                            'محدود شده';

                        button.classList.remove(
                            'unlimited'
                        );

                        button.classList.add(
                            'limited'
                        );

                    } else {

                        button.textContent =
                            'محدود کردن';

                        button.classList.remove(
                            'limited'
                        );

                        button.classList.add(
                            'unlimited'
                        );
                    }

                    button.disabled = false;

                    showMessage(
                        data.message,
                        'success'
                    );

                })
                .catch(function (error) {

                    console.error(error);

                    button.disabled = false;

                    button.textContent =
                        originalText;

                    showMessage(
                        'خطایی در انجام عملیات رخ داد.',
                        'error'
                    );

                });

            });

        });

    });
</script>

@endsection