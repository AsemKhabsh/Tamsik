const { pool } = require('../config/database-adapter');

async function addSampleSermons() {
    try {
        console.log('🔄 إضافة خطب تجريبية...');

        // التحقق من وجود خطب مسبقاً
        const [existingSermons] = await pool.execute(`
            SELECT COUNT(*) as count FROM sermons
        `);

        if (existingSermons[0] && existingSermons[0].count > 0) {
            console.log('✅ توجد خطب مسبقاً في قاعدة البيانات');
            return;
        }

        // إضافة خطب تجريبية
        const sampleSermons = [
            {
                title: 'التوكل على الله',
                content: JSON.stringify({
                    main_title: 'التوكل على الله',
                    introduction: {
                        athar: 'قال الله تعالى: "ومن يتوكل على الله فهو حسبه"',
                        saja: {
                            topic: 'التوكل',
                            rhyme: 'التوكل على الله سبيل النجاح',
                            attribution: '',
                            reference: ''
                        }
                    },
                    amma_baad: {
                        taqwa_advice: {
                            content: 'أوصيكم ونفسي بتقوى الله',
                            verse: 'يا أيها الذين آمنوا اتقوا الله حق تقاته'
                        }
                    },
                    main_content: {
                        points: [
                            {
                                title: 'معنى التوكل',
                                content: 'التوكل هو الاعتماد على الله مع الأخذ بالأسباب',
                                verses: ['ومن يتوكل على الله فهو حسبه'],
                                hadiths: []
                            }
                        ]
                    },
                    conclusion: {
                        summary: 'التوكل على الله من أعظم العبادات',
                        dua: 'اللهم أعنا على ذكرك وشكرك وحسن عبادتك'
                    }
                }),
                excerpt: 'خطبة عن التوكل على الله وأهميته في حياة المسلم',
                author: 'مؤلف تجريبي',
                status: 'published',
                is_featured: 1,
                category_id: 1,
                user_id: 1
            },
            {
                title: 'أهمية الصلاة',
                content: JSON.stringify({
                    main_title: 'أهمية الصلاة في الإسلام',
                    introduction: {
                        athar: 'قال رسول الله صلى الله عليه وسلم: "الصلاة عماد الدين"',
                        saja: {
                            topic: 'الصلاة',
                            rhyme: 'الصلاة نور وهداية',
                            attribution: '',
                            reference: ''
                        }
                    },
                    amma_baad: {
                        taqwa_advice: {
                            content: 'أوصيكم ونفسي بتقوى الله والمحافظة على الصلاة',
                            verse: 'حافظوا على الصلوات والصلاة الوسطى'
                        }
                    },
                    main_content: {
                        points: [
                            {
                                title: 'مكانة الصلاة في الإسلام',
                                content: 'الصلاة هي الركن الثاني من أركان الإسلام',
                                verses: ['وأقيموا الصلاة وآتوا الزكاة'],
                                hadiths: ['الصلاة عماد الدين']
                            }
                        ]
                    },
                    conclusion: {
                        summary: 'المحافظة على الصلاة من أعظم الواجبات',
                        dua: 'اللهم أعنا على إقامة الصلاة'
                    }
                }),
                excerpt: 'خطبة عن أهمية الصلاة ومكانتها في الإسلام',
                author: 'مؤلف تجريبي',
                status: 'published',
                is_featured: 0,
                category_id: 2,
                user_id: 1
            },
            {
                title: 'بر الوالدين',
                content: JSON.stringify({
                    main_title: 'بر الوالدين',
                    introduction: {
                        athar: 'قال الله تعالى: "وقضى ربك ألا تعبدوا إلا إياه وبالوالدين إحساناً"',
                        saja: {
                            topic: 'بر الوالدين',
                            rhyme: 'بر الوالدين طريق الجنان',
                            attribution: '',
                            reference: ''
                        }
                    },
                    amma_baad: {
                        taqwa_advice: {
                            content: 'أوصيكم ونفسي ببر الوالدين',
                            verse: 'وبالوالدين إحساناً'
                        }
                    },
                    main_content: {
                        points: [
                            {
                                title: 'أهمية بر الوالدين',
                                content: 'بر الوالدين من أعظم القربات إلى الله',
                                verses: ['وقضى ربك ألا تعبدوا إلا إياه وبالوالدين إحساناً'],
                                hadiths: ['رضا الرب في رضا الوالد']
                            }
                        ]
                    },
                    conclusion: {
                        summary: 'بر الوالدين سبب لدخول الجنة',
                        dua: 'رب اغفر لي ولوالدي'
                    }
                }),
                excerpt: 'خطبة عن بر الوالدين وأهميته في الإسلام',
                author: 'مؤلف تجريبي',
                status: 'published',
                is_featured: 1,
                category_id: 3,
                user_id: 1
            }
        ];

        for (const sermon of sampleSermons) {
            await pool.execute(`
                INSERT INTO sermons (
                    title, content, excerpt, author, status, is_featured,
                    category_id, user_id, views_count,
                    created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, datetime('now'), datetime('now'))
            `, [
                sermon.title,
                sermon.content,
                sermon.excerpt,
                sermon.author,
                sermon.status,
                sermon.is_featured,
                sermon.category_id,
                sermon.user_id
            ]);
        }

        console.log('✅ تم إضافة الخطب التجريبية بنجاح');

        // عرض الخطب المضافة
        const [addedSermons] = await pool.execute(`
            SELECT id, title, author, status FROM sermons
        `);

        console.log('📋 الخطب المضافة:');
        addedSermons.forEach(sermon => {
            console.log(`  - ${sermon.id}: ${sermon.title} (${sermon.author})`);
        });

    } catch (error) {
        console.error('❌ خطأ في إضافة الخطب التجريبية:', error);
    }
}

// تشغيل السكريبت
if (require.main === module) {
    addSampleSermons().then(() => {
        console.log('🏁 انتهى السكريبت');
        process.exit(0);
    }).catch(error => {
        console.error('❌ خطأ في تشغيل السكريبت:', error);
        process.exit(1);
    });
}

module.exports = { addSampleSermons };
