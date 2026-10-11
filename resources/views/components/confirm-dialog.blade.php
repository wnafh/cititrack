@props(['action', 'method' => 'POST', 'message' => 'Are you sure?', 'buttonLabel' => 'Delete', 'buttonClass' => 'text-red-600 hover:text-red-800', 'confirmText' => 'Are you sure? This cannot be undone.'])

<form method="POST" action="{{ $action }}"
      onsubmit="return confirm(@js($confirmText));"
      class="inline">
    @csrf
    @if(! in_array(strtoupper($method), ['GET', 'POST']))
        @method($method)
    @endif

    <button type="submit" {{ $attributes->merge(['class' => $buttonClass]) }}>
        {{ $buttonLabel }}
    </button>
</form>