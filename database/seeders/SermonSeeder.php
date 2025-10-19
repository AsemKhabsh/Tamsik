<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sermon;
use App\Models\User;

class SermonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $scholars = User::role('scholar')->get();
        $members = User::role('member')->get();
        $authors = $scholars->merge($members);

        $sermons = [
            [
                'title' => 'فضل شهر رمضان المبارك',
                'content' => $this->getRamadanSermonContent(),
                'introduction' => 'الحمد لله رب العالمين، والصلاة والسلام على أشرف المرسلين...',
                'conclusion' => 'نسأل الله أن يبلغنا رمضان وأن يعيننا على صيامه وقيامه...',
                'category' => 'occasions',
                'tags' => ['رمضان', 'صيام', 'قيام', 'عبادة'],
                'is_published' => true,
                'is_featured' => true,
                'views_count' => 1250,
                'downloads_count' => 89,
                'difficulty_level' => 'intermediate',
                'target_audience' => 'عامة المسلمين',
                'published_at' => now()->subDays(15)
            ],
            [
                'title' => 'بر الوالدين في الإسلام',
                'content' => $this->getParentsSermonContent(),
                'introduction' => 'إن الحمد لله نحمده ونستعينه ونستغفره...',
                'conclusion' => 'فاتقوا الله في والديكم وبروهما في حياتهما وبعد مماتهما...',
                'category' => 'akhlaq',
                'tags' => ['بر الوالدين', 'أخلاق', 'أسرة'],
                'is_published' => true,
                'is_featured' => false,
                'views_count' => 980,
                'downloads_count' => 67,
                'difficulty_level' => 'beginner',
                'target_audience' => 'جميع الأعمار',
                'published_at' => now()->subDays(12)
            ],
            [
                'title' => 'أهمية الصلاة في حياة المسلم',
                'content' => $this->getPrayerSermonContent(),
                'introduction' => 'الحمد لله الذي فرض علينا الصلاة وجعلها عماد الدين...',
                'conclusion' => 'فحافظوا على الصلاة واستقيموا عليها تفلحوا في الدنيا والآخرة...',
                'category' => 'fiqh',
                'tags' => ['صلاة', 'عبادة', 'فقه'],
                'is_published' => true,
                'is_featured' => true,
                'views_count' => 1580,
                'downloads_count' => 124,
                'difficulty_level' => 'intermediate',
                'target_audience' => 'عامة المسلمين',
                'published_at' => now()->subDays(8)
            ],
            [
                'title' => 'التوبة النصوح',
                'content' => $this->getRepentanceSermonContent(),
                'introduction' => 'إن الله يحب التوابين ويحب المتطهرين...',
                'conclusion' => 'فبادروا بالتوبة قبل فوات الأوان...',
                'category' => 'aqeedah',
                'tags' => ['توبة', 'استغفار', 'عقيدة'],
                'is_published' => true,
                'is_featured' => false,
                'views_count' => 756,
                'downloads_count' => 45,
                'difficulty_level' => 'intermediate',
                'target_audience' => 'عامة المسلمين',
                'published_at' => now()->subDays(5)
            ],
            [
                'title' => 'آداب المسجد وحرمته',
                'content' => $this->getMosqueEtiquetteSermonContent(),
                'introduction' => 'بيوت الله في الأرض هي المساجد...',
                'conclusion' => 'فاحترموا بيوت الله والتزموا بآدابها...',
                'category' => 'akhlaq',
                'tags' => ['مسجد', 'آداب', 'احترام'],
                'is_published' => true,
                'is_featured' => false,
                'views_count' => 432,
                'downloads_count' => 28,
                'difficulty_level' => 'beginner',
                'target_audience' => 'جميع الأعمار',
                'published_at' => now()->subDays(3)
            ]
        ];

        foreach ($sermons as $sermonData) {
            $author = $authors->random();
            $scholar = $scholars->random();
            
            $sermonData['author_id'] = $author->id;
            $sermonData['scholar_id'] = $scholar->id;
            
            Sermon::create($sermonData);
        }
    }

    private function getRamadanSermonContent()
    {
        return '
        <h2>مقدمة</h2>
        <p>الحمد لله الذي بلغنا شهر رمضان المبارك، شهر الصيام والقيام والقرآن...</p>
        
        <h2>فضائل شهر رمضان</h2>
        <p>شهر رمضان شهر عظيم له فضائل كثيرة منها:</p>
        <ul>
            <li>تفتح فيه أبواب الجنة وتغلق أبواب النار</li>
            <li>تصفد فيه الشياطين</li>
            <li>فيه ليلة القدر التي هي خير من ألف شهر</li>
            <li>الصيام فيه فريضة والقيام سنة مؤكدة</li>
        </ul>
        
        <h2>آداب الصيام</h2>
        <p>للصيام آداب ينبغي للمسلم أن يتحلى بها...</p>
        
        <h2>الخاتمة</h2>
        <p>نسأل الله أن يبلغنا رمضان وأن يعيننا على صيامه وقيامه إيماناً واحتساباً...</p>
        ';
    }

    private function getParentsSermonContent()
    {
        return '
        <h2>مقدمة</h2>
        <p>إن بر الوالدين من أعظم الأعمال وأجلها عند الله تعالى...</p>
        
        <h2>منزلة الوالدين في الإسلام</h2>
        <p>قرن الله تعالى بر الوالدين بعبادته في آيات كثيرة...</p>
        
        <h2>صور بر الوالدين</h2>
        <ul>
            <li>طاعتهما في المعروف</li>
            <li>الإحسان إليهما بالقول والفعل</li>
            <li>الدعاء لهما</li>
            <li>صلة أصدقائهما</li>
        </ul>
        
        <h2>عقوبة عقوق الوالدين</h2>
        <p>العقوق من كبائر الذنوب وله عقوبة عظيمة في الدنيا والآخرة...</p>
        ';
    }

    private function getPrayerSermonContent()
    {
        return '
        <h2>مقدمة</h2>
        <p>الصلاة عماد الدين وأول ما يحاسب عليه العبد يوم القيامة...</p>
        
        <h2>مكانة الصلاة في الإسلام</h2>
        <p>الصلاة هي الركن الثاني من أركان الإسلام...</p>
        
        <h2>فوائد المحافظة على الصلاة</h2>
        <ul>
            <li>تنهى عن الفحشاء والمنكر</li>
            <li>تطهر النفس وتزكيها</li>
            <li>تقرب العبد من ربه</li>
            <li>تجلب البركة والرزق</li>
        </ul>
        ';
    }

    private function getRepentanceSermonContent()
    {
        return '
        <h2>مقدمة</h2>
        <p>التوبة باب عظيم فتحه الله لعباده ليعودوا إليه...</p>
        
        <h2>شروط التوبة النصوح</h2>
        <ul>
            <li>الندم على ما فات</li>
            <li>الإقلاع عن الذنب</li>
            <li>العزم على عدم العودة</li>
            <li>رد المظالم إن كانت</li>
        </ul>
        ';
    }

    private function getMosqueEtiquetteSermonContent()
    {
        return '
        <h2>مقدمة</h2>
        <p>المساجد بيوت الله في الأرض، لها حرمة وآداب...</p>
        
        <h2>آداب دخول المسجد</h2>
        <ul>
            <li>الطهارة</li>
            <li>الدعاء عند الدخول والخروج</li>
            <li>تقديم القدم اليمنى عند الدخول</li>
        </ul>
        ';
    }
}
