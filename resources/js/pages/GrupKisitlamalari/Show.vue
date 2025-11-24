<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

interface OgrenciGrubu {
    id: number;
    isim: string;
    yil: number;
}

interface ZamanDilimi {
    id: number;
    haftanin_gunu: string;
    baslangic_saati: string;
    bitis_saati: string;
}

interface GrupKisitlama {
    id: number;
    ogrenci_grup_id: number;
    zaman_dilimi_id: number;
    musait_mi: boolean;
}

const props = defineProps<{
    grup: OgrenciGrubu;
    zaman_dilimleri: ZamanDilimi[];
    kisitlamalar: Record<number, GrupKisitlama>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Anasayfa',
        href: dashboard().url,
    },
    {
        title: 'Grup Kısıtlamaları',
        href: '/grup-kisitlamalari',
    },
    {
        title: props.grup.isim,
        href: `/grup-kisitlamalari/${props.grup.id}`,
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

const durumLabel = (durum: boolean | undefined) => {
    if (durum === undefined || durum === null) return 'Tanımsız';
    return durum ? 'Müsait' : 'Müsait Değil';
};

const durumClass = (durum: boolean | undefined) => {
    if (durum === undefined || durum === null) return 'bg-gray-100 text-gray-600';
    return durum ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
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

const getKisitlama = (zamanDilimiId: number) => {
    return props.kisitlamalar[zamanDilimiId];
};
</script>

<template>
    <Head :title="`${grup.isim} - Kısıtlama Durumu`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">{{ grup.isim }} - Müsaitlik Kısıtlamaları</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        {{ grup.yil }}. Yıl
                    </p>
                </div>
                <div class="flex gap-2">
                    <Link
                        href="/grup-kisitlamalari"
                        class="inline-flex items-center gap-2 rounded-lg border bg-card px-4 py-2 text-sm font-medium hover:bg-accent"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Geri Dön
                    </Link>
                    <Link
                        :href="`/grup-kisitlamalari/${grup.id}/edit`"
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
                                    :class="durumClass(getKisitlama(zamanDilimi.id)?.musait_mi)"
                                >
                                    {{ durumLabel(getKisitlama(zamanDilimi.id)?.musait_mi) }}
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