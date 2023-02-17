<div {{ $attributes->merge(['class' => 'relative bg-white border border-gray-400 overflow-hidden overflow-y-auto rounded-md scrollbar-thin scrollbar-thumb-rounded scrollbar-thumb-gray-600 hover:scrollbar-track-gray-300']) }}>
    <table class="table border-seperate w-full text-sm" style="border-spacing: 0">
    @isset($header)
        <thead>
            {{ $header }}
        </thead>
    @endisset

    @isset($body)
        <tbody>
    @endisset
            {{ $body ?? $slot }}
    @isset($body)
        </tbody>
    @endisset

    @isset($footer)
        <tfoot>
            {{ $footer }}
        </tfoot>
    @endisset
    </table>
</div>
