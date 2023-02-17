<div
    x-data="
    {
        showResult : @entangle('showResult').defer,
    }
    "
    class="overflow-x-auto overflow-hidden scrollbar-thin scrollbar-thumb-rounded scrollbar-thumb-gray-600 hover:scrollbar-track-gray-300 grid grid-cols-1 lg:grid-cols-2 gap-4">

    <div x-show="showResult" x-transition style="display: none;">
        <x-card class="p-4 grid grid-flow-row gap-4">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-xl">Hasil</h4>
                </div>

                <div class="flex items-center justify-end">
                    <x-button wire:click="showCalculator" wire:loading.attr="disabled" bg-color="bg-green-600 hover:bg-green-400 focus:bg-green-700">
                        <i wire:target="showCalculator" wire:loading.class="fa-circle-notch fa-spin" wire:loading.class.remove="fa-calculator" class="fas fa-calculator"></i>
                    </x-button>
                </div>
            </div>
            <x-table>
                <x-slot name="header">
                    <tr>
                        <th>Ahli Waris</th>
                        <th>Bagian</th>
                        <th>Aksi</th>
                    </tr>
                </x-slot>

                <x-slot name="body">
                    @forelse ($result as $item)
                        <tr>
                            <td>{{ $item['ahli_waris'] }}</td>
                            <td class="text-center">{{ $item['bagian'] }}</td>
                            <td class="text-center">
                                @if (isset($item['nomor_dalil']))
                                    <x-button wire:click="showDalil({{$item['nomor_dalil']}})" wire:loading.attr="disabled">
                                        <i wire:target="showDalil({{$item['nomor_dalil']}})" wire:loading.class="fa-circle-notch fa-spin" wire:loading.class.remove="fa-book" class="fas fa-book"></i>
                                    </x-button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">
                                Tidak ada hasil perhitungan
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-table>
            <div class="grid grid-cols-2 gap-3">
                <x-button wire:click="resetField" wire:loading.attr="disabled" bg-color="bg-red-600 hover:bg-red-400 focus:bg-red-700">
                    <i wire:target="resetField" wire:loading.class="fa-circle-notch fa-spin" wire:loading.class.remove="fa-undo-alt" class="fas fa-undo-alt"></i> Atur Ulang
                </x-button>
                <x-button wire:click="showPenjelasan" wire:loading.attr="disabled" bg-color="bg-green-600 hover:bg-green-400 focus:bg-green-700">
                    <i wire:target="showPenjelasan" wire:loading.class="fa-circle-notch fa-spin" wire:loading.class.remove="fa-list" class="fas fa-list"></i> Penjelasan
                </x-button>
            </div>
        </x-card>
    </div>

    <div class="{{ !$showResult ? 'col-span-2' : null }}">
        <x-card class="p-4 grid grid-flow-row gap-3">
            <div>
                <x-table style="max-height: 60rem;">
                    <x-slot name="header">
                        <tr>
                            <th>Ahli Waris (Hubungan dengan Pewaris)</th>
                            <th>Jumlah (orang)</th>
                        </tr>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($this->lists as $list)
                            <tr>
                                <td class="w-3/4">
                                    {{ $list->key }}
                                </td>
                                <td class="w-1/4">
                                    <x-input wire:model.defer="form.{{ $list->value }}" type="number" class="text-right"/>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-table>
            </div>
            <div class="grid grid-cols-{{ $showResult ? 2 : 1 }} gap-4">
                @if ($showResult)
                <x-button wire:click="resetField" wire:loading.attr="disabled" bg-color="bg-red-600 hover:bg-red-400 focus:bg-red-700">
                    <i wire:target="resetField" wire:loading.class="fa-circle-notch fa-spin" wire:loading.class.remove="fa-undo-alt" class="fas fa-undo-alt"></i> Atur Ulang
                </x-button>
                @endif
                <x-button wire:click="start" wire:loading.attr="disabled" bg-color="bg-blue-600 hover:bg-blue-400 focus:bg-blue-700">
                    <i wire:target="start" wire:loading.class="fa-circle-notch fa-spin" wire:loading.class.remove="fa-play" class="fas fa-play"></i> Mulai
                </x-button>
            </div>
        </x-card>
    </div>
</div>
