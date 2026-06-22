@extends('layout.master')

@section('title')
ملیسان | فعالیت‌های دانشجویان
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/style-activities.css')}}">
@endsection

@section('mohtava')
<div class="activities-container">
    <div class="activities-header">
        <h4 class="activities-title">فعالیت دانشجویان در درس : <span>فرسایش بادی ۴۰۴۲</span></h4>
        <div class="activity-controls">
            <div class="filter-group">
                <select class="filter-select" id="dayFilter">
                    <option value="1">یک روز</option>
                    <option value="2">دو روز</option>
                    <option value="3">سه روز</option>
                    <option value="5">پنج روز</option>
                    <option value="7">یک هفته</option>
                    <option value="14">دو هفته</option>
                    <option value="30">یک ماه</option>
                </select>
            </div>
            <button class="show-activity-btn" id="showActivityBtn">
                <i class="fas fa-eye"></i>
                نمایش فعالیت
            </button>
        </div>
    </div>

    <div class="search-wrapper">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" id="searchInput" placeholder="جستجوی نام دانشجو...">
            <button class="search-clear" id="clearSearch" style="display: none;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="search-result" id="searchResult"></div>
    </div>

    <div class="table-wrapper">
        <table class="activities-table" id="activitiesTable">
            <thead>
                <tr>
                    <th rowspan="2" style="vertical-align: middle; min-width: 120px;">نام</th>
                    <th colspan="5" class="blue-header">فعالیت‌های انجام شده</th>
                    <th colspan="5" class="red-header">فعالیت‌های بازه انتخابی</th>
                </tr>
                <tr>
                    <th>
                        گزارش
                        <span class="sort-buttons">
                            <i class="fas fa-chevron-up sort-btn" data-column="1" data-order="asc"></i>
                            <i class="fas fa-chevron-down sort-btn" data-column="1" data-order="desc"></i>
                        </span>
                    </th>
                    <th>
                        سوال
                        <span class="sort-buttons">
                            <i class="fas fa-chevron-up sort-btn" data-column="2" data-order="asc"></i>
                            <i class="fas fa-chevron-down sort-btn" data-column="2" data-order="desc"></i>
                        </span>
                    </th>
                    <th>
                        تکلیف
                        <span class="sort-buttons">
                            <i class="fas fa-chevron-up sort-btn" data-column="3" data-order="asc"></i>
                            <i class="fas fa-chevron-down sort-btn" data-column="3" data-order="desc"></i>
                        </span>
                    </th>
                    <th>
                        داوری
                        <span class="sort-buttons">
                            <i class="fas fa-chevron-up sort-btn" data-column="4" data-order="asc"></i>
                            <i class="fas fa-chevron-down sort-btn" data-column="4" data-order="desc"></i>
                        </span>
                    </th>
                    <th>
                        خودآزمایی
                        <span class="sort-buttons">
                            <i class="fas fa-chevron-up sort-btn" data-column="5" data-order="asc"></i>
                            <i class="fas fa-chevron-down sort-btn" data-column="5" data-order="desc"></i>
                        </span>
                    </th>
                    <th>
                        گزارش
                        <span class="sort-buttons">
                            <i class="fas fa-chevron-up sort-btn" data-column="6" data-order="asc"></i>
                            <i class="fas fa-chevron-down sort-btn" data-column="6" data-order="desc"></i>
                        </span>
                    </th>
                    <th>
                        سوال
                        <span class="sort-buttons">
                            <i class="fas fa-chevron-up sort-btn" data-column="7" data-order="asc"></i>
                            <i class="fas fa-chevron-down sort-btn" data-column="7" data-order="desc"></i>
                        </span>
                    </th>
                    <th>
                        تکلیف
                        <span class="sort-buttons">
                            <i class="fas fa-chevron-up sort-btn" data-column="8" data-order="asc"></i>
                            <i class="fas fa-chevron-down sort-btn" data-column="8" data-order="desc"></i>
                        </span>
                    </th>
                    <th>
                        داوری
                        <span class="sort-buttons">
                            <i class="fas fa-chevron-up sort-btn" data-column="9" data-order="asc"></i>
                            <i class="fas fa-chevron-down sort-btn" data-column="9" data-order="desc"></i>
                        </span>
                    </th>
                    <th>
                        خودآزمایی
                        <span class="sort-buttons">
                            <i class="fas fa-chevron-up sort-btn" data-column="10" data-order="asc"></i>
                            <i class="fas fa-chevron-down sort-btn" data-column="10" data-order="desc"></i>
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr>
                    <td>احمد رضایی</td>
                    <td>۱۲</td>
                    <td>۸</td>
                    <td>۵</td>
                    <td>۳</td>
                    <td>۱۰</td>
                    <td>۴</td>
                    <td>۳</td>
                    <td>۲</td>
                    <td>۱</td>
                    <td>۵</td>
                </tr>
                <tr>
                    <td>مریم کریمی</td>
                    <td>۱۵</td>
                    <td>۱۰</td>
                    <td>۷</td>
                    <td>۴</td>
                    <td>۱۲</td>
                    <td>۶</td>
                    <td>۴</td>
                    <td>۳</td>
                    <td>۲</td>
                    <td>۷</td>
                </tr>
                <tr>
                    <td>علی محمدی</td>
                    <td>۲۰</td>
                    <td>۱۴</td>
                    <td>۹</td>
                    <td>۶</td>
                    <td>۱۵</td>
                    <td>۸</td>
                    <td>۵</td>
                    <td>۴</td>
                    <td>۳</td>
                    <td>۹</td>
                </tr>
                <tr>
                    <td>سارا احمدی</td>
                    <td>۸</td>
                    <td>۵</td>
                    <td>۳</td>
                    <td>۲</td>
                    <td>۶</td>
                    <td>۳</td>
                    <td>۲</td>
                    <td>۱</td>
                    <td>۱</td>
                    <td>۴</td>
                </tr>
                <tr>
                    <td>محمد حسینی</td>
                    <td>۱۸</td>
                    <td>۱۲</td>
                    <td>۸</td>
                    <td>۵</td>
                    <td>۱۴</td>
                    <td>۷</td>
                    <td>۴</td>
                    <td>۳</td>
                    <td>۲</td>
                    <td>۸</td>
                </tr>
                <tr>
                    <td>زهرا رضایی</td>
                    <td>۱۰</td>
                    <td>۷</td>
                    <td>۴</td>
                    <td>۳</td>
                    <td>۸</td>
                    <td>۴</td>
                    <td>۳</td>
                    <td>۲</td>
                    <td>۱</td>
                    <td>۵</td>
                </tr>
                <tr>
                    <td>رضا کریمی</td>
                    <td>۱۴</td>
                    <td>۹</td>
                    <td>۶</td>
                    <td>۴</td>
                    <td>۱۱</td>
                    <td>۵</td>
                    <td>۳</td>
                    <td>۲</td>
                    <td>۲</td>
                    <td>۶</td>
                </tr>
                <tr>
                    <td>نرگس محمدی</td>
                    <td>۲۲</td>
                    <td>۱۶</td>
                    <td>۱۰</td>
                    <td>۷</td>
                    <td>۱۸</td>
                    <td>۹</td>
                    <td>۶</td>
                    <td>۴</td>
                    <td>۳</td>
                    <td>۱۰</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script>
    // ====== جستجو ======
    const searchInput = document.getElementById('searchInput');
    const clearSearch = document.getElementById('clearSearch');
    const searchResult = document.getElementById('searchResult');
    const tableBody = document.getElementById('tableBody');
    const rows = tableBody.querySelectorAll('tr');

    searchInput.addEventListener('input', function() {
        const searchValue = this.value.toLowerCase().trim();
        let foundCount = 0;

        rows.forEach(function(row) {
            const nameCell = row.querySelector('td:first-child');
            if (nameCell) {
                const nameText = nameCell.textContent.toLowerCase();
                if (nameText.includes(searchValue)) {
                    row.style.display = '';
                    foundCount++;
                } else {
                    row.style.display = 'none';
                }
            }
        });

        // نمایش یا مخفی کردن دکمه پاک کردن
        if (searchValue.length > 0) {
            clearSearch.style.display = 'flex';
        } else {
            clearSearch.style.display = 'none';
        }

        // نمایش تعداد نتایج
        if (searchValue.length > 0) {
            searchResult.textContent = foundCount + ' نتیجه یافت شد';
            searchResult.style.display = 'block';
        } else {
            searchResult.style.display = 'none';
        }
    });

    // پاک کردن جستجو
    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
        searchInput.dispatchEvent(new Event('input'));
        searchInput.focus();
    });

    // ====== سورت ======
    document.querySelectorAll('.sort-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const column = parseInt(this.getAttribute('data-column'));
            const order = this.getAttribute('data-order');
            const tbody = document.getElementById('tableBody');
            const rows = Array.from(tbody.querySelectorAll('tr'));

            // حذف کلاس‌های active از همه دکمه‌های سورت
            document.querySelectorAll('.sort-btn').forEach(function(b) {
                b.classList.remove('active');
            });
            this.classList.add('active');

            // حذف کلاس active از دکمه مخالف
            const sibling = this.parentElement.querySelector(
                `.sort-btn[data-column="${column}"][data-order="${order === 'asc' ? 'desc' : 'asc'}"]`);
            if (sibling) {
                sibling.classList.remove('active');
            }

            rows.sort(function(a, b) {
                const aValue = a.querySelectorAll('td')[column]?.textContent.trim() || '0';
                const bValue = b.querySelectorAll('td')[column]?.textContent.trim() || '0';

                const aNum = parseFloat(aValue);
                const bNum = parseFloat(bValue);

                if (!isNaN(aNum) && !isNaN(bNum)) {
                    return order === 'asc' ? aNum - bNum : bNum - aNum;
                } else {
                    return order === 'asc' ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
                }
            });

            rows.forEach(function(row) {
                tbody.appendChild(row);
            });
        });
    });

    // ====== دکمه نمایش فعالیت ======
    document.getElementById('showActivityBtn').addEventListener('click', function() {
        const days = document.getElementById('dayFilter').value;
        alert('نمایش فعالیت‌های دانشجویان در ' + this.textContent.trim());
    });
</script>
@endsection