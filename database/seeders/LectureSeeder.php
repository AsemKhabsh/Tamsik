<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lecture;
use App\Models\User;
use Carbon\Carbon;

class LectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على المستخدمين (العلماء والخطباء)
        $scholars = User::whereIn('user_type', ['scholar', 'preacher'])->get();
        
        if ($scholars->isEmpty()) {
            echo "لا يوجد علماء أو خطباء في قاعدة البيانات. يرجى تشغيل AdminSeeder أولاً.\n";
            return;
        }

        $lectures = [
            [
                'title' => 'أحكام الصيام في الإسلام',
                'description' => 'محاضرة شاملة عن أحكام الصيام وآدابه وفوائده الروحية والصحية',
                'topic' => 'الصيام ركن من أركان الإسلام الخمسة، وهو عبادة عظيمة لها أحكام وآداب يجب على المسلم معرفتها...',
                'category' => 'فقه العبادات',
                'status' => 'scheduled',
                'is_published' => true,
                'scheduled_at' => Carbon::now()->addDays(7),
                'duration' => 90,
                'location' => 'مسجد النور الكبير',
                'city' => 'صنعاء',
                'venue' => 'القاعة الكبرى',
                'max_attendees' => 200,
                'contact_phone' => '+967-1-234567',
                'contact_email' => 'info@mosque.com',
                'target_audience' => 'general',
                'requirements' => 'لا توجد متطلبات خاصة',
                'tags' => json_encode(['صيام', 'فقه', 'عبادات', 'رمضان'])
            ],
            [
                'title' => 'السيرة النبوية: دروس وعبر',
                'description' => 'سلسلة محاضرات عن السيرة النبوية الشريفة والدروس المستفادة منها',
                'topic' => 'السيرة النبوية مدرسة عظيمة للتربية والأخلاق، نتعلم منها كيف نعيش حياتنا وفق منهج الإسلام...',
                'category' => 'السيرة النبوية',
                'status' => 'scheduled',
                'is_published' => true,
                'scheduled_at' => Carbon::now()->addDays(14),
                'duration' => 120,
                'location' => 'محاضرة افتراضية',
                'city' => 'عبر الإنترنت',
                'venue' => 'Zoom Meeting',
                'max_attendees' => 500,
                'contact_phone' => '+967-1-234568',
                'contact_email' => 'seerah@tamsik.com',
                'target_audience' => 'general',
                'requirements' => 'اتصال بالإنترنت',
                'tags' => json_encode(['سيرة', 'نبوية', 'تربية', 'أخلاق'])
            ],
            [
                'title' => 'التربية الإسلامية للأطفال',
                'description' => 'كيفية تربية الأطفال على القيم الإسلامية والأخلاق الحميدة',
                'topic' => 'تربية الأطفال مسؤولية عظيمة، والإسلام وضع منهجاً شاملاً لتربية الأجيال...',
                'category' => 'التربية والأسرة',
                'status' => 'scheduled',
                'is_published' => true,
                'scheduled_at' => Carbon::now()->addDays(21),
                'duration' => 180,
                'location' => 'مركز التربية الإسلامية',
                'city' => 'تعز',
                'venue' => 'قاعة المؤتمرات',
                'max_attendees' => 100,
                'contact_phone' => '+967-4-234567',
                'contact_email' => 'education@center.com',
                'target_audience' => 'general',
                'requirements' => 'خبرة في التعامل مع الأطفال مفضلة',
                'tags' => json_encode(['تربية', 'أطفال', 'أسرة', 'قيم'])
            ],
            [
                'title' => 'الاقتصاد الإسلامي: المبادئ والتطبيق',
                'description' => 'محاضرة عن مبادئ الاقتصاد الإسلامي وتطبيقاته في العصر الحديث',
                'topic' => 'الاقتصاد الإسلامي نظام متكامل يحقق العدالة الاجتماعية والتنمية المستدامة...',
                'category' => 'الاقتصاد الإسلامي',
                'status' => 'scheduled',
                'is_published' => true,
                'scheduled_at' => Carbon::now()->addDays(28),
                'duration' => 150,
                'location' => 'محاضرة افتراضية',
                'city' => 'عبر الإنترنت',
                'venue' => 'Microsoft Teams',
                'max_attendees' => 300,
                'contact_phone' => '+967-1-234569',
                'contact_email' => 'economy@tamsik.com',
                'target_audience' => 'general',
                'requirements' => 'معرفة أساسية بالاقتصاد',
                'tags' => json_encode(['اقتصاد', 'إسلامي', 'تنمية', 'عدالة'])
            ],
            [
                'title' => 'الإعجاز العلمي في القرآن الكريم',
                'description' => 'استكشاف الإعجاز العلمي في القرآن الكريم والاكتشافات الحديثة',
                'topic' => 'القرآن الكريم معجزة خالدة، وقد كشف العلم الحديث عن إعجازات علمية مذهلة...',
                'category' => 'الإعجاز العلمي',
                'status' => 'scheduled',
                'is_published' => true,
                'scheduled_at' => Carbon::now()->addDays(35),
                'duration' => 120,
                'location' => 'قاعة المؤتمرات الكبرى',
                'city' => 'عدن',
                'venue' => 'مركز المؤتمرات',
                'max_attendees' => 400,
                'contact_phone' => '+967-2-234567',
                'contact_email' => 'science@tamsik.com',
                'target_audience' => 'youth',
                'requirements' => 'خلفية علمية أساسية',
                'tags' => json_encode(['إعجاز', 'علمي', 'قرآن', 'اكتشافات'])
            ]
        ];

        foreach ($lectures as $lectureData) {
            // اختيار متحدث عشوائي من العلماء/الخطباء
            $speaker = $scholars->random();
            
            Lecture::create(array_merge($lectureData, [
                'speaker_id' => $speaker->id,
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
                'updated_at' => Carbon::now()->subDays(rand(0, 5))
            ]));
        }

        echo "تم إنشاء " . count($lectures) . " محاضرة تجريبية بنجاح!\n";
        echo "المحاضرات تشمل مواضيع متنوعة: فقه، سيرة، تربية، اقتصاد، إعجاز علمي\n";
        echo "أنواع المحاضرات: حضورية وافتراضية\n";
        echo "منها مجانية ومدفوعة\n";
    }
}
