<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface ZamanDilimi {
    id: number;
    haftanin_gunu: string;
    baslangic_saati: string;
    bitis_saati: string;
}

const props = defineProps<{
    zaman_dilimleri: ZamanDilimi[];
}>();

// View toggle
const viewMode = ref<'card' | 'calendar'>('calendar');

// Import form
const fileInput = ref<HTMLInputElement | null>(null);
const importForm = useForm({
    file: null as File | null,
}); const downloadTemplate = () => {
    window.location.href = '/zaman-dilimleri/template/download';
};

const selectFile = () => {
    fileInput.value?.click();
};

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        importForm.file = target.files[0];
        uploadFile();
    }
};

const uploadFile = () => {
    if (!importForm.file) return;

    importForm.post('/zaman-dilimleri/import', {
        forceFormData: true,
        onSuccess: () => {
            importForm.reset();
            if (fileInput.value) {
                fileInput.value.value = '';
            }
        },
        onError: () => {
            importForm.reset();
            if (fileInput.value) {
                fileInput.value.value = '';
            }
        },
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Anasayfa',
        href: dashboard().url,
    },
    {
        title: 'Zaman Dilimleri',
        href: '/zaman-dilimleri',
    },
];

const formatTime = (time: string) => {
    return time.substring(0, 5);
};

const calculateDuration = (start: string, end: string) => {
    return Math.floor(
        (new Date(`2000-01-01 ${end}`).getTime() -
         new Date(`2000-01-01 ${start}`).getTime()) /
        (1000 * 60)
    );
};

// Flash messages
const page = usePage<any>();
const flashSuccess = computed(() => page.props.flash?.success || page.props.success);
const flashError = computed(() => page.props.flash?.error || page.props.error);
const flashWarning = computed(() => page.props.flash?.warning || page.props.warning);
const flashImportErrors = computed(() => page.props.flash?.import_errors || page.props.import_errors);

// Günleri sırala
const gunSirasi = ['pazartesi', 'sali', 'carsamba', 'persembe', 'cuma', 'cumartesi', 'pazar'];
const gunIsimleri: Record<string, string> = {
    'pazartesi': 'Pazartesi',
    'sali': 'Salı',
    'carsamba': 'Çarşamba',
    'persembe': 'Perşembe',
    'cuma': 'Cuma',
    'cumartesi': 'Cumartesi',
    'pazar': 'Pazar',
};

// Günlere göre grupla
const zamanDilimleriByGun = computed(() => {
    const grouped: Record<string, ZamanDilimi[]> = {};
    gunSirasi.forEach(gun => {
        grouped[gun] = props.zaman_dilimleri
            .filter(d => d.haftanin_gunu === gun)
            .sort((a, b) => a.baslangic_saati.localeCompare(b.baslangic_saati));
    });
    return grouped;
});

// Renk paleti
const gunRenkleri: Record<string, string> = {
    'pazartesi': 'bg-blue-100 text-blue-800 border-blue-200',
    'sali': 'bg-green-100 text-green-800 border-green-200',
    'carsamba': 'bg-yellow-100 text-yellow-800 border-yellow-200',
    'persembe': 'bg-purple-100 text-purple-800 border-purple-200',
    'cuma': 'bg-pink-100 text-pink-800 border-pink-200',
    'cumartesi': 'bg-orange-100 text-orange-800 border-orange-200',
    'pazar': 'bg-red-100 text-red-800 border-red-200',
};
</script>

<template>
    <Head title="Zaman Dilimleri" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Flash Messages -->
            <div v-if="flashSuccess || flashError || flashWarning" class="mb-6 space-y-4">
                <div v-if="flashWarning" class="border-yellow-200 bg-yellow-50 text-yellow-800 rounded-lg p-4">
                    <strong>Uyarı!</strong>
                    <p class="mt-1 text-sm">{{ flashWarning }}</p>
                    <ul v-if="flashImportErrors && flashImportErrors.length > 0" class="mt-2 list-inside list-disc text-sm">
                        <li v-for="(error, index) in flashImportErrors" :key="index">{{ error }}</li>
                    </ul>
                </div>
                <div v-if="flashSuccess" class="border-green-200 bg-green-50 text-green-800 rounded-lg p-4">
                    <strong>Başarılı!</strong>
                    <p class="mt-1 text-sm">{{ flashSuccess }}</p>
                </div>

                <div v-if="flashError" class="border-red-200 bg-red-50 text-red-800 rounded-lg p-4">
                    <strong>Hata!</strong>
                    <p class="mt-1 text-sm">{{ flashError }}</p>
                </div>
            </div>

            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Zaman Dilimleri</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Tüm zaman dilimlerini görüntüleyin ve yönetin
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button
                        @click="downloadTemplate"
                        class="inline-flex items-center gap-2 rounded-lg border bg-background px-4 py-2 text-sm font-medium hover:bg-muted"
                        title="Excel şablonunu indir"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Şablon İndir
                    </button>
                    <button
                        @click="selectFile"
                        :disabled="importForm.processing"
                        class="inline-flex items-center gap-2 rounded-lg border bg-background px-4 py-2 text-sm font-medium hover:bg-muted disabled:opacity-50"
                        title="Excel dosyası yükle"
                    >
                        <svg v-if="importForm.processing" class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        {{ importForm.processing ? 'Yükleniyor...' : 'Excel Yükle' }}
                    </button>
                    <input
                        ref="fileInput"
                        type="file"
                        accept=".xlsx,.xls"
                        class="hidden"
                        @change="handleFileChange"
                    />

                    <Link
                        href="/zaman-dilimleri/create"
                        class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                        title="Otomatik zaman dilimi oluştur"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Otomatik Oluştur
                    </Link>
                </div>
            </div>

            <!-- View Toggle -->
            <div v-if="zaman_dilimleri.length > 0" class="mb-6 flex items-center justify-center">
                <div class="inline-flex rounded-lg border bg-muted/50 p-1">
                    <button
                        @click="viewMode = 'calendar'"
                        :class="[
                            'inline-flex items-center gap-2 rounded-md px-4 py-2 text-sm font-medium transition-all',
                            viewMode === 'calendar'
                                ? 'bg-background shadow-sm'
                                : 'text-muted-foreground hover:text-foreground',
                        ]"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Takvim Görünümü
                    </button>
                    <button
                        @click="viewMode = 'card'"
                        :class="[
                            'inline-flex items-center gap-2 rounded-md px-4 py-2 text-sm font-medium transition-all',
                            viewMode === 'card'
                                ? 'bg-background shadow-sm'
                                : 'text-muted-foreground hover:text-foreground',
                        ]"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Kart Görünümü
                    </button>
                </div>
            </div>

            <!-- Calendar View -->
            <div v-if="viewMode === 'calendar' && zaman_dilimleri.length > 0" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                <div
                    v-for="gun in gunSirasi"
                    :key="gun"
                    class="rounded-lg border bg-card p-4"
                >
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="font-semibold capitalize">{{ gunIsimleri[gun] }}</h3>
                        <span class="rounded-full bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary">
                            {{ zamanDilimleriByGun[gun].length }}
                        </span>
                    </div>
                    <div class="space-y-2">
                        <div
                            v-for="dilim in zamanDilimleriByGun[gun]"
                            :key="dilim.id"
                            :class="[
                                'rounded-lg border-2 p-3 transition-all hover:shadow-md',
                                gunRenkleri[gun],
                            ]"
                        >
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-semibold">
                                        {{ formatTime(dilim.baslangic_saati) }}
                                    </span>
                                </div>
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                                <span class="text-sm font-semibold">
                                    {{ formatTime(dilim.bitis_saati) }}
                                </span>
                            </div>
                            <div class="text-xs opacity-75">
                                {{ calculateDuration(dilim.baslangic_saati, dilim.bitis_saati) }} dakika
                            </div>
                            <div class="mt-2 flex gap-1">
                                <Link
                                    :href="`/zaman-dilimleri/${dilim.id}/edit`"
                                    class="flex-1 rounded-md bg-white/50 px-2 py-1 text-center text-xs font-medium hover:bg-white/80"
                                >
                                    Düzenle
                                </Link>
                            </div>
                        </div>
                        <div v-if="zamanDilimleriByGun[gun].length === 0" class="rounded-lg border-2 border-dashed p-3 text-center text-sm text-muted-foreground">
                            Zaman dilimi yok
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card View -->
            <div v-if="viewMode === 'card' && zaman_dilimleri.length > 0" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <div
                    v-for="dilim in zaman_dilimleri"
                    :key="dilim.id"
                    :class="[
                        'rounded-xl border-2 p-6 transition-all hover:shadow-lg hover:-translate-y-1',
                        gunRenkleri[dilim.haftanin_gunu],
                    ]"
                >
                    <div class="mb-4">
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/50 px-3 py-1 text-sm font-bold capitalize">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ gunIsimleri[dilim.haftanin_gunu] }}
                        </span>
                    </div>
                    <div class="mb-4 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium opacity-75">Başlangıç</span>
                            <span class="text-2xl font-bold">{{ formatTime(dilim.baslangic_saati) }}</span>
                        </div>
                        <div class="flex items-center justify-center opacity-50">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium opacity-75">Bitiş</span>
                            <span class="text-2xl font-bold">{{ formatTime(dilim.bitis_saati) }}</span>
                        </div>
                    </div>
                    <div class="mb-4 rounded-lg bg-white/50 px-3 py-2 text-center">
                        <div class="text-xs font-medium opacity-75">Süre</div>
                        <div class="text-lg font-bold">
                            {{ calculateDuration(dilim.baslangic_saati, dilim.bitis_saati) }} dk
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <Link
                            :href="`/zaman-dilimleri/${dilim.id}`"
                            class="flex-1 rounded-lg bg-white/50 px-3 py-2 text-center text-sm font-medium hover:bg-white/80"
                        >
                            Görüntüle
                        </Link>
                        <Link
                            :href="`/zaman-dilimleri/${dilim.id}/edit`"
                            class="flex-1 rounded-lg bg-white/50 px-3 py-2 text-center text-sm font-medium hover:bg-white/80"
                        >
                            Düzenle
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="zaman_dilimleri.length === 0" class="flex flex-col items-center justify-center rounded-lg border border-dashed py-12">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-muted">
                    <svg class="h-6 w-6 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="mt-4 font-semibold">Henüz zaman dilimi yok</h3>
                <p class="mt-2 text-center text-sm text-muted-foreground">
                    Başlamak için ilk zaman diliminizi oluşturun
                </p>
                <Link
                    href="/zaman-dilimleri/create"
                    class="mt-4 inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    İlk Zaman Dilimini Oluştur
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
