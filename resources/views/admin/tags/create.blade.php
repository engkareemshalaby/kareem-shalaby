@php($tagRows = old('tags', [['name' => '', 'slug' => '']]))

<x-layouts.admin title="وسوم جديدة">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div><p class="font-bold text-accent">تنظيم المحتوى</p><h2 class="mt-2 text-3xl font-bold">إضافة عدة وسوم</h2><p class="mt-3 text-muted">أضف حتى 20 وسمًا واحفظها كلها في خطوة واحدة.</p></div>
        <a class="text-sm font-bold text-accent" href="{{ route('admin.tags.index') }}">الوسوم الحالية ←</a>
    </div>

    <form class="mt-8" method="POST" action="{{ route('admin.tags.store') }}">
        @csrf
        <section class="card max-w-4xl !rounded-2xl">
            <div class="hidden grid-cols-[1fr_1fr_44px] gap-4 px-1 pb-3 text-sm font-bold text-muted md:grid"><span>اسم الوسم</span><span>Slug بالإنجليزية</span><span></span></div>
            <div data-tag-rows class="grid gap-3">
                @foreach($tagRows as $index => $tagRow)
                    <div data-tag-row class="grid gap-3 rounded-2xl border border-line bg-ink/[.015] p-3 md:grid-cols-[1fr_1fr_44px] md:items-start">
                        <div><label class="label md:hidden">اسم الوسم</label><input data-tag-name class="field" name="tags[{{ $index }}][name]" value="{{ $tagRow['name'] ?? '' }}" placeholder="Laravel" required>@error("tags.$index.name")<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="label md:hidden">Slug بالإنجليزية</label><input data-tag-slug class="field" dir="ltr" name="tags[{{ $index }}][slug]" value="{{ $tagRow['slug'] ?? '' }}" placeholder="laravel" required>@error("tags.$index.slug")<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <button data-remove-tag type="button" class="grid size-11 place-items-center rounded-xl border border-red-200 text-xl text-red-600 transition hover:bg-red-50" aria-label="حذف الوسم">×</button>
                    </div>
                @endforeach
            </div>

            <div class="mt-5 flex flex-col justify-between gap-4 border-t border-line pt-5 sm:flex-row sm:items-center">
                <button data-add-tag type="button" class="inline-flex items-center gap-2 self-start rounded-full border border-accent/30 bg-accent/5 px-5 py-2.5 text-sm font-bold text-accent transition hover:bg-accent/10"><span class="text-lg">+</span> إضافة وسم آخر</button>
                <p class="text-xs leading-6 text-muted">استخدم اسمًا مختصرًا وSlug فريدًا؛ مثل <span dir="ltr">Architecture / architecture</span>.</p>
            </div>
        </section>

        <div class="mt-6 flex flex-wrap gap-3"><button class="btn-primary" type="submit">حفظ كل الوسوم</button><a class="btn-secondary" href="{{ route('admin.tags.index') }}">إلغاء</a></div>
    </form>

    <template data-tag-template>
        <div data-tag-row class="grid gap-3 rounded-2xl border border-line bg-ink/[.015] p-3 md:grid-cols-[1fr_1fr_44px] md:items-start">
            <div><label class="label md:hidden">اسم الوسم</label><input data-tag-name class="field" name="tags[__INDEX__][name]" placeholder="Laravel" required></div>
            <div><label class="label md:hidden">Slug بالإنجليزية</label><input data-tag-slug class="field" dir="ltr" name="tags[__INDEX__][slug]" placeholder="laravel" required></div>
            <button data-remove-tag type="button" class="grid size-11 place-items-center rounded-xl border border-red-200 text-xl text-red-600 transition hover:bg-red-50" aria-label="حذف الوسم">×</button>
        </div>
    </template>

    @push('head')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const rows = document.querySelector('[data-tag-rows]');
                const template = document.querySelector('[data-tag-template]');
                const addButton = document.querySelector('[data-add-tag]');
                let nextIndex = rows.querySelectorAll('[data-tag-row]').length;

                const updateRemoveButtons = () => {
                    const buttons = rows.querySelectorAll('[data-remove-tag]');
                    buttons.forEach((button) => button.classList.toggle('invisible', buttons.length === 1));
                };

                addButton.addEventListener('click', () => {
                    if (rows.querySelectorAll('[data-tag-row]').length >= 20) {
                        return;
                    }

                    rows.insertAdjacentHTML('beforeend', template.innerHTML.replaceAll('__INDEX__', nextIndex++));
                    rows.lastElementChild.querySelector('[data-tag-name]').focus();
                    updateRemoveButtons();
                });

                rows.addEventListener('click', (event) => {
                    const removeButton = event.target.closest('[data-remove-tag]');

                    if (removeButton && rows.querySelectorAll('[data-tag-row]').length > 1) {
                        removeButton.closest('[data-tag-row]').remove();
                        updateRemoveButtons();
                    }
                });

                updateRemoveButtons();
            });
        </script>
    @endpush
</x-layouts.admin>
