@extends('layouts.admin')

@section('title', 'Audit Logs')

@section('content')

<x-page-header title="Audit Logs" subtitle="Complete trail of system actions" />

<form method="GET" class="card p-4 mb-4">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
        <x-search-input name="q" placeholder="Search action..." />

        <x-filter-select
            name="action"
            :options="$actions->mapWithKeys(fn($a) => [$a => $a])->toArray()"
            placeholder="All Actions"
        />

        <input type="date" name="from" value="{{ request('from') }}" class="input" placeholder="From">
        <input type="date" name="to"   value="{{ request('to') }}"   class="input" placeholder="To">

        <div class="flex gap-2">
            <button type="submit" class="btn-primary">Filter</button>
            <a href="{{ route('admin.audit-logs.index') }}" class="btn-secondary">Reset</a>
        </div>
    </div>
</form>

@if($logs->isEmpty())
    <x-empty-state title="No audit logs found" />
@else
    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">When</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">User</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Action</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Model</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @foreach($logs as $log)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">
                            {{ $log->created_at->format('M d, Y g:i A') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-700">
                            {{ $log->user->name ?? 'System' }}
                        </td>
                        <td class="px-4 py-3">
                            <x-badge :color="str_contains($log->action, 'deleted') || str_contains($log->action, 'rejected') ? 'red' : (str_contains($log->action, 'approved') ? 'green' : 'slate')">
                                {{ $log->action }}
                            </x-badge>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500 font-mono">
                            {{ $log->model_type ? class_basename($log->model_type) : '—' }}
                            @if($log->model_id) #{{ $log->model_id }} @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500 font-mono">
                            {{ $log->ip_address ?? '—' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-pagination :paginator="$logs" />
@endif

@endsection