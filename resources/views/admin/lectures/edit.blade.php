@extends('admin.layout')

@section('title', 'تعديل المحاضرة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-microphone"></i>
                        تعديل المحاضرة: {{ $lecture->title }}
                    </h4>
                    <a href="{{ route('admin.lectures') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i>
                        العودة للقائمة
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.lectures.update', $lecture) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- عنوان المحاضرة -->
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">عنوان المحاضرة *</label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                           value="{{ old('title', $lecture->title) }}" required>
                                    @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- الحالة -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">الحالة *</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="scheduled" {{ old('status', $lecture->status) == 'scheduled' ? 'selected' : '' }}>مجدولة</option>
                                        <option value="completed" {{ old('status', $lecture->status) == 'completed' ? 'selected' : '' }}>مكتملة</option>
                                        <option value="cancelled" {{ old('status', $lecture->status) == 'cancelled' ? 'selected' : '' }}>ملغية</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- المحاضر -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="speaker" class="form-label">المحاضر *</label>
                                    <input type="text" class="form-control" id="speaker" name="speaker"
                                           value="{{ old('speaker', $lecture->speaker ? $lecture->speaker->name : '') }}" required>
                                    @error('speaker')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- المكان -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="location" class="form-label">المكان *</label>
                                    <input type="text" class="form-control" id="location" name="location" 
                                           value="{{ old('location', $lecture->location) }}" required>
                                    @error('location')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- تاريخ ووقت المحاضرة -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="scheduled_at" class="form-label">تاريخ ووقت المحاضرة *</label>
                                    <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at" 
                                           value="{{ old('scheduled_at', $lecture->scheduled_at ? $lecture->scheduled_at->format('Y-m-d\TH:i') : '') }}" required>
                                    @error('scheduled_at')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- المدة -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="duration" class="form-label">المدة (بالدقائق)</label>
                                    <input type="number" class="form-control" id="duration" name="duration" 
                                           value="{{ old('duration', $lecture->duration) }}" min="15" max="300">
                                    @error('duration')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- التصنيف -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="category" class="form-label">التصنيف</label>
                                    <select class="form-control" id="category" name="category">
                                        <option value="">اختر التصنيف</option>
                                        <option value="aqeedah" {{ old('category', $lecture->category) == 'aqeedah' ? 'selected' : '' }}>العقيدة</option>
                                        <option value="fiqh" {{ old('category', $lecture->category) == 'fiqh' ? 'selected' : '' }}>الفقه</option>
                                        <option value="tafseer" {{ old('category', $lecture->category) == 'tafseer' ? 'selected' : '' }}>التفسير</option>
                                        <option value="hadith" {{ old('category', $lecture->category) == 'hadith' ? 'selected' : '' }}>الحديث</option>
                                        <option value="seerah" {{ old('category', $lecture->category) == 'seerah' ? 'selected' : '' }}>السيرة</option>
                                        <option value="akhlaq" {{ old('category', $lecture->category) == 'akhlaq' ? 'selected' : '' }}>الأخلاق</option>
                                        <option value="other" {{ old('category', $lecture->category) == 'other' ? 'selected' : '' }}>أخرى</option>
                                    </select>
                                    @error('category')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- السعة -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="capacity" class="form-label">السعة (عدد الحضور)</label>
                                    <input type="number" class="form-control" id="capacity" name="capacity" 
                                           value="{{ old('capacity', $lecture->capacity) }}" min="10" max="10000">
                                    @error('capacity')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- الوصف -->
                        <div class="form-group mb-3">
                            <label for="description" class="form-label">وصف المحاضرة</label>
                            <textarea class="form-control" id="description" name="description" rows="4" 
                                      placeholder="وصف مختصر عن محتوى المحاضرة...">{{ old('description', $lecture->description) }}</textarea>
                            <small class="text-muted">عدد الأحرف: <span id="descCount">{{ strlen($lecture->description ?? '') }}</span>/1000</small>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- الملف الصوتي -->
                        <div class="form-group mb-3">
                            <label for="audio_file" class="form-label">الملف الصوتي</label>
                            <input type="file" class="form-control" id="audio_file" name="audio_file" accept="audio/*">
                            @if($lecture->audio_file)
                                <div class="mt-2">
                                    <audio controls class="w-100">
                                        <source src="{{ asset('storage/' . $lecture->audio_file) }}" type="audio/mpeg">
                                        متصفحك لا يدعم تشغيل الملفات الصوتية.
                                    </audio>
                                    <p class="text-muted mt-1">الملف الصوتي الحالي</p>
                                </div>
                            @endif
                            @error('audio_file')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- معلومات إضافية -->
                        <div class="form-group mb-3">
                            <label for="additional_info" class="form-label">معلومات إضافية</label>
                            <textarea class="form-control" id="additional_info" name="additional_info" rows="3" 
                                      placeholder="أي معلومات إضافية عن المحاضرة...">{{ old('additional_info', $lecture->additional_info) }}</textarea>
                            @error('additional_info')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- أزرار الحفظ -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i>
                                حفظ التغييرات
                            </button>
                            <a href="{{ route('admin.lectures') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                                إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// عداد أحرف الوصف
document.getElementById('description').addEventListener('input', function() {
    const count = this.value.length;
    document.getElementById('descCount').textContent = count;
    
    if (count > 1000) {
        this.style.borderColor = '#dc3545';
        document.getElementById('descCount').style.color = '#dc3545';
    } else {
        this.style.borderColor = '#ced4da';
        document.getElementById('descCount').style.color = '#6c757d';
    }
});

// التحقق من صحة التاريخ
document.getElementById('scheduled_at').addEventListener('change', function() {
    const selectedDate = new Date(this.value);
    const now = new Date();
    
    if (selectedDate < now) {
        alert('لا يمكن جدولة محاضرة في الماضي');
        this.value = '';
    }
});
</script>
@endsection
