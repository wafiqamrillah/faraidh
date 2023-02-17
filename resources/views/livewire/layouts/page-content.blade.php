<div x-data="{ load : @entangle('readyToLoad') }"
    wire:init="loadPage()"
    class="relative w-full h-full">
    <div x-show="!load"
        x-transition
        class="absolute inset-0 text-center">
        <i class="fas fa-circle-notch fa-spin"></i>
    </div>
    <div x-show="load"
        x-transition
        class=""
        style="display: none;">
        @if ($readyToLoad)
            {!! base64_decode($content) !!}
        @endif
    </div>
</div>
