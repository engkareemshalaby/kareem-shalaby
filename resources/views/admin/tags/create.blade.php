<x-layouts.admin title="وسم جديد">
    <h1 class="mb-8 text-3xl font-bold">إضافة وسم</h1>
    <form method="POST" action="{{ route('admin.tags.store') }}">@csrf @include('admin.tags.form')<button class="btn-primary mt-6">حفظ الوسم</button></form>
</x-layouts.admin>
