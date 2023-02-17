<div
    x-data="{
            show : false,
            load : @entangle('readyToLoad').defer,
            modalSize : @entangle('modalSize').defer,
            focusables() {
                // All focusable element types...
                let selector = 'a, button, input, textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'

                return [...$el.querySelectorAll(selector)]
                    // All non-disabled elements...
                    .filter(el => ! el.hasAttribute('disabled'))
            },
            closeModal() {
                this.show = false;
            },
            firstFocusable() { return this.focusables()[0] },
            lastFocusable() { return this.focusables().slice(-1)[0] },
            nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
            prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
            nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
            prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 },
        }"
    x-init="
        $watch('show', value => {
            if(!value){
                setTimeout(() => {
                    $wire.set('readyToLoad', false);
                    $wire.clear();
                    document.body.classList.remove('overflow-y-hidden');
                }, 500)
            } else {
                document.body.classList.add('overflow-y-hidden');
                setTimeout(() => {
                    $wire.set('readyToLoad', true);
                }, 1000);
            }
        });"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
    x-on:keydown.shift.tab.prevent="prevFocusable().focus()"
    x-on:show-modal.window="show = true"
    x-on:close-modal.window="show = false"
    x-on:switch-modal.window="$wire.clear(); setTimeout(() => { $wire.switch($event.detail); }, 500);"
    x-show="show"
    style="display: none;"
    class="fixed inset-0 overflow-y-auto flex items-center z-50"
>
    <div x-show="show"
        x-on:click="show = false"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 transform transition-all">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <div x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="bg-white border border-blue-600 shadow-md overflow-hidden transform transition-all w-full sm:mx-auto"
        x-bind:class="modalSize">
        <x-modal.header>
            <div class="flex flex-row justify-between">
                <div class="w-full flex flex-row justify-between">
                    <div>{{ $title }}</div>
                    <div>
                        <div x-on:click="closeModal()"
                            class="cursor-pointer text-gray-700 hover:text-gray-500">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>
                </div>
            </div>
        </x-modal.header>

        <div class="relative" style="min-height: 2rem;">
            <div x-show="!load"
                x-transition.duration.500ms
                class="absolute w-full h-full flex items-center justify-center">
                <div class="flex-1 text-center">
                    <i class="fas fa-circle-notch fa-spin"></i>
                </div>
            </div>

            <div x-show="load" x-transition.duration.500ms>
                @if($livewireContent != NULL && $readyToLoad)
                    @livewire($livewireContent, $params)
                @endif
            </div>
        </div>

        @if ($options['footer'])
            <x-modal.footer>
                <x-button x-on:click="closeModal()">
                    Tutup
                </x-button>
            </x-modal.footer>
        @endif
    </div>
</div>
