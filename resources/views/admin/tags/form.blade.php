@php($item = $tag ?? null)
<div class="card max-w-2xl">
    <div class="grid gap-5 md:grid-cols-2">
        <div><label class="label">اسم الوسم</label><input class="field" name="name" value="{{ old('name', $item?->name) }}" placeholder="Laravel" required></div>
        <div><label class="label">Slug</label><input class="field" dir="ltr" name="slug" value="{{ old('slug', $item?->slug) }}" placeholder="laravel" required></div>
    </div>
    <p class="mt-4 text-sm text-ink/50">استخدم اسمًا قصيرًا ومحددًا؛ مثل Laravel أو WordPress أو Architecture.</p>
</div>
