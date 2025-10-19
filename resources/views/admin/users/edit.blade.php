@extends('admin.layout')

@section('title', 'تعديل المستخدم')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-user-edit"></i>
                        تعديل المستخدم: {{ $user->name }}
                    </h4>
                    <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i>
                        العودة للقائمة
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- المعلومات الأساسية -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">الاسم الكامل *</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">اللقب العلمي</label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                           value="{{ old('title', $user->title) }}" 
                                           placeholder="مثل: الدكتور، الشيخ، الأستاذ">
                                    @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">البريد الإلكتروني *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label">رقم الهاتف</label>
                                    <input type="text" class="form-control" id="phone" name="phone" 
                                           value="{{ old('phone', $user->phone) }}" 
                                           placeholder="+967-1-234567">
                                    @error('phone')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="role" class="form-label">الدور *</label>
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>مدير</option>
                                        <option value="scholar" {{ old('role', $user->role) == 'scholar' ? 'selected' : '' }}>عالم</option>
                                        <option value="preacher" {{ old('role', $user->role) == 'preacher' ? 'selected' : '' }}>خطيب</option>
                                        <option value="thinker" {{ old('role', $user->role) == 'thinker' ? 'selected' : '' }}>مفكر</option>
                                        <option value="data_entry" {{ old('role', $user->role) == 'data_entry' ? 'selected' : '' }}>مدخل بيانات</option>
                                        <option value="member" {{ old('role', $user->role) == 'member' ? 'selected' : '' }}>عضو</option>
                                    </select>
                                    @error('role')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="specialization" class="form-label">التخصص</label>
                                    <input type="text" class="form-control" id="specialization" name="specialization" 
                                           value="{{ old('specialization', $user->specialization) }}" 
                                           placeholder="مثل: الفقه، العقيدة، التفسير">
                                    @error('specialization')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="education" class="form-label">المؤهل العلمي</label>
                                    <input type="text" class="form-control" id="education" name="education" 
                                           value="{{ old('education', $user->education) }}" 
                                           placeholder="مثل: دكتوراه في الشريعة">
                                    @error('education')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="location" class="form-label">الموقع</label>
                                    <input type="text" class="form-control" id="location" name="location" 
                                           value="{{ old('location', $user->location) }}" 
                                           placeholder="مثل: صنعاء، اليمن">
                                    @error('location')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- السيرة الذاتية -->
                        <div class="form-group mb-3">
                            <label for="bio" class="form-label">السيرة الذاتية</label>
                            <textarea class="form-control" id="bio" name="bio" rows="4" 
                                      placeholder="نبذة مختصرة عن المستخدم...">{{ old('bio', $user->bio) }}</textarea>
                            <small class="text-muted">عدد الأحرف: <span id="bioCount">{{ strlen($user->bio ?? '') }}</span>/500</small>
                            @error('bio')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- الصورة الشخصية -->
                        <div class="form-group mb-3">
                            <label for="image" class="form-label">الصورة الشخصية</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            @if($user->image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $user->image) }}" alt="الصورة الحالية" 
                                         class="img-thumbnail" style="max-width: 150px;">
                                    <p class="text-muted mt-1">الصورة الحالية</p>
                                </div>
                            @endif
                            <div id="imagePreview" class="mt-2" style="display: none;">
                                <img id="previewImg" src="" alt="معاينة الصورة" 
                                     class="img-thumbnail" style="max-width: 150px;">
                                <p class="text-muted mt-1">الصورة الجديدة</p>
                            </div>
                            @error('image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- كلمة المرور -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="password" class="form-label">كلمة المرور الجديدة</label>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="اتركها فارغة إذا لم ترد تغييرها">
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                                    <input type="password" class="form-control" id="password_confirmation" 
                                           name="password_confirmation" placeholder="أعد كتابة كلمة المرور">
                                </div>
                            </div>
                        </div>

                        <!-- الحالة -->
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                       value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    حساب نشط
                                </label>
                            </div>
                        </div>

                        <!-- أزرار الحفظ -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i>
                                حفظ التغييرات
                            </button>
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary">
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
// عداد أحرف السيرة الذاتية
document.getElementById('bio').addEventListener('input', function() {
    const count = this.value.length;
    document.getElementById('bioCount').textContent = count;
    
    if (count > 500) {
        this.style.borderColor = '#dc3545';
        document.getElementById('bioCount').style.color = '#dc3545';
    } else {
        this.style.borderColor = '#ced4da';
        document.getElementById('bioCount').style.color = '#6c757d';
    }
});

// معاينة الصورة
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
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
</script>
@endsection
