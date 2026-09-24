<x-layouts.admin title="تعديل الوسم">
    <h1 class="mb-8 text-3xl font-bold">تعديل #{{ $tag->name }}</h1>
    <form method="POST" action="{{ route('admin.tags.update', $tag) }}">@csrf @method('PUT') @include('admin.tags.form')<button class="btn-primary mt-6">حفظ التعديلات</button></form>
    <form class="mt-3" method="POST" action="{{ route('admin.tags.destroy', $tag) }}" onsubmit="return confirm('حذف الوسم من كل المقالات؟')">@csrf @method('DELETE')<button class="rounded-xl border border-red-200 px-5 py-3 font-bold text-red-700">حذف</button></form>
</x-layouts.admin>
