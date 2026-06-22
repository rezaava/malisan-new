@extends('layout.master')

@section('title')
ملیسان | مکالمات
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-chats.css')}}">
@endsection

@section('mohtava')
<div class="chat-container">
    <!-- سایدبار چت (سمت راست) -->
    <div class="chat-sidebar">
        <div class="search-wrapper">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" placeholder="جستجو...">
        </div>
        <div class="chat-list">
            <a href="#" class="chat-item active">
                <div class="chat-avatar">
                    <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="عکس">
                    <span class="online-dot"></span>
                </div>
                <div class="chat-info">
                    <div class="chat-name-row">
                        <span class="chat-course">ریاضیات پایه</span>
                        <span class="chat-code">MATH101</span>
                    </div>
                    <div class="chat-user">دانشجو: احمد رضایی</div>
                    <div class="chat-last-msg">سلام استاد وقتتون بخیر</div>
                </div>
                <div class="chat-time">۱۰:۳۰</div>
            </a>

            <a href="#" class="chat-item">
                <div class="chat-avatar">
                    <img src="https://randomuser.me/api/portraits/women/2.jpg" alt="عکس">
                    <span class="online-dot"></span>
                </div>
                <div class="chat-info">
                    <div class="chat-name-row">
                        <span class="chat-course">فیزیک عمومی</span>
                        <span class="chat-code">PHYS101</span>
                    </div>
                    <div class="chat-user">دانشجو: مریم کریمی</div>
                    <div class="chat-last-msg">ممنون از توضیحاتتون</div>
                </div>
                <div class="chat-time">۰۹:۱۵</div>
            </a>

            <a href="#" class="chat-item">
                <div class="chat-avatar">
                    <img src="https://randomuser.me/api/portraits/men/3.jpg" alt="عکس">
                    <span class="online-dot offline"></span>
                </div>
                <div class="chat-info">
                    <div class="chat-name-row">
                        <span class="chat-course">برنامه‌نویسی وب</span>
                        <span class="chat-code">WEB101</span>
                    </div>
                    <div class="chat-user">دانشجو: علی محمدی</div>
                    <div class="chat-last-msg">تکلیف جلسه قبل رو ارسال کردم</div>
                </div>
                <div class="chat-time">دیروز</div>
            </a>

            <a href="#" class="chat-item">
                <div class="chat-avatar">
                    <img src="https://randomuser.me/api/portraits/women/4.jpg" alt="عکس">
                    <span class="online-dot"></span>
                </div>
                <div class="chat-info">
                    <div class="chat-name-row">
                        <span class="chat-course">پایگاه داده</span>
                        <span class="chat-code">DB101</span>
                    </div>
                    <div class="chat-user">دانشجو: سارا احمدی</div>
                    <div class="chat-last-msg">سوالی درباره پروژه داشتم</div>
                </div>
                <div class="chat-time">دیروز</div>
            </a>

            <a href="#" class="chat-item">
                <div class="chat-avatar">
                    <img src="https://randomuser.me/api/portraits/men/5.jpg" alt="عکس">
                    <span class="online-dot offline"></span>
                </div>
                <div class="chat-info">
                    <div class="chat-name-row">
                        <span class="chat-course">هوش مصنوعی</span>
                        <span class="chat-code">AI101</span>
                    </div>
                    <div class="chat-user">دانشجو: محمد حسینی</div>
                    <div class="chat-last-msg">ممنون استاد عالی بود</div>
                </div>
                <div class="chat-time">۲ روز قبل</div>
            </a>
        </div>
    </div>

    <!-- بخش چت روم اصلی -->
    <div class="chat-main">
        <div class="chat-header">
            <div class="chat-header-info">
                <div class="chat-header-avatar">
                    <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="عکس">
                    <span class="online-dot"></span>
                </div>
                <div class="chat-header-details">
                    <div class="chat-header-name-row">
                        <span class="chat-header-course">ریاضیات پایه</span>
                        <span class="chat-header-code">MATH101</span>
                    </div>
                    <div class="chat-header-user">دانشجو: احمد رضایی</div>
                </div>
            </div>
            <div class="chat-header-actions">
                <i class="fas fa-ellipsis-v"></i>
            </div>
        </div>

        <div class="chat-messages">
            <div class="message received">
                <div class="message-content">
                    <span class="message-sender">احمد رضایی</span>
                    <p>سلام استاد وقتتون بخیر</p>
                    <span class="message-time">۱۰:۲۵</span>
                </div>
            </div>

            <div class="message sent">
                <div class="message-content">
                    <span class="message-sender">شما</span>
                    <p>سلام احمد جان! چطور هستید؟</p>
                    <span class="message-time">۱۰:۲۷</span>
                </div>
            </div>

            <div class="message received">
                <div class="message-content">
                    <span class="message-sender">احمد رضایی</span>
                    <p>خوبم استاد ممنون. یه سوال درباره تمرین جلسه قبل داشتم</p>
                    <span class="message-time">۱۰:۲۸</span>
                </div>
            </div>

            <div class="message sent">
                <div class="message-content">
                    <span class="message-sender">شما</span>
                    <p>بله بفرمایید، چه سوالی دارید؟</p>
                    <span class="message-time">۱۰:۳۰</span>
                </div>
            </div>

            <div class="message received">
                <div class="message-content">
                    <span class="message-sender">احمد رضایی</span>
                    <p>در مورد سوال سوم که مربوط به مشتق بود، روش حلش رو متوجه نشدم</p>
                    <span class="message-time">۱۰:۳۲</span>
                </div>
            </div>

            <div class="message sent">
                <div class="message-content">
                    <span class="message-sender">شما</span>
                    <p>بیایید فردا سر کلاس با هم بررسیش کنیم، راحت‌تر متوجه میشید</p>
                    <span class="message-time">۱۰:۳۵</span>
                </div>
            </div>

            <div class="message received">
                <div class="message-content">
                    <span class="message-sender">احمد رضایی</span>
                    <p>چشم استاد ممنون 🙏</p>
                    <span class="message-time">۱۰:۳۶</span>
                </div>
            </div>
        </div>

        <div class="chat-input-area">
            <div class="chat-input-wrapper">
                <i class="fas fa-smile"></i>
                <input type="text" class="chat-input" placeholder="پیام خود را بنویسید...">
                <button class="send-btn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection