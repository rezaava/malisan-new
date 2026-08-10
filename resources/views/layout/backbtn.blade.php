<style>
.back-btn {
    background: linear-gradient(135deg, #1e6f9f, #0b4a6e);
}
.back-skill-btn {
    background: linear-gradient(135deg, #e67e22, #d35400);
}
.back-action-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    color: white;
    text-decoration: none;
}
.back-action-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}
</style>

@php
    // تشخیص وجود skill در URL (میتوانید سگمنت یا routeIs را جایگزین کنید)
    $isSkill = request()->segment(1) == 'skill'; 

    // تنظیم لینک و کلاس بر اساس شرط
    $route = $isSkill ? route('skill.view', $course->id) : route('view.coure', $course->id);
    $btnClass = $isSkill ? 'back-skill-btn' : 'back-btn';
@endphp

<a href="{{ $route }}" class="back-action-btn {{ $btnClass }}">
    <i class="fas fa-arrow-right"></i>
</a>