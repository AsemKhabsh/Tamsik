{{-- ========================================
     نماذج منبثقة موحدة (Modals)
     ======================================== --}}

{{-- Modal للتأكيد على الحذف --}}
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteConfirmModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    تأكيد الحذف
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-trash-alt fa-4x text-danger mb-3"></i>
                <h5 class="mb-3">هل أنت متأكد من الحذف؟</h5>
                <p class="text-muted mb-0" id="deleteMessage">
                    لن تتمكن من استرجاع هذا العنصر بعد الحذف
                </p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    إلغاء
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-2"></i>
                    حذف
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal للتأكيد العام --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="confirmModalLabel">
                    <i class="fas fa-question-circle me-2"></i>
                    تأكيد العملية
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-question-circle fa-4x text-primary mb-3"></i>
                <h5 class="mb-3" id="confirmTitle">هل أنت متأكد؟</h5>
                <p class="text-muted mb-0" id="confirmMessage">
                    يرجى التأكيد للمتابعة
                </p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    إلغاء
                </button>
                <button type="button" class="btn btn-primary" id="confirmActionBtn">
                    <i class="fas fa-check me-2"></i>
                    تأكيد
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal للنجاح --}}
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">
                    <i class="fas fa-check-circle me-2"></i>
                    نجحت العملية
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h5 class="mb-3">تمت العملية بنجاح!</h5>
                <p class="text-muted mb-0" id="successMessage">
                    تم تنفيذ العملية بنجاح
                </p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                    <i class="fas fa-check me-2"></i>
                    حسناً
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal للخطأ --}}
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="errorModalLabel">
                    <i class="fas fa-times-circle me-2"></i>
                    حدث خطأ
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-times-circle fa-4x text-danger mb-3"></i>
                <h5 class="mb-3">عذراً، حدث خطأ!</h5>
                <p class="text-muted mb-0" id="errorMessage">
                    حدث خطأ أثناء تنفيذ العملية
                </p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    إغلاق
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal للتحذير --}}
<div class="modal fade" id="warningModal" tabindex="-1" aria-labelledby="warningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="warningModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    تحذير
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                <h5 class="mb-3">تحذير!</h5>
                <p class="text-muted mb-0" id="warningMessage">
                    يرجى الانتباه
                </p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">
                    <i class="fas fa-check me-2"></i>
                    فهمت
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal للمعلومات --}}
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="infoModalLabel">
                    <i class="fas fa-info-circle me-2"></i>
                    معلومات
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-info-circle fa-4x text-info mb-3"></i>
                <h5 class="mb-3" id="infoTitle">معلومة</h5>
                <p class="text-muted mb-0" id="infoMessage">
                    معلومات إضافية
                </p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-info text-white" data-bs-dismiss="modal">
                    <i class="fas fa-check me-2"></i>
                    حسناً
                </button>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript للتحكم في Modals --}}
<script>
// دالة لإظهار modal الحذف
function showDeleteModal(message, onConfirm) {
    const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    document.getElementById('deleteMessage').textContent = message || 'لن تتمكن من استرجاع هذا العنصر بعد الحذف';
    
    document.getElementById('confirmDeleteBtn').onclick = function() {
        if (typeof onConfirm === 'function') {
            onConfirm();
        }
        modal.hide();
    };
    
    modal.show();
}

// دالة لإظهار modal التأكيد
function showConfirmModal(title, message, onConfirm) {
    const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    document.getElementById('confirmTitle').textContent = title || 'هل أنت متأكد؟';
    document.getElementById('confirmMessage').textContent = message || 'يرجى التأكيد للمتابعة';
    
    document.getElementById('confirmActionBtn').onclick = function() {
        if (typeof onConfirm === 'function') {
            onConfirm();
        }
        modal.hide();
    };
    
    modal.show();
}

// دالة لإظهار modal النجاح
function showSuccessModal(message) {
    const modal = new bootstrap.Modal(document.getElementById('successModal'));
    document.getElementById('successMessage').textContent = message || 'تم تنفيذ العملية بنجاح';
    modal.show();
}

// دالة لإظهار modal الخطأ
function showErrorModal(message) {
    const modal = new bootstrap.Modal(document.getElementById('errorModal'));
    document.getElementById('errorMessage').textContent = message || 'حدث خطأ أثناء تنفيذ العملية';
    modal.show();
}

// دالة لإظهار modal التحذير
function showWarningModal(message) {
    const modal = new bootstrap.Modal(document.getElementById('warningModal'));
    document.getElementById('warningMessage').textContent = message || 'يرجى الانتباه';
    modal.show();
}

// دالة لإظهار modal المعلومات
function showInfoModal(title, message) {
    const modal = new bootstrap.Modal(document.getElementById('infoModal'));
    document.getElementById('infoTitle').textContent = title || 'معلومة';
    document.getElementById('infoMessage').textContent = message || 'معلومات إضافية';
    modal.show();
}
</script>

