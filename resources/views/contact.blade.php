@php($isEnglish = app()->isLocale('en'))
<x-layouts.app :title="$isEnglish ? 'Contact Kareem Shalaby' : 'تواصل مع كريم شلبي'" :description="$isEnglish ? 'Contact Kareem Shalaby for projects, consulting, and professional conversations.' : 'تواصل مع كريم شلبي للمشاريع والاستشارات والنقاشات المهنية.'">
<section class="container-site py-16 sm:py-24">
    <div class="mx-auto max-w-3xl text-center">
        <span class="eyebrow">{{ $isEnglish ? 'Let’s talk' : 'لنتحدث' }}</span>
        <h1 class="mt-6 text-4xl leading-tight font-bold sm:text-6xl">{{ $isEnglish ? 'A good conversation can start something useful.' : 'محادثة جيدة قد تكون بداية شيء مفيد.' }}</h1>
        <p class="mx-auto mt-6 max-w-2xl text-lg leading-8 text-muted">{{ $isEnglish ? 'For projects, consulting, collaborations, or a simple hello. Choose the easiest channel or send a message below.' : 'للمشاريع والاستشارات والتعاون، أو حتى لإلقاء التحية. اختر الوسيلة الأنسب أو أرسل رسالة مباشرة.' }}</p>
    </div>

    <div class="mt-14 grid gap-6 lg:grid-cols-[.8fr_1.2fr]">
        <aside class="space-y-5">
            <div class="rounded-[2rem] bg-[#0b1424] p-7 text-white shadow-xl">
                <img class="h-24 w-auto" src="{{ asset('ks.png') }}" alt="Kareem Shalaby">
                <div class="mt-8 grid gap-3">
                    @if($settings['phone'] ?? null)<a class="contact-link" dir="ltr" href="tel:{{ preg_replace('/\s+/', '', $settings['phone']) }}"><span>☎</span><span>{{ $settings['phone'] }}</span></a>@endif
                    @if($settings['phone_secondary'] ?? null)<a class="contact-link" dir="ltr" href="tel:{{ preg_replace('/\s+/', '', $settings['phone_secondary']) }}"><span>☎</span><span>{{ $settings['phone_secondary'] }}</span></a>@endif
                    @if($settings['email'] ?? null)<a class="contact-link" href="mailto:{{ $settings['email'] }}"><span>✉</span><span class="break-all">{{ $settings['email'] }}</span></a>@endif
                    @if($settings['location'] ?? null)<div class="contact-link"><span>⌖</span><span>{{ $settings['location'] }}</span></div>@endif
                </div>
            </div>
            @if($socialLinks->isNotEmpty())
                <div class="card">
                    <h2 class="text-lg font-bold">{{ $isEnglish ? 'Find me online' : 'حساباتي على الإنترنت' }}</h2>
                    <div class="mt-5 grid gap-2">
                        @foreach($socialLinks as $link)<a class="flex items-center justify-between rounded-2xl border border-line px-4 py-3 font-bold transition hover:border-accent hover:bg-accent/5 hover:text-accent" href="{{ $link->url }}" target="_blank" rel="noopener"><span>{{ $link->label }}</span><span>↗</span></a>@endforeach
                    </div>
                </div>
            @endif
        </aside>

        <div class="card !p-7 sm:!p-9">
            <div><p class="font-bold text-accent">{{ $isEnglish ? 'Direct message' : 'رسالة مباشرة' }}</p><h2 class="mt-2 text-3xl font-bold">{{ $isEnglish ? 'What would you like to discuss?' : 'ما الذي ترغب في مناقشته؟' }}</h2><p class="mt-3 text-sm text-muted">{{ $isEnglish ? 'Only the message is required. Add any contact details you prefer.' : 'نص الرسالة فقط هو المطلوب، وباقي البيانات اختيارية بالكامل.' }}</p></div>
            @if(session('contact_success'))<div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 font-bold text-emerald-800">✓ {{ session('contact_success') }}</div>@endif
            <form class="mt-7 grid gap-5" method="POST" action="{{ route('contact.store') }}">
                @csrf
                <div class="grid gap-5 sm:grid-cols-2"><div><label class="label" for="name">{{ $isEnglish ? 'Name (optional)' : 'الاسم (اختياري)' }}</label><input class="field" id="name" name="name" value="{{ old('name') }}" autocomplete="name"></div><div><label class="label" for="email">{{ $isEnglish ? 'Email (optional)' : 'البريد الإلكتروني (اختياري)' }}</label><input class="field" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" dir="ltr">@error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</div></div>
                <div class="grid gap-5 sm:grid-cols-2"><div><label class="label" for="phone">{{ $isEnglish ? 'Phone (optional)' : 'الهاتف (اختياري)' }}</label><input class="field" id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel" dir="ltr"></div><div><label class="label" for="subject">{{ $isEnglish ? 'Subject (optional)' : 'الموضوع (اختياري)' }}</label><input class="field" id="subject" name="subject" value="{{ old('subject') }}"></div></div>
                <div><label class="label" for="message">{{ $isEnglish ? 'Message' : 'الرسالة' }} <span class="text-red-600">*</span></label><textarea class="field min-h-44 resize-y" id="message" name="message" required maxlength="5000">{{ old('message') }}</textarea>@error('message')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"><p class="text-xs leading-6 text-muted">{{ $isEnglish ? 'Technical request data such as IP and browser information is recorded for security.' : 'تُسجّل بيانات الطلب التقنية مثل IP والمتصفح لأغراض الأمان.' }}</p><button class="btn-primary shrink-0" type="submit">{{ $isEnglish ? 'Send message →' : 'إرسال الرسالة ←' }}</button></div>
            </form>
        </div>
    </div>
</section>
</x-layouts.app>
