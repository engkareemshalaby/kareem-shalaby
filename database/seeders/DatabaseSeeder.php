<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Experience;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CategorySeeder::class);

        User::updateOrCreate(['email' => env('ADMIN_EMAIL', 'admin@kareemshalaby.local')], [
            'name' => 'Kareem Shalaby', 'password' => env('ADMIN_PASSWORD', 'ChangeMe123!'), 'is_admin' => true,
        ]);

        $settings = [
            'phone' => '+20 101 925 1504',
            'email' => 'engkareemshalaby@gmail.com',
            'linkedin_url' => 'https://linkedin.com/in/kareem-adel-874317170',
            'location' => 'Faisal, Giza, Egypt',
            'professional_summary' => 'Full Stack Web Developer with 3+ years of experience building and deploying scalable web applications across institutional, enterprise, and startup environments. Strong background in Laravel, PHP, Livewire, Filament, server administration, AWS, and third-party API integrations.',
        ];
        foreach ($settings as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value, 'type' => $key === 'professional_summary' ? 'textarea' : 'text']);
        }

        SocialLink::updateOrCreate(['platform' => 'linkedin'], [
            'label' => 'LinkedIn',
            'url' => $settings['linkedin_url'],
            'is_visible' => true,
            'sort_order' => 1,
        ]);

        $experiences = [
            ['Web Developer', 'MSA University', 'Faisal, Giza', 'March 2026', 'Present', [
                'Own the university official WordPress website, including development, server administration, and backups.',
                'Reduced average page load time from about 20 seconds to under 3 seconds through caching, asset optimization, and image compression.',
                'Manage the production VPS on Hostinger and the mobile application backend infrastructure hosted on AWS.',
                'Built a Staff Directory system using Laravel Filament with a complete CRUD administration panel.',
                'Developing a Laravel university-comparison platform integrating Google Gemini, WhatsApp, and email automation.',
            ]],
            ['Co-Founder & CTO', 'EduTrick', null, 'October 2023', 'Present', [
                'Led a development team of 5 engineers building educational platforms for academies and universities.',
                'Architected and oversaw 50+ educational platforms serving 300K+ users, including 120K+ students and 200+ teachers and organizations.',
                'Owned deployment and server administration across the platform portfolio.',
                'Built EduLens and Elda7ee7a with multi-authentication, quizzes, dashboards, and mobile API integration.',
                'Directed integrations for payments, video conferencing, cloud services, video hosting, and notifications.',
            ]],
            ['Laravel Developer', 'IT-Gates', 'Nasr City', 'October 2022', 'September 2023', [
                'Collaborated on a large CRM system using Laravel and Vue.js.',
                'Implemented scalable backend solutions and applied design patterns in an Agile team.',
            ]],
            ['Freelance Web Developer', null, null, '2017', '2022', [
                'Designed and developed company profile websites, e-commerce stores, and custom platforms.',
                'Specialized in multilingual, SEO-friendly websites with responsive design.',
            ]],
        ];
        foreach ($experiences as $order => [$role, $company, $location, $start, $end, $highlights]) {
            Experience::updateOrCreate(['role' => $role, 'company' => $company], [
                'location' => $location, 'start_date' => $start, 'end_date' => $end,
                'highlights' => $highlights, 'is_visible' => true, 'sort_order' => $order + 1,
            ]);
        }

        $software = Category::where('slug', 'software')->firstOrFail();
        $welcomeArticle = Article::updateOrCreate(['slug' => 'welcome-to-my-digital-garden'], [
            'category_id' => $software->id, 'language' => 'both',
            'title_ar' => 'مرحبًا بك في مساحتي الرقمية', 'title_en' => 'Welcome to my digital garden',
            'excerpt_ar' => 'مساحة أشارك فيها ما أتعلمه في البرمجة والأعمال والحياة.',
            'excerpt_en' => 'A space where I share what I learn about software, business, and life.',
            'body_ar' => "## أهلًا بك\n\nهذا الموقع هو مساحتي لتوثيق الخبرات والدروس العملية. سأكتب هنا بوضوح، مع الاهتمام بالمصادر والسياق.",
            'body_en' => "## Welcome\n\nThis website is where I document practical lessons and experience with clarity, context, and useful sources.",
            'status' => 'published', 'is_featured' => true,
            'seo_title' => 'Kareem Shalaby — Software Engineer, Entrepreneur & Writer',
            'seo_description' => 'مقالات كريم شلبي عن البرمجة والأعمال وما يتعلمه في العلوم والخبرات.',
            'seo_keywords' => ['Kareem Shalaby', 'كريم شلبي', 'Software Engineer'], 'published_at' => now(),
        ]);

        $tags = collect([
            'Laravel' => 'laravel',
            'PHP' => 'php',
            'WordPress' => 'wordpress',
            'Architecture' => 'architecture',
            'Servers' => 'servers',
        ])->map(fn (string $slug, string $name) => Tag::updateOrCreate(['slug' => $slug], ['name' => $name]));

        $welcomeArticle->tags()->sync($tags->whereIn('slug', ['laravel', 'php'])->pluck('id'));
    }
}
