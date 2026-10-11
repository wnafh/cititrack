@props(['action', 'label' => 'Delete', 'confirm' => 'Are you sure? This cannot be undone.'])

<x-confirm-dialog
    :action="$action"
    method="DELETE"
    :buttonLabel="$label"
    :confirmText="$confirm"
    buttonClass="text-red-600 hover:text-red-800 text-sm font-medium"
/>