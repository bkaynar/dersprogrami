<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Anasayfa',
        href: dashboard().url,
    },
    {
        title: 'Zaman Dilimleri',
        href: '/zaman-dilimleri',
    },
    {
        title: 'Otomatik Oluştur',
        href: '/zaman-dilimleri/create',
    },
];

const form = useForm({
    baslangic_saati: '08:00',
    bitis_saati: '17:00',
    ders_suresi: 45,
    teneffus_suresi: 10,
    ogle_arasi_baslangic: '12:00',
    ogle_arasi_bitis: '13:00',
    pazartesi: true,
    sali: true,
    carsamba: true,
    persembe: true,
    cuma: true,
    cumartesi: false,
    pazar: false,
});

const gunler = [
    { key: 'pazartesi', label: 'Pazartesi' },
    { key: 'sali', label: 'Salı' },
    { key: 'carsamba', label: 'Çarşamba' },
    { key: 'persembe', label: 'Perşembe' },
    { key: 'cuma', label: 'Cuma' },
    { key: 'cumartesi', label: 'Cumartesi' },
    { key: 'pazar', label: 'Pazar' },
];

// Önizleme hesaplama
const preview = computed(() => {
    const slots: string[] = [];
    const start = parseTime(form.baslangic_saati);
    const end = parseTime(form.bitis_saati);
    const ogleStart = parseTime(form.ogle_arasi_baslangic);
    const ogleEnd = parseTime(form.ogle_arasi_bitis);

    let current = start;
    let slotNumber = 1;

    while (current + form.ders_suresi <= end) {
        const slotStart = formatTime(current);
        const slotEnd = formatTime(current + form.ders_suresi);

        // Öğle arası kontrolü
        if (current >= ogleStart && current < ogleEnd) {
            if (current === ogleStart) {
                slots.push(`🍽️ ÖĞLE ARASI (${formatTime(ogleStart)} - ${formatTime(ogleEnd)})`);
            }
            current = ogleEnd;
            continue;
        }

        slots.push(`${slotNumber}. Ders: ${slotStart} - ${slotEnd}`);
        current += form.ders_suresi + form.teneffus_suresi;
        slotNumber++;
    }

    return slots;
});

function parseTime(time: string): number {
    const [hours, minutes] = time.split(':').map(Number);
    return hours * 60 + minutes;
}

function formatTime(minutes: number): string {
    const h = Math.floor(minutes / 60);
    const m = minutes % 60;
    return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
}

const submit = () => {
    form.post('/zaman-dilimleri/generate', {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Zaman Dilimi Otomatik Oluştur" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-semibold">Zaman Dilimlerini Otomatik Oluştur</h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Parametrelere göre tüm hafta için otomatik zaman dilimleri oluşturun
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form -->
                <div class="lg:col-span-2">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Zaman Ayarları -->
                        <div class="rounded-lg border bg-card p-6">
                            <h2 class="text-lg font-semibold mb-4">⏰ Zaman Ayarları</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Başlangıç Saati -->
                                <div>
                                    <label class="block text-sm font-medium mb-2">
                                        Başlangıç Saati
                                    </label>
                                    <input
                                        v-model="form.baslangic_saati"
                                        type="time"
                                        class="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                        required
                                    />
                                    <p v-if="form.errors.baslangic_saati" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.baslangic_saati }}
                                    </p>
                                </div>

                                <!-- Bitiş Saati -->
                                <div>
                                    <label class="block text-sm font-medium mb-2">
                                        Bitiş Saati
                                    </label>
                                    <input
                                        v-model="form.bitis_saati"
                                        type="time"
                                        class="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                        required
                                    />
                                    <p v-if="form.errors.bitis_saati" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.bitis_saati }}
                                    </p>
                                </div>

                                <!-- Ders Süresi -->
                                <div>
                                    <label class="block text-sm font-medium mb-2">
                                        Ders Süresi (dakika)
                                    </label>
                                    <input
                                        v-model.number="form.ders_suresi"
                                        type="number"
                                        min="30"
                                        max="120"
                                        step="5"
                                        class="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                        required
                                    />
                                    <p v-if="form.errors.ders_suresi" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.ders_suresi }}
                                    </p>
                                </div>

                                <!-- Teneffüs Süresi -->
                                <div>
                                    <label class="block text-sm font-medium mb-2">
                                        Teneffüs Süresi (dakika)
                                    </label>
                                    <input
                                        v-model.number="form.teneffus_suresi"
                                        type="number"
                                        min="0"
                                        max="30"
                                        step="5"
                                        class="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                        required
                                    />
                                    <p v-if="form.errors.teneffus_suresi" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.teneffus_suresi }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Öğle Arası -->
                        <div class="rounded-lg border bg-card p-6">
                            <h2 class="text-lg font-semibold mb-4">🍽️ Öğle Arası</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Öğle Arası Başlangıç -->
                                <div>
                                    <label class="block text-sm font-medium mb-2">
                                        Öğle Arası Başlangıç
                                    </label>
                                    <input
                                        v-model="form.ogle_arasi_baslangic"
                                        type="time"
                                        class="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                        required
                                    />
                                    <p v-if="form.errors.ogle_arasi_baslangic" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.ogle_arasi_baslangic }}
                                    </p>
                                </div>

                                <!-- Öğle Arası Bitiş -->
                                <div>
                                    <label class="block text-sm font-medium mb-2">
                                        Öğle Arası Bitiş
                                    </label>
                                    <input
                                        v-model="form.ogle_arasi_bitis"
                                        type="time"
                                        class="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                        required
                                    />
                                    <p v-if="form.errors.ogle_arasi_bitis" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.ogle_arasi_bitis }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Günler -->
                        <div class="rounded-lg border bg-card p-6">
                            <h2 class="text-lg font-semibold mb-4">📅 Günler</h2>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <div
                                    v-for="gun in gunler"
                                    :key="gun.key"
                                    class="flex items-center gap-2"
                                >
                                    <input
                                        :id="gun.key"
                                        v-model="form[gun.key]"
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-2 focus:ring-primary"
                                    />
                                    <label :for="gun.key" class="text-sm font-medium cursor-pointer select-none">
                                        {{ gun.label }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end gap-3">
                            <a
                                href="/zaman-dilimleri"
                                class="rounded-lg border bg-background px-4 py-2 text-sm font-medium hover:bg-muted"
                            >
                                İptal
                            </a>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50"
                            >
                                <svg v-if="form.processing" class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ form.processing ? 'Oluşturuluyor...' : 'Oluştur' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Preview -->
                <div class="lg:col-span-1">
                    <div class="rounded-lg border bg-card p-6 sticky top-6">
                        <h2 class="text-lg font-semibold mb-4">👁️ Önizleme</h2>
                        <p class="text-sm text-muted-foreground mb-4">
                            Seçilen günler için oluşturulacak zaman dilimleri:
                        </p>

                        <div class="space-y-2 max-h-96 overflow-y-auto">
                            <div
                                v-for="(slot, index) in preview"
                                :key="index"
                                :class="[
                                    'p-2 rounded text-sm',
                                    slot.includes('ÖĞLE ARASI')
                                        ? 'bg-orange-100 text-orange-800 font-semibold'
                                        : 'bg-muted text-foreground'
                                ]"
                            >
                                {{ slot }}
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t">
                            <div class="text-sm text-muted-foreground space-y-1">
                                <p>Toplam ders sayısı: <strong>{{ preview.filter(s => !s.includes('ÖĞLE')).length }}</strong></p>
                                <p>Seçilen gün sayısı: <strong>{{ gunler.filter(g => form[g.key]).length }}</strong></p>
                                <p>Oluşturulacak dilim: <strong>{{ preview.filter(s => !s.includes('ÖĞLE')).length * gunler.filter(g => form[g.key]).length }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
