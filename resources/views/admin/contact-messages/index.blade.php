<x-layouts.admin title="رسائل التواصل">
    <div><p class="font-bold text-brand">Inbox</p><h1 class="mt-2 text-3xl font-bold">رسائل التواصل</h1><p class="mt-2 text-muted">كل الرسائل الواردة من نموذج التواصل بالموقع.</p></div>
    <div class="card mt-8 overflow-hidden !p-0">
        <div class="divide-y divide-line">
            @forelse($messages as $message)
                <a class="flex flex-col gap-3 p-5 transition hover:bg-brand/[.03] sm:flex-row sm:items-center" href="{{ route('admin.contact-messages.show', $message) }}">
                    <span class="size-2 shrink-0 rounded-full {{ $message->read_at ? 'bg-line' : 'bg-accent shadow-[0_0_0_5px_rgba(182,138,69,.12)]' }}"></span>
                    <div class="min-w-0 grow"><div class="flex flex-wrap items-center gap-2"><strong>{{ $message->name ?: 'زائر بدون اسم' }}</strong>@if($message->subject)<span class="text-sm text-muted">— {{ $message->subject }}</span>@endif</div><p class="mt-1 truncate text-sm text-muted">{{ $message->message }}</p></div>
                    <div class="shrink-0 text-xs text-muted"><span dir="ltr">{{ $message->ip_address ?: '—' }}</span><span class="mt-1 block">{{ $message->created_at->translatedFormat('j F Y، g:i A') }}</span></div>
                </a>
            @empty
                <div class="p-12 text-center text-muted">لا توجد رسائل حتى الآن.</div>
            @endforelse
        </div>
    </div>
    <div class="mt-6">{{ $messages->links() }}</div>
</x-layouts.admin>
