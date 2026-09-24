<x-filament-panels::page>

    @if (!$selesai)

        {{-- =========================
             TIMER UJIAN
        ========================== --}}

        <div
            wire:poll.1s="updateTimer"
            style="
                position: sticky;
                top: 10px;
                z-index: 50;
                margin-bottom: 25px;
            "
        >

            <div
                style="
                    background: #18181b;
                    border: 1px solid #3f3f46;
                    border-radius: 12px;
                    padding: 15px 20px;
                    text-align: center;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
                "
            >

                <div
                    style="
                        font-size: 14px;
                        color: #a1a1aa;
                        margin-bottom: 5px;
                    "
                >
                    Waktu Tersisa
                </div>

                <div
                    style="
                        font-size: 30px;
                        font-weight: 700;
                        color: white;
                    "
                >
                    {{ sprintf(
                        '%02d:%02d',
                        floor($remainingSeconds / 60),
                        $remainingSeconds % 60
                    ) }}
                </div>

            </div>

        </div>


        {{-- =========================
             TAMPILAN SOAL UJIAN
        ========================== --}}

        @foreach ($collections as $pelajaran)

            {{-- Nama Mata Pelajaran --}}
            <div style="font-size: 22px; font-weight: 700; margin-bottom: 15px;">
                {{ $pelajaran['name'] }}
            </div>


            {{-- Soal --}}
            @foreach ($pelajaran['soals'] as $index => $pertanyaan)

                <div style="margin-bottom: 25px;">

                    {{-- NOMOR SOAL --}}
                    <div style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">

                        {{ $index + 1 }}.

                        {!! $pertanyaan['payload'] !!}

                    </div>


                    {{-- PILIHAN JAWABAN --}}
                    <div style="margin-left: 5px;">

                        @foreach ($pertanyaan['answers'] as $jawaban)

                            <div style="margin-bottom: 8px;">

                                <label
                                    style="
                                        display: flex;
                                        align-items: center;
                                        gap: 8px;
                                        cursor: pointer;
                                    "
                                >

                                    <x-filament::input.radio
                                        wire:model="jawaban.{{ $pertanyaan['id'] }}"
                                        value="{{ $jawaban['id'] }}"
                                        name="jawaban_{{ $pertanyaan['id'] }}"
                                    />

                                    <span>
                                        {{ $jawaban['text'] }}
                                    </span>

                                </label>

                            </div>

                        @endforeach

                    </div>

                </div>

            @endforeach

            <hr style="margin: 30px 0; border-color: #444;">

        @endforeach


        {{-- =========================
             TOMBOL SELESAI
        ========================== --}}

        <div style="margin-top: 30px;">

            <x-filament::button
                wire:click="submitExam"
                wire:loading.attr="disabled"
            >
                Selesai
            </x-filament::button>

        </div>


    @else

        {{-- =========================
             HASIL UJIAN
        ========================== --}}

        <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 20px;">
            Hasil Ujian
        </h2>


        <div style="overflow-x: auto;">

            <table style="width: 100%; border-collapse: collapse;">

                <thead>

                    <tr>

                        <th style="border: 1px solid #555; padding: 15px; text-align: center;">
                            No
                        </th>

                        <th style="border: 1px solid #555; padding: 15px; text-align: center;">
                            Mata Pelajaran
                        </th>

                        <th style="border: 1px solid #555; padding: 15px; text-align: center;">
                            Score
                        </th>

                        <th style="border: 1px solid #555; padding: 15px; text-align: center;">
                            Jumlah Soal
                        </th>

                        <th style="border: 1px solid #555; padding: 15px; text-align: center;">
                            Benar
                        </th>

                        <th style="border: 1px solid #555; padding: 15px; text-align: center;">
                            Salah
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach ($hasil as $index => $item)

                        <tr>

                            <td style="border: 1px solid #555; padding: 15px;">
                                {{ $index + 1 }}
                            </td>

                            <td style="border: 1px solid #555; padding: 15px;">
                                {{ $item['subject'] }}
                            </td>

                            <td style="border: 1px solid #555; padding: 15px;">
                                {{ number_format($item['score'], 2) }}
                            </td>

                            <td style="border: 1px solid #555; padding: 15px;">
                                {{ $item['total'] }}
                            </td>

                            <td style="border: 1px solid #555; padding: 15px;">
                                {{ $item['correct'] }}
                            </td>

                            <td style="border: 1px solid #555; padding: 15px;">
                                {{ $item['wrong'] }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- =========================
             KEMBALI KE DAFTAR UJIAN
        ========================== --}}

        <div style="margin-top: 25px;">

            <x-filament::button
                color="success"
                tag="a"
                href="{{ \App\Filament\Test\Resources\Exams\ExamResource::getUrl('index') }}"
            >
                Selesai
            </x-filament::button>

        </div>

    @endif

</x-filament-panels::page>