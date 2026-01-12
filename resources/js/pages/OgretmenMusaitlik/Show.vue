<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Ogretmen {
    id: number;
    isim: string;
    unvan: string;
    email: string;
}

interface ZamanDilimi {
    id: number;
    haftanin_gunu: string;
    baslangic_saati: string;
    bitis_saati: string;
}

interface Musaitlik {
    id: number;
    ogretmen_id: number;
    zaman_dilimi_id: number;
    musaitlik_tipi: string;
}

const props = defineProps<{
    ogretmen: Ogretmen;
    zaman_dilimleri: ZamanDilimi[];
    musaitlikler: Record<number, Musaitlik>;
    gerekli_ders_saati: number;
    musait_saat_sayisi: number;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Anasayfa',
        href: dashboard().url,
    },
    {
        title: 'Öğretmen Müsaitlik',
        href: '/ogretmen-musaitlik',
    },
    {
        title: props.ogretmen.isim,
        href: `/ogretmen-musaitlik/${props.ogretmen.id}`,
    },
];

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

const formatSaat = (saat: string) => {
    return saat.substring(0, 5);
};

const musaitlikTipiLabel = (tip: string | undefined) => {
    if (!tip) return 'Tanımsız';
    const labels: Record<string, string> = {
        'musait': 'Müsait',
        'musait_degil': 'Müsait Değil',
        'tercih_edilen': 'Tercih Edilen',
    };
    return labels[tip] || tip;
};

const musaitlikTipiClass = (tip: string | undefined) => {
    if (!tip) return 'bg-gray-100 text-gray-600';
    const classes: Record<string, string> = {
        'musait': 'bg-green-100 text-green-800',
        'musait_degil': 'bg-red-100 text-red-800',
        'tercih_edilen': 'bg-blue-100 text-blue-800',
    };
    return classes[tip] || 'bg-gray-100 text-gray-800';
};

// Zaman dilimlerini güne göre grupla
const zamanDilimleriByGun = computed(() => {
    const grouped: Record<string, ZamanDilimi[]> = {};
    gunSirasi.forEach(gun => {
        grouped[gun] = props.zaman_dilimleri
            .filter(zd => zd.haftanin_gunu === gun)
            .sort((a, b) => a.baslangic_saati.localeCompare(b.baslangic_saati));
    });
    return grouped;
});

const getMusaitlik = (zamanDilimiId: number) => {
    return props.musaitlikler[zamanDilimiId];
};
</script>

<template>
    <Head :title="`${ogretmen.isim} - Müsaitlik`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">{{ ogretmen.isim }} - Müsaitlik Durumu</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        {{ ogretmen.unvan }} • {{ ogretmen.email }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <Link
                        href="/ogretmen-musaitlik"
                        class="inline-flex items-center gap-2 rounded-lg border bg-card px-4 py-2 text-sm font-medium hover:bg-accent"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Geri Dön
                    </Link>
                    <Link
                        :href="`/ogretmen-musaitlik/${ogretmen.id}/edit`"
                        class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Düzenle
                    </Link>
                </div>
            </div>

            <!-- Ders Saati Bilgisi -->
            <div class="mb-6 rounded-lg border bg-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold">Ders Saati Durumu</h2>
                        <p class="text-sm text-muted-foreground mt-1">
                            Bu öğretmenin vermesi gereken toplam ders saati ve müsaitlik durumu
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold">{{ gerekli_ders_saati }}</div>
                        <div class="text-sm text-muted-foreground">saat gerekli</div>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 rounded-full bg-green-500"></div>
                        <div>
                            <div class="font-medium">{{ musait_saat_sayisi }} saat</div>
                            <div class="text-sm text-muted-foreground">Müsait</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 rounded-full bg-gray-300"></div>
                        <div>
                            <div class="font-medium">{{ zaman_dilimleri.length - musait_saat_sayisi }} saat</div>
                            <div class="text-sm text-muted-foreground">Müsait Değil</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div
                            :class="[
                                'w-4 h-4 rounded-full',
                                musait_saat_sayisi >= gerekli_ders_saati ? 'bg-green-500' : 'bg-red-500'
                            ]"
                        ></div>
                        <div>
                            <div
                                :class="[
                                    'font-medium',
                                    musait_saat_sayisi >= gerekli_ders_saati ? 'text-green-600' : 'text-red-600'
                                ]"
                            >
                                {{ musait_saat_sayisi >= gerekli_ders_saati ? 'Yeterli' : `${gerekli_ders_saati - musait_saat_sayisi} Eksik` }}
                            </div>
                            <div class="text-sm text-muted-foreground">Durum</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Haftalık Takvim -->
            <div class="space-y-4">
                <div v-for="gun in gunSirasi" :key="gun" class="rounded-lg border bg-card">
                    <div class="border-b bg-muted/50 px-4 py-3">
                        <h3 class="font-semibold">{{ gunIsimleri[gun] }}</h3>
                    </div>
                    <div class="p-4">
                        <div v-if="zamanDilimleriByGun[gun] && zamanDilimleriByGun[gun].length > 0" class="grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                            <div
                                v-for="zamanDilimi in zamanDilimleriByGun[gun]"
                                :key="zamanDilimi.id"
                                class="flex items-center justify-between rounded-md border p-3"
                            >
                                <div class="text-sm">
                                    <div class="font-medium">{{ formatSaat(zamanDilimi.baslangic_saati) }} - {{ formatSaat(zamanDilimi.bitis_saati) }}</div>
                                </div>
                                <span
                                    class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium"
                                    :class="musaitlikTipiClass(getMusaitlik(zamanDilimi.id)?.musaitlik_tipi)"
                                >
                                    {{ musaitlikTipiLabel(getMusaitlik(zamanDilimi.id)?.musaitlik_tipi) }}
                                </span>
                            </div>
                        </div>
                        <div v-else class="text-center text-sm text-muted-foreground py-4">
                            Bu gün için zaman dilimi tanımlanmamış
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
