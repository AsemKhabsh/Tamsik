@extends('layouts.app')

@section('title', 'المحاضرات والدروس - تمسيك')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="page-title mb-2">
                <i class="fas fa-chalkboard-teacher me-2 text-primary"></i>
                المحاضرات والدروس
            </h1>
            <p class="text-muted fs-6">مواعيد المحاضرات والدروس في محافظات اليمن</p>
        </div>
        <div class="col-md-4 text-end">
            @auth
                @if(in_array(auth()->user()->user_type, ['admin', 'preacher', 'scholar', 'data_entry']))
                    <a href="{{ route('lectures.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>
                        إضافة محاضرة جديدة
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <!-- View Toggle Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-eye me-2"></i>
                        طريقة العرض والتصفية
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="view-toggle-buttons btn-group" role="group">
                            <button class="btn btn-outline-primary active" data-view="cards">
                                <i class="fas fa-th-large me-2"></i>
                                بطاقات
                            </button>
                            <button class="btn btn-outline-primary" data-view="table">
                                <i class="fas fa-table me-2"></i>
                                جدول
                            </button>
                            <button class="btn btn-outline-primary" data-view="calendar">
                                <i class="fas fa-calendar me-2"></i>
                                تقويم
                            </button>
                        </div>

                        <div class="filters">
                            <label class="form-label fw-semibold me-2 mb-0">
                                <i class="fas fa-filter me-1"></i>
                                الحالة:
                            </label>
                            <select class="form-control form-select d-inline-block w-auto" id="statusFilter">
                                <option value="">جميع الحالات</option>
                                <option value="upcoming">قادمة</option>
                                <option value="ongoing">جارية</option>
                                <option value="completed">مكتملة</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards View -->
    <div id="cards-view" class="view-content">
        <div class="row">
            @forelse($lectures ?? [] as $lecture)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="lecture-card">
                        <div class="lecture-status {{ $lecture->status ?? 'upcoming' }}">
                            @switch($lecture->status ?? 'upcoming')
                                @case('upcoming')
                                    <i class="fas fa-clock"></i> قادمة
                                    @break
                                @case('ongoing')
                                    <i class="fas fa-play"></i> جارية
                                    @break
                                @case('completed')
                                    <i class="fas fa-check"></i> مكتملة
                                    @break
                            @endswitch
                        </div>

                        <div class="lecture-content">
                            <h5 class="lecture-title">{{ $lecture->title ?? 'محاضرة في العقيدة' }}</h5>
                            <p class="lecture-speaker">
                                <i class="fas fa-user me-2"></i>
                                {{ $lecture->speaker->name ?? 'الشيخ محمد الحكيم' }}
                            </p>

                            <div class="lecture-details">
                                <div class="detail-item">
                                    <i class="fas fa-calendar text-primary"></i>
                                    <span>{{ $lecture->date ?? '2024-01-15' }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-clock text-primary"></i>
                                    <span>{{ $lecture->time ?? '8:00 مساءً' }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                    <span>{{ $lecture->location ?? 'مسجد النور - صنعاء' }}</span>
                                </div>
                            </div>

                            <div class="lecture-actions">
                                <a href="{{ route('lectures.show', $lecture->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-info-circle me-1"></i>
                                    التفاصيل
                                </a>
                                @if(($lecture->status ?? 'upcoming') === 'upcoming')
                                    <button class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-bell me-1"></i>
                                        تذكير
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state text-center py-5">
                        <i class="fas fa-calendar-times text-muted mb-3" style="font-size: 3rem;"></i>
                        <h4 class="text-muted">لا توجد محاضرات حالياً</h4>
                        <p class="text-muted">سيتم إضافة المحاضرات الجديدة قريباً</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Table View -->
    <div id="table-view" class="view-content d-none">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>العنوان</th>
                                <th>المحاضر</th>
                                <th>التاريخ</th>
                                <th>الوقت</th>
                                <th>المكان</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lectures ?? [] as $lecture)
                                <tr>
                                    <td>
                                        <strong>{{ $lecture->title ?? 'محاضرة في العقيدة' }}</strong>
                                    </td>
                                    <td>
                                        <i class="fas fa-user me-2"></i>
                                        {{ $lecture->speaker->name ?? 'الشيخ محمد الحكيم' }}
                                    </td>
                                    <td>{{ $lecture->date ?? '2024-01-15' }}</td>
                                    <td>{{ $lecture->time ?? '8:00 مساءً' }}</td>
                                    <td>{{ $lecture->location ?? 'مسجد النور - صنعاء' }}</td>
                                    <td>
                                        <span class="badge bg-{{ ($lecture->status ?? 'upcoming') === 'upcoming' ? 'warning' : (($lecture->status ?? 'upcoming') === 'ongoing' ? 'success' : 'secondary') }}">
                                            @switch($lecture->status ?? 'upcoming')
                                                @case('upcoming') قادمة @break
                                                @case('ongoing') جارية @break
                                                @case('completed') مكتملة @break
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if(($lecture->status ?? 'upcoming') === 'upcoming')
                                                <button class="btn btn-outline-success">
                                                    <i class="fas fa-bell"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-calendar-times text-muted mb-2" style="font-size: 2rem;"></i>
                                        <br>
                                        <span class="text-muted">لا توجد محاضرات حالياً</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Calendar View -->
    <div id="calendar-view" class="view-content d-none">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar me-2"></i>
                        تقويم المحاضرات
                    </h5>
                    <div class="calendar-controls">
                        <button class="btn btn-outline-primary btn-sm" id="prevMonth">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <span id="currentMonth" class="mx-3 fw-bold"></span>
                        <button class="btn btn-outline-primary btn-sm" id="nextMonth">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="calendar-container">
                    <div class="calendar-grid">
                        <div class="calendar-header">
                            <div class="day-header">الأحد</div>
                            <div class="day-header">الاثنين</div>
                            <div class="day-header">الثلاثاء</div>
                            <div class="day-header">الأربعاء</div>
                            <div class="day-header">الخميس</div>
                            <div class="day-header">الجمعة</div>
                            <div class="day-header">السبت</div>
                        </div>
                        <div id="calendar-days" class="calendar-days">
                            <!-- سيتم ملؤها بـ JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Legend -->
                <div class="calendar-legend mt-3">
                    <div class="d-flex justify-content-center gap-3">
                        <div class="legend-item">
                            <span class="legend-color bg-warning"></span>
                            <span>محاضرة قادمة</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color bg-success"></span>
                            <span>محاضرة جارية</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color bg-secondary"></span>
                            <span>محاضرة مكتملة</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* View Toggle Buttons */
.view-toggle-buttons .btn {
    margin-left: 0.5rem;
    border-radius: 25px;
    transition: all 0.3s ease;
}

.view-toggle-buttons .btn.active {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
}

/* Lecture Cards */
.lecture-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
    height: 100%;
}

.lecture-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.lecture-status {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: bold;
    color: white;
}

.lecture-status.upcoming {
    background: linear-gradient(135deg, #ffc107, #ff8c00);
}

.lecture-status.ongoing {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.lecture-status.completed {
    background: linear-gradient(135deg, #6c757d, #495057);
}

.lecture-content {
    padding: 20px;
    padding-top: 50px;
}

.lecture-title {
    font-size: 1.2rem;
    font-weight: bold;
    color: var(--primary-color);
    margin-bottom: 10px;
}

.lecture-speaker {
    color: #666;
    margin-bottom: 15px;
}

.lecture-details {
    margin-bottom: 20px;
}

.detail-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.detail-item i {
    width: 20px;
    margin-left: 10px;
}

.lecture-actions {
    display: flex;
    gap: 10px;
}

/* Calendar Styles */
.calendar-grid {
    width: 100%;
}

.calendar-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background: #e9ecef;
    border-radius: 8px 8px 0 0;
}

.day-header {
    background: var(--primary-color);
    color: white;
    padding: 12px;
    text-align: center;
    font-weight: bold;
    font-size: 0.9rem;
}

.calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background: #e9ecef;
    border-radius: 0 0 8px 8px;
}

.calendar-day {
    background: white;
    min-height: 80px;
    padding: 8px;
    position: relative;
    cursor: pointer;
    transition: background-color 0.2s;
}

.calendar-day:hover {
    background: #f8f9fa;
}

.calendar-day.other-month {
    background: #f8f9fa;
    color: #6c757d;
}

.calendar-day.today {
    background: #e3f2fd;
    font-weight: bold;
}

.calendar-day.has-lecture {
    background: #fff3cd;
}

.day-number {
    font-weight: bold;
    margin-bottom: 4px;
}

.lecture-indicator {
    position: absolute;
    bottom: 4px;
    right: 4px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.calendar-legend {
    border-top: 1px solid #dee2e6;
    padding-top: 15px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

/* Table Responsive */
.table th {
    border-top: none;
    font-weight: bold;
    color: var(--primary-color);
}

/* Empty State */
.empty-state {
    padding: 60px 20px;
    text-align: center;
}

.empty-state i {
    color: #dee2e6;
    margin-bottom: 20px;
}

.empty-state h4 {
    color: #6c757d;
    margin-bottom: 10px;
}

.empty-state p {
    color: #adb5bd;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View Toggle Functionality
    const viewButtons = document.querySelectorAll('.view-toggle-buttons .btn');
    const viewContents = document.querySelectorAll('.view-content');

    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetView = this.getAttribute('data-view');

            // Update active button
            viewButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Show target view
            viewContents.forEach(content => {
                content.classList.add('d-none');
                if (content.id === targetView + '-view') {
                    content.classList.remove('d-none');
                }
            });

            // Initialize calendar if calendar view is selected
            if (targetView === 'calendar') {
                initializeCalendar();
            }
        });
    });

    // Calendar functionality
    let currentDate = new Date();

    function initializeCalendar() {
        updateCalendarHeader();
        generateCalendarDays();
    }

    function updateCalendarHeader() {
        const monthNames = [
            'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
            'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
        ];

        const monthElement = document.getElementById('currentMonth');
        if (monthElement) {
            monthElement.textContent = monthNames[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
        }
    }

    function generateCalendarDays() {
        const calendarDays = document.getElementById('calendar-days');
        if (!calendarDays) return;

        calendarDays.innerHTML = '';

        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        // First day of the month
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);

        // Start from Sunday
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - firstDay.getDay());

        // Generate 42 days (6 weeks)
        for (let i = 0; i < 42; i++) {
            const date = new Date(startDate);
            date.setDate(startDate.getDate() + i);

            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';

            if (date.getMonth() !== month) {
                dayElement.classList.add('other-month');
            }

            if (isToday(date)) {
                dayElement.classList.add('today');
            }

            // Sample lecture data (replace with real data)
            if (hasLecture(date)) {
                dayElement.classList.add('has-lecture');
                const indicator = document.createElement('div');
                indicator.className = 'lecture-indicator bg-warning';
                dayElement.appendChild(indicator);
            }

            dayElement.innerHTML = `<div class="day-number">${date.getDate()}</div>` + dayElement.innerHTML;
            calendarDays.appendChild(dayElement);
        }
    }

    function isToday(date) {
        const today = new Date();
        return date.toDateString() === today.toDateString();
    }

    function hasLecture(date) {
        // Sample logic - replace with real lecture data
        return date.getDate() % 7 === 0 && date.getMonth() === currentDate.getMonth();
    }

    // Calendar navigation
    document.getElementById('prevMonth')?.addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        initializeCalendar();
    });

    document.getElementById('nextMonth')?.addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        initializeCalendar();
    });

    // Status filter
    document.getElementById('statusFilter')?.addEventListener('change', function() {
        const filterValue = this.value;
        const lectureCards = document.querySelectorAll('.lecture-card');

        lectureCards.forEach(card => {
            if (!filterValue) {
                card.closest('.col-lg-4, .col-md-6').style.display = 'block';
            } else {
                const status = card.querySelector('.lecture-status');
                if (status && status.classList.contains(filterValue)) {
                    card.closest('.col-lg-4, .col-md-6').style.display = 'block';
                } else {
                    card.closest('.col-lg-4, .col-md-6').style.display = 'none';
                }
            }
        });
    });
});
</script>
@endsection

