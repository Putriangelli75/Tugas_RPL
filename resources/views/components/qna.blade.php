<section class="section-shell section-divider py-16">
    <div class="grid gap-10 lg:grid-cols-[0.8fr_1.2fr]">
        <div>
            <p class="eyebrow-clean mb-4">QnA</p>
            <h2 class="text-3xl font-semibold tracking-[-0.01em] text-slate-950">Pertanyaan yang sering muncul.</h2>
        </div>

        <div class="divide-y divide-slate-200 border-y border-slate-200">
            @foreach ([
                [
                    'question' => 'Apakah harus punya akun untuk melihat lapangan?',
                    'answer' => 'Landing page menampilkan ringkasan fasilitas. Untuk melihat data lengkap dan masuk ke dashboard, pengguna perlu login.',
                ],
                [
                    'question' => 'Data lapangan berasal dari mana?',
                    'answer' => 'Data awal berasal dari seeder Laravel dan dapat dikonsumsi melalui endpoint API lapangan.',
                ],
                [
                    'question' => 'Apakah pembayaran sudah otomatis?',
                    'answer' => 'Saat ini alur pembayaran masih berupa upload bukti bayar dan verifikasi status booking.',
                ],
            ] as $item)
                <details class="group py-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950">
                        <span>{{ $item['question'] }}</span>
                        <span class="text-emerald-950 transition group-open:rotate-45">+</span>
                    </summary>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">{{ $item['answer'] }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>
