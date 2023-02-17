<div wire:init="load" class="p-4">
    <x-table class="w-full" style="max-height: 20rem;">
        <x-slot name="body">
            <tr>
                <td class="text-center">/</td>
                <td class="text-center">0</td>
                @foreach ($this->jadwal as $jadwal)
                <td class="text-center">{{ $jadwal['angka_waris'] }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="text-center">0</td>
                @if (count($this->jadwal) > 0)
                    @for ($i = 0; $i <= count($this->jadwal); $i++)
                        <td class="text-center">{{ $this->jadwal[$i]['hasil_mentah'][0] ?? '' }}</td>
                    @endfor
                @endif
            </tr>
            @forelse ($this->jadwal as $jadwal)
                <tr>
                    <td class="text-center">{{ $jadwal['angka_waris'] }}</td>
                    @foreach ($jadwal['hasil_mentah'] as $hasil)
                        <td class="text-center">{{ $hasil }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td class="text-center">
                        Tidak mendapatkan jadwal.
                    </td>
                </tr>
            @endforelse
        </x-slot>
    </x-table>
</div>
