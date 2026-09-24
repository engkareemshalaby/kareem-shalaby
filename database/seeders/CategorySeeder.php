<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['software', 'البرمجيات', 'Software', 'البرمجة، Laravel، هندسة البرمجيات، الخوادم وتجارب الإنتاج.', 'Programming, Laravel, architecture, servers, and production lessons.', '#0F766E', true],
            ['business', 'الأعمال', 'Business', 'الإدارة، تأسيس الشركات، التجارة والتسويق وإدارة الفرق.', 'Management, entrepreneurship, commerce, marketing, and teams.', '#C2410C', true],
            ['islam', 'إسلاميات', 'Islam', 'دروس وملخصات شرعية مع العناية بالمصادر.', 'Islamic lessons and reflections with careful sourcing.', '#047857', false],
            ['arabic', 'اللغة العربية', 'Arabic', 'النحو والبلاغة وتعليم العربية لغير الناطقين بها.', 'Arabic grammar, rhetoric, and teaching Arabic.', '#7C3AED', false],
            ['family', 'الأسرة', 'Family', 'ما أتعلمه وأطبقه عن التربية والأسرة.', 'What I learn and apply about parenting and family.', '#BE123C', false],
            ['thoughts', 'أفكار وقراءات', 'Thoughts', 'كتب وتجارب وتطوير ذات وأفكار عامة.', 'Books, experiences, personal growth, and ideas.', '#1D4ED8', false],
        ];

        foreach ($categories as $index => [$slug, $nameAr, $nameEn, $descriptionAr, $descriptionEn, $color, $professional]) {
            Category::updateOrCreate(['slug' => $slug], [
                'name_ar' => $nameAr, 'name_en' => $nameEn, 'description_ar' => $descriptionAr,
                'description_en' => $descriptionEn, 'color' => $color, 'is_professional' => $professional,
                'is_visible' => true, 'sort_order' => $index + 1,
            ]);
        }
    }
}
