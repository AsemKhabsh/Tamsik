@extends('admin.layout')

@section('title', 'إضافة مستخدم جديد')
@section('page-title', 'إضافة مستخدم جديد')
@section('page-description', 'إنشاء حساب مستخدم جديد في النظام')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus"></i>
                    بيانات المستخدم الجديد
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <!-- الاسم -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- البريد الإلكتروني -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- كلمة المرور -->
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- تأكيد كلمة المرور -->
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- الدور -->
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">الدور <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="">اختر الدور</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>مدير</option>
                                <option value="scholar" {{ old('role') == 'scholar' ? 'selected' : '' }}>عالم</option>
                                <option value="preacher" {{ old('role') == 'preacher' ? 'selected' : '' }}>خطيب</option>
                                <option value="thinker" {{ old('role') == 'thinker' ? 'selected' : '' }}>مفكر</option>
                                <option value="data_entry" {{ old('role') == 'data_entry' ? 'selected' : '' }}>مدخل بيانات</option>
                                <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>عضو</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- الحالة -->
                        <div class="col-md-6 mb-3">
                            <label for="is_active" class="form-label">الحالة</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    حساب نشط
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- حقول إضافية للعلماء -->
                    <div id="scholar-fields" style="display: none;">
                        <hr>
                        <h6 class="mb-3">
                            <i class="fas fa-user-graduate"></i>
                            معلومات إضافية للعالم
                        </h6>
                        
                        <div class="row">
                            <!-- اللقب العلمي -->
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">اللقب العلمي</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" 
                                       placeholder="مثال: الدكتور، الشيخ، الأستاذ">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- الصورة الشخصية -->
                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label">الصورة الشخصية</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">الحد الأقصى: 2 ميجابايت. الصيغ المدعومة: JPG, PNG, GIF</small>
                            </div>
                        </div>
                        
                        <!-- السيرة الذاتية -->
                        <div class="mb-3">
                            <label for="bio" class="form-label">السيرة الذاتية</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" name="bio" rows="4" 
                                      placeholder="نبذة مختصرة عن العالم وتخصصه ومؤهلاته">{{ old('bio') }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- أزرار الحفظ -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i>
                            العودة
                        </a>
                        <div>
                            <button type="reset" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-undo"></i>
                                إعادة تعيين
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                حفظ المستخدم
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // إظهار/إخفاء حقول العلماء
    document.getElementById('role').addEventListener('change', function() {
        const scholarFields = document.getElementById('scholar-fields');
        if (this.value === 'scholar') {
            scholarFields.style.display = 'block';
        } else {
            scholarFields.style.display = 'none';
        }
    });
    
    // تحقق من القيمة المحددة مسبقاً
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        if (roleSelect.value === 'scholar') {
            document.getElementById('scholar-fields').style.display = 'block';
        }
    });
    
    // معاينة الصورة
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // يمكن إضافة معاينة للصورة هنا
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
