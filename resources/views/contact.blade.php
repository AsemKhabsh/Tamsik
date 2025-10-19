<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اتصل بنا - تمسيك</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Noto Sans Arabic', sans-serif; background-color: #f8f9fa; }
        .text-primary { color: #2c5530 !important; }
        .btn-primary { background-color: #2c5530; border-color: #2c5530; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 text-primary mb-3">اتصل بنا</h1>
                <p class="lead text-muted">نحن هنا للإجابة على استفساراتكم ومساعدتكم</p>
            </div>

            <div class="row">
                <!-- Contact Form -->
                <div class="col-lg-8 mb-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-5">
                            <h3 class="text-primary mb-4">أرسل لنا رسالة</h3>
                            
                            <form action="#" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">الاسم الكامل *</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">البريد الإلكتروني *</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">رقم الهاتف</label>
                                        <input type="tel" class="form-control" id="phone" name="phone">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="subject" class="form-label">الموضوع *</label>
                                        <select class="form-select" id="subject" name="subject" required>
                                            <option value="">اختر الموضوع</option>
                                            <option value="general">استفسار عام</option>
                                            <option value="technical">مشكلة تقنية</option>
                                            <option value="content">اقتراح محتوى</option>
                                            <option value="partnership">شراكة</option>
                                            <option value="other">أخرى</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="message" class="form-label">الرسالة *</label>
                                    <textarea class="form-control" id="message" name="message" rows="6" required placeholder="اكتب رسالتك هنا..."></textarea>
                                </div>
                                
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        إرسال الرسالة
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h4 class="text-primary mb-4">معلومات التواصل</h4>
                            
                            <div class="contact-item mb-3">
                                <i class="fas fa-envelope text-primary me-3"></i>
                                <div>
                                    <strong>البريد الإلكتروني</strong><br>
                                    <a href="mailto:info@tamsik.org" class="text-decoration-none">info@tamsik.org</a>
                                </div>
                            </div>
                            
                            <div class="contact-item mb-3">
                                <i class="fas fa-phone text-primary me-3"></i>
                                <div>
                                    <strong>الهاتف</strong><br>
                                    <a href="tel:+966123456789" class="text-decoration-none">+966 12 345 6789</a>
                                </div>
                            </div>
                            
                            <div class="contact-item mb-3">
                                <i class="fas fa-map-marker-alt text-primary me-3"></i>
                                <div>
                                    <strong>العنوان</strong><br>
                                    المملكة العربية السعودية<br>
                                    الرياض
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <i class="fas fa-clock text-primary me-3"></i>
                                <div>
                                    <strong>ساعات العمل</strong><br>
                                    الأحد - الخميس: 9:00 - 17:00<br>
                                    الجمعة - السبت: مغلق
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="text-primary mb-4">تابعنا على</h4>
                            <div class="d-flex gap-3">
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="fab fa-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="fab fa-youtube"></i>
                                </a>
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="fab fa-telegram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="text-primary text-center mb-4">الأسئلة الشائعة</h3>
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq1">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                    كيف يمكنني المساهمة في المحتوى؟
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    يمكنك التواصل معنا عبر النموذج أعلاه أو البريد الإلكتروني لمناقشة إمكانية المساهمة في المحتوى.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                    هل المحتوى مجاني؟
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    نعم، جميع المحتوى على منصة تمسيك مجاني ومتاح للجميع.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                    كيف يتم مراجعة المحتوى؟
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    يتم مراجعة جميع المحتوى من قبل فريق من العلماء والمختصين قبل النشر لضمان الجودة والدقة.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
