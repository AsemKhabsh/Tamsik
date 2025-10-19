@extends('admin.layout')

@section('title', 'إضافة عالم جديد')
@section('page-title', 'إضافة عالم جديد')
@section('page-description', 'إنشاء حساب عالم أو مفكر جديد في النظام')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-graduate"></i>
                    بيانات العالم الجديد
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.scholars.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- المعلومات الأساسية -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">المعلومات الأساسية</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- الاسم الكامل -->
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- اللقب العلمي -->
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">اللقب العلمي</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" 
                                           placeholder="مثال: الدكتور، الشيخ، الأستاذ، المفكر">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <!-- البريد الإلكتروني -->
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- رقم الهاتف -->
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">رقم الهاتف</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" 
                                           placeholder="+967 xxx xxx xxx">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- كلمة المرور -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">كلمة المرور</h6>
                        </div>
                        <div class="card-body">
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
                        </div>
                    </div>
                    
                    <!-- الصورة الشخصية -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">الصورة الشخصية</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="image" class="form-label">اختر الصورة</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">الحد الأقصى: 2 ميجابايت. الصيغ المدعومة: JPG, PNG, GIF</small>
                                </div>
                                <div class="col-md-6">
                                    <div id="image-preview" class="text-center" style="display: none;">
                                        <img id="preview-img" src="" alt="معاينة الصورة" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- السيرة الذاتية -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">السيرة الذاتية والتخصص</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="bio" class="form-label">السيرة الذاتية</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" 
                                          id="bio" name="bio" rows="6" 
                                          placeholder="نبذة مختصرة عن العالم، تخصصه، مؤهلاته العلمية، وإنجازاته">{{ old('bio') }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="specialization" class="form-label">التخصص الرئيسي</label>
                                    <select class="form-select @error('specialization') is-invalid @enderror" id="specialization" name="specialization">
                                        <option value="">اختر التخصص</option>
                                        <option value="aqeedah" {{ old('specialization') == 'aqeedah' ? 'selected' : '' }}>العقيدة</option>
                                        <option value="fiqh" {{ old('specialization') == 'fiqh' ? 'selected' : '' }}>الفقه</option>
                                        <option value="tafseer" {{ old('specialization') == 'tafseer' ? 'selected' : '' }}>التفسير</option>
                                        <option value="hadith" {{ old('specialization') == 'hadith' ? 'selected' : '' }}>الحديث</option>
                                        <option value="seerah" {{ old('specialization') == 'seerah' ? 'selected' : '' }}>السيرة النبوية</option>
                                        <option value="dawah" {{ old('specialization') == 'dawah' ? 'selected' : '' }}>الدعوة</option>
                                        <option value="islamic_thought" {{ old('specialization') == 'islamic_thought' ? 'selected' : '' }}>الفكر الإسلامي</option>
                                        <option value="islamic_history" {{ old('specialization') == 'islamic_history' ? 'selected' : '' }}>التاريخ الإسلامي</option>
                                        <option value="arabic_language" {{ old('specialization') == 'arabic_language' ? 'selected' : '' }}>اللغة العربية</option>
                                        <option value="general" {{ old('specialization') == 'general' ? 'selected' : '' }}>عام</option>
                                    </select>
                                    @error('specialization')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="education" class="form-label">المؤهل العلمي</label>
                                    <input type="text" class="form-control @error('education') is-invalid @enderror" 
                                           id="education" name="education" value="{{ old('education') }}" 
                                           placeholder="مثال: دكتوراه في الشريعة، ماجستير في التفسير">
                                    @error('education')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- الحالة -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">حالة الحساب</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>حساب نشط</strong>
                                    <br><small class="text-muted">يمكن للعالم تسجيل الدخول والمساهمة في الموقع</small>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- أزرار الحفظ -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.scholars') }}" class="btn btn-secondary">
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
                                حفظ العالم
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
    // معاينة الصورة
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
    
    // عداد الأحرف للسيرة الذاتية
    function setupCharacterCounter() {
        const textarea = document.getElementById('bio');
        const counter = document.createElement('small');
        counter.className = 'form-text text-muted';
        textarea.parentNode.appendChild(counter);
        
        function updateCounter() {
            const length = textarea.value.length;
            counter.textContent = `عدد الأحرف: ${length.toLocaleString()}`;
            
            if (length > 1000) {
                counter.className = 'form-text text-warning';
            } else {
                counter.className = 'form-text text-muted';
            }
        }
        
        textarea.addEventListener('input', updateCounter);
        updateCounter();
    }
    
    // تطبيق عداد الأحرف
    document.addEventListener('DOMContentLoaded', setupCharacterCounter);
    
    // تأكيد قبل المغادرة إذا كان هناك تغييرات
    let formChanged = false;
    document.querySelectorAll('input, textarea, select').forEach(element => {
        element.addEventListener('change', () => {
            formChanged = true;
        });
    });
    
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
    
    // إزالة التحذير عند الإرسال
    document.querySelector('form').addEventListener('submit', function() {
        formChanged = false;
    });
</script>
@endpush
