<div
{{
    $attributes
        ->merge([
            'id' => $getId(),
        ], escape: false)
        ->merge($getExtraAttributes(), escape: false)
        ->class([
            'fi-in-actions fi-tabs-edit-action'
        ])
}}
>
    {{ $getAction($getExtraAttributes()['action'] ?? 'editDetails') }}
</div>
