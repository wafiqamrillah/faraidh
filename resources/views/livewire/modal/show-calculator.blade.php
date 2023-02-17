<div class="p-4">
    <div class="grid grid-flow-row gap-3">
        <h6 class="font-bold">Harta:</h6>
        <div x-data="{ showMask : true }" class="relative">
            <x-input x-show="showMask" x-init="new Inputmask('currency').mask($el)" x-on:focus="showMask = false;" :value="$harta" class="absolute text-right"/>
            <x-input type="number" x-on:blur="showMask = true" wire:model="harta" class="text-right" />
        </div>
        <div>
            <x-table>
                <x-slot name="header">
                    <tr>
                        <th>Ahli Waris</th>
                        <th>Bagian</th>
                        <th>Harta Diterima</th>
                    </tr>
                </x-slot>

                <x-slot name="body">
                    @forelse ($data as $item)
                        <tr>
                            <td>{{ $item['ahli_waris'] }}</td>
                            <td class="text-center">{{ $item['bagian'] }}</td>
                            <td class="text-center">
                                <x-input x-init="new Inputmask('currency').mask($el)" :value="$item['bagian'] != 0 ? $item['bagian']/array_sum(array_map(fn($list) => $list['bagian'], $data)) * $harta : 0" class="text-right" disabled/>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">
                                Tidak ada hasil.
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-table>
        </div>
    </div>
</div>
