 <style>
.back-btn {
    background: linear-gradient(135deg, #1e6f9f, #0b4a6e);
}

.back-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(30, 111, 159, 0.4);
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
</style>
<a href="{{ route('view.skill',$course->id) }}" class="back-action-btn back-btn">
    <i class="fas fa-arrow-right"></i>
</a>
