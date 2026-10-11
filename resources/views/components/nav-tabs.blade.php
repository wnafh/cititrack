@props(['tabs' => [])

<div class="border-b border-slate-200 mb-6">
    <nav class="-mb-px flex gap-6">
        @foreach($tabs as $tab)
            <a href="{{ $tab['url'] }}"
               class="py-3 px-1 border-b-2 text-sm font-medium transition
                      {{ ($tab['active'] ?? false)
                          ? 'border-amber-500 text-amber-600'
                          : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                {{ $tab['label'] }}
            </a>
        @endforeach
    </nav>
</div>