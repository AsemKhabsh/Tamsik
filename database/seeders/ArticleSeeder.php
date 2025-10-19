<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;
use Carbon\Carbon;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // الحصول على معرفات المؤلفين (العلماء والخطباء)
        $authorIds = User::whereIn('user_type', ['scholar', 'preacher'])
                        ->pluck('id')
                        ->toArray();

        if (empty($authorIds)) {
            $this->command->warn('لا توجد حسابات علماء أو خطباء. سيتم استخدام المدير كمؤلف.');
            $authorIds = User::where('user_type', 'admin')->pluck('id')->toArray();
        }

        $articles = [
            [
                'title' => 'دور الشباب في بناء المجتمع الإسلامي',
                'excerpt' => 'الشباب هم عماد الأمة وأساس نهضتها، وللشباب المسلم دور مهم في بناء مجتمع إسلامي قوي ومتماسك.',
                'content' => 'إن الشباب في أي مجتمع هم القوة الحقيقية التي تدفع عجلة التقدم والتطور، وفي المجتمع الإسلامي يحمل الشباب مسؤولية مضاعفة، فهم لا يبنون مجتمعاً عادياً، بل يبنون مجتمعاً إسلامياً يقوم على القيم والمبادئ الإسلامية السامية. ويتطلب هذا البناء من الشباب المسلم أن يكون على وعي كامل بدوره ومسؤوليته، وأن يتسلح بالعلم والمعرفة والأخلاق الحميدة.',
                'category' => 'الشباب والمستقبل',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(5),
                'is_featured' => true,
                'views_count' => rand(100, 500),
                'reading_time' => 8,
                'tags' => json_encode(['شباب', 'مجتمع', 'بناء', 'إسلام'])
            ],
            [
                'title' => 'التربية الإيمانية في عصر التكنولوجيا',
                'excerpt' => 'كيف نربي أطفالنا على الإيمان والقيم الإسلامية في عصر التكنولوجيا والانفتاح الرقمي؟',
                'content' => 'يواجه الآباء والمربون اليوم تحدياً كبيراً في تربية الأجيال الجديدة على القيم الإسلامية، خاصة مع انتشار التكنولوجيا ووسائل التواصل الاجتماعي. فالأطفال اليوم يتعرضون لمؤثرات متنوعة من خلال الإنترنت والألعاب والبرامج، مما يتطلب من المربين وضع استراتيجيات حديثة للتربية الإيمانية.',
                'category' => 'التربية والتعليم',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(10),
                'is_featured' => false,
                'views_count' => rand(80, 300),
                'reading_time' => 6,
                'tags' => json_encode(['تربية', 'إيمان', 'تكنولوجيا', 'أطفال'])
            ],
            [
                'title' => 'الاقتصاد الإسلامي: حلول للأزمات المعاصرة',
                'excerpt' => 'نظرة على مبادئ الاقتصاد الإسلامي وكيف يمكن أن تساهم في حل الأزمات الاقتصادية المعاصرة.',
                'content' => 'يقدم الاقتصاد الإسلامي نموذجاً متكاملاً للتعامل مع المسائل المالية والاقتصادية، ويتميز بمبادئه العادلة التي تحقق التوازن بين مصالح الفرد والمجتمع. ومن أهم هذه المبادئ تحريم الربا، وتشجيع التجارة الحلال، والزكاة كنظام للتكافل الاجتماعي.',
                'category' => 'الاقتصاد الإسلامي',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(15),
                'is_featured' => true,
                'views_count' => rand(200, 600),
                'reading_time' => 10,
                'tags' => json_encode(['اقتصاد', 'إسلامي', 'حلول', 'أزمات'])
            ],
            [
                'title' => 'الحوار الحضاري بين الأديان',
                'excerpt' => 'أهمية الحوار البناء بين أتباع الأديان المختلفة لتحقيق السلام والتفاهم المتبادل.',
                'content' => 'يدعو الإسلام إلى الحوار الحضاري مع أتباع الأديان الأخرى على أساس من الاحترام المتبادل والبحث عن القواسم المشتركة. وهذا الحوار ضروري في عالمنا المعاصر لمواجهة التحديات المشتركة وبناء جسور التفاهم والتعاون.',
                'category' => 'الحوار الحضاري',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(20),
                'is_featured' => false,
                'views_count' => rand(150, 400),
                'reading_time' => 7,
                'tags' => json_encode(['حوار', 'حضاري', 'أديان', 'سلام'])
            ]
        ];

        // إنشاء المقالات
        foreach ($articles as $articleData) {
            Article::create(array_merge($articleData, [
                'author_id' => $authorIds[array_rand($authorIds)]
            ]));
        }

        $this->command->info('تم إنشاء ' . count($articles) . ' مقالة تجريبية بنجاح!');
        $this->command->info('المقالات تشمل مواضيع متنوعة: شباب، تربية، اقتصاد، حوار حضاري');
        $this->command->info('منها مقالات مميزة وأخرى عادية');
    }
}
