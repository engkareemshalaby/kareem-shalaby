<x-layouts.admin title="الوسوم">
    <div class="flex items-center justify-between">
        <div><p class="font-bold text-brand">التنظيم</p><h1 class="mt-2 text-3xl font-bold">الوسوم</h1></div>
        <a class="btn-primary" href="{{ route('admin.tags.create') }}">+ وسم جديد</a>
    </div>
    <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse($tags as $tag)
            <div class="card flex items-center justify-between">
                <div><h2 class="text-lg font-bold">#{{ $tag->name }}</h2><p class="mt-1 text-sm text-ink/45">{{ $tag->slug }} · {{ $tag->articles_count }} مقال</p></div>
                <a class="font-bold text-brand" href="{{ route('admin.tags.edit', $tag) }}">تعديل</a>
            </div>
        @empty
            <div class="card col-span-full text-center">لم تُضف وسوم بعد.</div>
        @endforelse
    </div>
</x-layouts.admin>
