<div wire:init="load">
    <div class="p-4 grid grid-flow-row gap-3">
        @if ($show)
            <h6>Hasil :</h6>
            <x-table>
                <x-slot name="header">
                    <tr>
                        <th colspan="5" style="vertical-align: middle;">Asal Mas'alah = {{ $data['header']['asalmasalah'] ?? 0 }}</th>
                        <th rowspan="2">No. Dalil</th>
                    </tr>
                    <tr>
                        <th>Jml</th>
                        <th>Ahli Waris</th>
                        <th>Kode</th>
                        <th>N</th>
                        <th>Bg.</th>
                    </tr>
                </x-slot>
                <x-slot name="body">
                    @forelse ($data['list'] as $list)
                        <tr>
                            <td class="text-center" style="min-width: 50px;">{{ $list['jumlah'] }}</td>
                            <td style="min-width: 150px;">{{ $list['ahli_waris'] }}</td>
                            <td class="text-center" style="min-width: 50px;">{{ $list['angka_waris'] }}</td>
                            <td class="text-center" style="min-width: 50px;">{{ $list['hasil_matang'] }}</td>
                            <td class="text-center" style="min-width: 50px;">{{ $list['bagian'] }}</td>
                            <td class="text-center" style="min-width: 50px;">{{ $list['nomor_dalil'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                Tidak ada hasil.
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-table>
            <h6>Proses :</h6>
            <x-table class="text-center">
                <x-slot name="header">
                    <tr>
                        <th>0</th>
                        @foreach ($data['list'] as $list)
                            <th>{{ $list['angka_waris'] }}</th>
                        @endforeach
                    </tr>
                </x-slot>
                <x-slot name="body">
                    @for ($i = 0; $i < max(array_map(fn($list) => count($list['hasil_mentah']), $data['list'])); $i++)
                        <tr>
                            <td></td>
                            @foreach ($data['list'] as $list)
                                <td>{{ $list['hasil_mentah'][$i] ?? '' }}</td>
                            @endforeach
                        </tr>
                    @endfor
                    <tr>
                        <td colspan="{{ count($data['list']) + 1 }}">
                            Hasil Matang
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        @foreach ($data['list'] as $list)
                            <td>{{ $list['hasil_matang']?? '' }}</td>
                        @endforeach
                    </tr>
                </x-slot>
            </x-table>
        @endif
    </div>

    <x-modal.footer>
        <x-button x-on:click="closeModal()">
            Tutup
        </x-button>
        <x-button wire:click="showJadwalFaraidh" wire:loading.attr="disabled">
            <i class="fas fa-table"></i> Lihat Jadwal Faraidh
        </x-button>
    </x-modal.footer>
</div>
