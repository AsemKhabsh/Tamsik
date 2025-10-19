<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تمسيك - إعداد خطبة</title>
    <link rel="stylesheet" href="{{ asset('css/prepare-sermon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth-protection.css') }}">

    <!-- أنماط إضافية للقائمة المحمولة -->
    <style>
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block !important;
                background: none !important;
                border: none !important;
                color: #f8f9fa !important;
                font-size: 1.5rem !important;
                cursor: pointer !important;
                padding: 10px !important;
                border-radius: 4px !important;
                transition: all 0.3s ease !important;
            }

            .nav-links {
                display: none !important;
                position: absolute !important;
                top: 100% !important;
                left: 0 !important;
                right: 0 !important;
                background-color: #1a3a4a !important;
                flex-direction: column !important;
                padding: 20px !important;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2) !important;
                border-radius: 0 0 8px 8px !important;
                z-index: 9999 !important;
            }

            .nav-links.active {
                display: flex !important;
            }

            .nav-links li {
                margin: 0 !important;
                margin-bottom: 10px !important;
                width: 100% !important;
            }

            .nav-links a {
                display: block !important;
                width: 100% !important;
                text-align: center !important;
                padding: 12px 15px !important;
                border-radius: 6px !important;
                color: #f8f9fa !important;
            }
        }
    </style>

    <!-- الخطوط -->
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Scheherazade+New:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- إضافة مكتبات تصدير الملفات -->
    <script src="https://unpkg.com/docx@7.1.0/build/index.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <a href="{{ url('/') }}" class="logo">تمسيك</a>
                <p class="slogan">"والذين يمسكون بالكتاب..."</p>
                <button type="button" class="mobile-menu-toggle" aria-label="فتح القائمة" title="فتح القائمة"><i class="fas fa-bars"></i></button>
                <ul class="nav-links">
                    <li><a href="{{ url('/') }}"><i class="fas fa-home"></i> الرئيسية</a></li>
                    <li><a href="{{ route('sermons.index') }}"><i class="fas fa-book-open"></i> الخطب الجاهزة</a></li>
                    <li><a href="{{ route('sermons.create') }}" class="active"><i class="fas fa-pen"></i> إعداد خطبة</a></li>
                    <li><a href="{{ route('scholars.index') }}"><i class="fas fa-user-graduate"></i> العلماء اليمنيين</a></li>
                    <li><a href="{{ route('thinkers.index') }}"><i class="fas fa-lightbulb"></i> المفكرون والدعاة</a></li>
                    <li><a href="{{ route('lectures.index') }}"><i class="fas fa-microphone"></i> المحاضرات والدروس</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="page-header">
        <div class="container">
            <h1>إعداد خطبة</h1>
            <p>أداة مساعدة لتنظيم وإعداد خطبتك بطريقة احترافية</p>
        </div>
    </div>

    <main class="container page-content">
        <div class="prepare-sermon-container">
            <div class="sermon-form-card">
                <h2><i class="fas fa-pen-fancy"></i> إعداد خطبة جديدة</h2>
                <p class="form-intro">استخدم النموذج التالي لإعداد خطبتك بشكل منظم وفقاً للبنية الإسلامية التقليدية. سيتم حفظ ما تكتبه تلقائياً وعرض اقتراحات من المستخدمين السابقين.</p>

                <!-- مؤشر التقدم -->
                <div class="progress-indicator">
                    <div class="progress-step active" data-step="1">
                        <i class="fas fa-heading"></i>
                        <div>العنوان</div>
                    </div>
                    <div class="progress-step" data-step="2">
                        <i class="fas fa-book-open"></i>
                        <div>المقدمة</div>
                    </div>
                    <div class="progress-step" data-step="3">
                        <i class="fas fa-file-alt"></i>
                        <div>المحتوى</div>
                    </div>
                    <div class="progress-step" data-step="4">
                        <i class="fas fa-hands"></i>
                        <div>الخاتمة</div>
                    </div>
                </div>

                <form id="prepare-sermon-form" action="{{ route('sermons.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- 1. العنوان الرئيسي للخطبة -->
                    <div class="sermon-section" data-section="1">
                        <h3><i class="fas fa-heading"></i> العنوان الرئيسي للخطبة</h3>
                        <div class="form-group required">
                            <label for="main-title">العنوان الرئيسي</label>
                            <input type="text" id="main-title" name="title" placeholder="أدخل العنوان الرئيسي للخطبة" required>
                            <button type="button" class="suggest-btn" data-target="main-title" data-type="title">
                                <i class="fas fa-lightbulb"></i> اقتراحات
                            </button>
                        </div>

                        <!-- معلومات إضافية -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="sermon-date">تاريخ الخطبة</label>
                                <input type="date" id="sermon-date" name="sermon_date">
                            </div>
                            <div class="form-group">
                                <label for="sermon-occasion">المناسبة</label>
                                <select id="sermon-occasion" name="occasion">
                                    <option value="">اختر المناسبة</option>
                                    <option value="جمعة عادية">جمعة عادية</option>
                                    <option value="رمضان">رمضان</option>
                                    <option value="عيد الفطر">عيد الفطر</option>
                                    <option value="عيد الأضحى">عيد الأضحى</option>
                                    <option value="الحج">الحج</option>
                                    <option value="العشر من ذي الحجة">العشر من ذي الحجة</option>
                                    <option value="أخرى">أخرى</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- 2. المقدمة -->
                    <div class="sermon-section">
                        <h3><i class="fas fa-book-open"></i> المقدمة</h3>

                        <!-- الأثر -->
                        <div class="form-group">
                            <label for="introduction-athar">الأثر (آية قرآنية أو حديث أو قول لصحابي أو تابعي)</label>
                            <div class="textarea-container">
                                <textarea id="introduction-athar" name="introduction_athar" rows="3" placeholder="أدخل الأثر المناسب للموضوع..."></textarea>
                                <div class="textarea-actions">
                                    <button type="button" class="suggest-btn" data-target="introduction-athar" data-type="athar">
                                        <i class="fas fa-lightbulb"></i> اقتراحات
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- السجع -->
                        <div class="subsection">
                            <h4><i class="fas fa-feather-alt"></i> السجع</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="saja-topic">الموضوع</label>
                                    <input type="text" id="saja-topic" name="saja_topic" placeholder="موضوع السجع">
                                </div>
                                <div class="form-group">
                                    <label for="saja-rhyme">القافية</label>
                                    <input type="text" id="saja-rhyme" name="saja_rhyme" placeholder="القافية">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="saja-attribution">النسبة (أول شيخ كتبها)</label>
                                    <input type="text" id="saja-attribution" name="saja_attribution" placeholder="اسم الشيخ">
                                </div>
                                <div class="form-group">
                                    <label for="saja-reference">المرجع</label>
                                    <input type="text" id="saja-reference" name="saja_reference" placeholder="المرجع">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="saja-text">نص السجع</label>
                                <div class="textarea-container">
                                    <textarea id="saja-text" name="saja_text" rows="3" placeholder="أدخل نص السجع..."></textarea>
                                    <div class="textarea-actions">
                                        <button type="button" class="suggest-btn" data-target="saja-text" data-type="saja">
                                            <i class="fas fa-lightbulb"></i> اقتراحات
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- الحمد والثناء -->
                        <div class="form-group">
                            <label for="praise-text">الحمد والثناء</label>
                            <div class="textarea-container">
                                <textarea id="praise-text" name="praise_text" rows="4" placeholder="أدخل نص الحمد والثناء..."></textarea>
                                <div class="textarea-actions">
                                    <button type="button" class="suggest-btn" data-target="praise-text" data-type="praise">
                                        <i class="fas fa-lightbulb"></i> اقتراحات
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- الشهادتان -->
                        <div class="form-group">
                            <label for="shahada-text">الشهادتان</label>
                            <div class="textarea-container">
                                <textarea id="shahada-text" name="shahada_text" rows="3" placeholder="أدخل نص الشهادتين..."></textarea>
                                <div class="textarea-actions">
                                    <button type="button" class="suggest-btn" data-target="shahada-text" data-type="shahada">
                                        <i class="fas fa-lightbulb"></i> اقتراحات
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- الصلاة على النبي -->
                        <div class="form-group">
                            <label for="salawat-text">الصلاة على النبي</label>
                            <div class="textarea-container">
                                <textarea id="salawat-text" name="salawat_text" rows="3" placeholder="أدخل نص الصلاة على النبي..."></textarea>
                                <div class="textarea-actions">
                                    <button type="button" class="suggest-btn" data-target="salawat-text" data-type="salawat">
                                        <i class="fas fa-lightbulb"></i> اقتراحات
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- الوصية بالتقوى -->
                        <div class="form-group">
                            <label for="taqwa-text">الوصية بالتقوى</label>
                            <div class="textarea-container">
                                <textarea id="taqwa-text" name="taqwa_text" rows="4" placeholder="أدخل نص الوصية بالتقوى..."></textarea>
                                <div class="textarea-actions">
                                    <button type="button" class="suggest-btn" data-target="taqwa-text" data-type="taqwa">
                                        <i class="fas fa-lightbulb"></i> اقتراحات
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. أما بعد -->
                    <div class="sermon-section">
                        <h3><i class="fas fa-praying-hands"></i> أما بعد</h3>

                        <!-- الوصية بالتقوى -->
                        <div class="subsection">
                            <h4><i class="fas fa-heart"></i> الوصية بالتقوى</h4>

                            <!-- آيات قرآنية -->
                            <div class="taqwa-subsection">
                                <h5>آيات قرآنية</h5>
                                <div class="form-group">
                                    <label for="taqwa-verses-informative">آيات الإخبار</label>
                                    <div class="textarea-container">
                                        <textarea id="taqwa-verses-informative" name="taqwa_verses_informative" rows="3" placeholder="آيات قرآنية تتحدث عن التقوى بصيغة الإخبار..."></textarea>
                                        <div class="textarea-actions">
                                            <button type="button" class="suggest-btn" data-target="taqwa-verses-informative" data-type="verses" data-context="إخبار">
                                                <i class="fas fa-lightbulb"></i> اقتراحات
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="taqwa-verses-command">آيات الأمر</label>
                                    <div class="textarea-container">
                                        <textarea id="taqwa-verses-command" name="taqwa_verses_command" rows="3" placeholder="آيات قرآنية تأمر بالتقوى..."></textarea>
                                        <div class="textarea-actions">
                                            <button type="button" class="suggest-btn" data-target="taqwa-verses-command" data-type="verses" data-context="أمر">
                                                <i class="fas fa-lightbulb"></i> اقتراحات
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="taqwa-verses-promise">آيات الوعد</label>
                                    <div class="textarea-container">
                                        <textarea id="taqwa-verses-promise" name="taqwa_verses_promise" rows="3" placeholder="آيات قرآنية تتضمن وعد الله للمتقين..."></textarea>
                                        <div class="textarea-actions">
                                            <button type="button" class="suggest-btn" data-target="taqwa-verses-promise" data-type="verses" data-context="وعد">
                                                <i class="fas fa-lightbulb"></i> اقتراحات
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- أحاديث التقوى -->
                            <div class="taqwa-subsection">
                                <h5>أحاديث التقوى</h5>
                                <div class="form-group">
                                    <label for="taqwa-hadith-informative">أحاديث الإخبار</label>
                                    <div class="textarea-container">
                                        <textarea id="taqwa-hadith-informative" name="taqwa_hadith_informative" rows="3" placeholder="أحاديث تتحدث عن التقوى بصيغة الإخبار..."></textarea>
                                        <div class="textarea-actions">
                                            <button type="button" class="suggest-btn" data-target="taqwa-hadith-informative" data-type="hadith" data-context="إخبار">
                                                <i class="fas fa-lightbulb"></i> اقتراحات
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="taqwa-hadith-command">أحاديث الأمر</label>
                                    <div class="textarea-container">
                                        <textarea id="taqwa-hadith-command" name="taqwa_hadith_command" rows="3" placeholder="أحاديث تأمر بالتقوى..."></textarea>
                                        <div class="textarea-actions">
                                            <button type="button" class="suggest-btn" data-target="taqwa-hadith-command" data-type="hadith" data-context="أمر">
                                                <i class="fas fa-lightbulb"></i> اقتراحات
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="taqwa-hadith-promise">أحاديث الوعد</label>
                                    <div class="textarea-container">
                                        <textarea id="taqwa-hadith-promise" name="taqwa_hadith_promise" rows="3" placeholder="أحاديث تتضمن وعد النبي للمتقين..."></textarea>
                                        <div class="textarea-actions">
                                            <button type="button" class="suggest-btn" data-target="taqwa-hadith-promise" data-type="hadith" data-context="وعد">
                                                <i class="fas fa-lightbulb"></i> اقتراحات
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- آثار في التقوى -->
                            <div class="taqwa-subsection">
                                <h5>آثار في التقوى</h5>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="taqwa-athar-speaker">القائل</label>
                                        <input type="text" id="taqwa-athar-speaker" name="taqwa_athar_speaker" placeholder="اسم القائل">
                                    </div>
                                    <div class="form-group">
                                        <label for="taqwa-athar-type">نوع الأثر</label>
                                        <select id="taqwa-athar-type" name="taqwa_athar_type">
                                            <option value="إخبار">إخبار</option>
                                            <option value="قصة">قصة</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="taqwa-athar-text">نص الأثر</label>
                                    <div class="textarea-container">
                                        <textarea id="taqwa-athar-text" name="taqwa_athar_text" rows="3" placeholder="أدخل نص الأثر..."></textarea>
                                        <div class="textarea-actions">
                                            <button type="button" class="suggest-btn" data-target="taqwa-athar-text" data-type="athar" data-context="تقوى">
                                                <i class="fas fa-lightbulb"></i> اقتراحات
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- الموضوع الرئيسي -->
                        <div class="subsection">
                            <h4><i class="fas fa-bullseye"></i> الموضوع الرئيسي</h4>
                            <div class="form-group">
                                <label for="main-topic">عنوان الموضوع</label>
                                <input type="text" id="main-topic" name="main_topic" placeholder="أدخل عنوان الموضوع الرئيسي">
                            </div>
                            <div class="form-group">
                                <label for="topic-content">محتوى الموضوع</label>
                                <div class="textarea-container">
                                    <textarea id="topic-content" name="content" rows="8" placeholder="أدخل محتوى الموضوع الرئيسي..."></textarea>
                                    <div class="textarea-actions">
                                        <button type="button" class="suggest-btn" data-target="topic-content" data-type="content">
                                            <i class="fas fa-lightbulb"></i> اقتراحات
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. الخاتمة -->
                    <div class="sermon-section">
                        <h3><i class="fas fa-hands"></i> الخاتمة</h3>

                        <div class="form-group">
                            <label for="conclusion-text">نص الخاتمة</label>
                            <div class="textarea-container">
                                <textarea id="conclusion-text" name="conclusion" rows="6" placeholder="أدخل نص الخاتمة..."></textarea>
                                <div class="textarea-actions">
                                    <button type="button" class="suggest-btn" data-target="conclusion-text" data-type="conclusion">
                                        <i class="fas fa-lightbulb"></i> اقتراحات
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- الدعاء -->
                        <div class="form-group">
                            <label for="dua-text">الدعاء</label>
                            <div class="textarea-container">
                                <textarea id="dua-text" name="dua_text" rows="4" placeholder="أدخل نص الدعاء..."></textarea>
                                <div class="textarea-actions">
                                    <button type="button" class="suggest-btn" data-target="dua-text" data-type="dua">
                                        <i class="fas fa-lightbulb"></i> اقتراحات
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- معلومات إضافية -->
                    <div class="sermon-section">
                        <h3><i class="fas fa-info-circle"></i> معلومات إضافية</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="sermon-category">التصنيف</label>
                                <select id="sermon-category" name="category">
                                    <option value="">اختر التصنيف</option>
                                    <option value="عقيدة">عقيدة</option>
                                    <option value="فقه">فقه</option>
                                    <option value="أخلاق">أخلاق</option>
                                    <option value="سيرة">سيرة</option>
                                    <option value="تفسير">تفسير</option>
                                    <option value="حديث">حديث</option>
                                    <option value="مناسبات">مناسبات</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="sermon-duration">المدة المتوقعة (بالدقائق)</label>
                                <input type="number" id="sermon-duration" name="duration" placeholder="30" min="5" max="120">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="sermon-tags">الكلمات المفتاحية</label>
                            <input type="text" id="sermon-tags" name="tags" placeholder="أدخل الكلمات المفتاحية مفصولة بفواصل">
                        </div>

                        <div class="form-group">
                            <label for="sermon-references">المراجع</label>
                            <div class="textarea-container">
                                <textarea id="sermon-references" name="references" rows="3" placeholder="أدخل المراجع المستخدمة..."></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="audio-file">ملف صوتي (اختياري)</label>
                            <input type="file" id="audio-file" name="audio_file" accept="audio/*">
                        </div>

                        <div class="form-group">
                            <label for="sermon-status">حالة النشر</label>
                            <select id="sermon-status" name="status">
                                <option value="draft">مسودة</option>
                                <option value="published">منشور</option>
                                <option value="private">خاص</option>
                            </select>
                        </div>
                    </div>

                    <!-- أزرار الحفظ والتصدير -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> حفظ الخطبة
                        </button>
                        <button type="button" class="btn btn-secondary" id="export-word">
                            <i class="fas fa-file-word"></i> تصدير Word
                        </button>
                        <button type="button" class="btn btn-outline" id="save-draft">
                            <i class="fas fa-edit"></i> حفظ كمسودة
                        </button>
                        <button type="button" class="btn btn-outline" id="preview-sermon">
                            <i class="fas fa-eye"></i> معاينة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-about">
                    <h3>عن تمسيك</h3>
                    <p>منصة إسلامية شاملة تهدف إلى مساعدة الخطباء والباحثين وعامة المسلمين في الوصول إلى محتوى إسلامي موثوق.</p>
                </div>
                <div class="footer-links">
                    <h3>روابط سريعة</h3>
                    <ul>
                        <li><a href="{{ url('/') }}">الرئيسية</a></li>
                        <li><a href="{{ route('sermons.index') }}">الخطب</a></li>
                        <li><a href="{{ route('scholars.index') }}">العلماء</a></li>
                        <li><a href="{{ route('lectures.index') }}">المحاضرات</a></li>
                        <li><a href="{{ route('thinkers.index') }}">المفكرون</a></li>
                        <li><a href="{{ route('search.index') }}">البحث</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h3>تواصل معنا</h3>
                    <p><i class="fas fa-envelope"></i> info@tamsik.com</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 تمسيك. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/prepare-sermon.js') }}"></script>
    <script src="{{ asset('js/auth-protection.js') }}"></script>
</body>
</html>
