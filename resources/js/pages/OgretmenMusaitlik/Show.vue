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
    haftanin_gunu: number;
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

const gunIsmi = (gun: number) => {
    const gunler = ['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi', 'Pazar'];
    return gunler[gun - 1] || gun.toString();
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
    const grouped: Record<number, ZamanDilimi[]> = {};
    props.zaman_dilimleri.forEach(zd => {
        if (!grouped[zd.haftanin_gunu]) {
            grouped[zd.haftanin_gunu] = [];
        }
        grouped[zd.haftanin_gunu].push(zd);
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

            <!-- Haftalık Takvim -->
            <div class="space-y-4">
                <div v-for="gun in [1, 2, 3, 4, 5, 6, 7]" :key="gun" class="rounded-lg border bg-card">
                    <div class="border-b bg-muted/50 px-4 py-3">
                        <h3 class="font-semibold">{{ gunIsmi(gun) }}</h3>
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
