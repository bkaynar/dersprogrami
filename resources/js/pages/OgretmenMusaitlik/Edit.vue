<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

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
    {
        title: 'Düzenle',
        href: `/ogretmen-musaitlik/${props.ogretmen.id}/edit`,
    },
];

// Müsaitlik durumlarını tut
const musaitlikDurumlari = ref<Record<number, string | null>>({});

// İlk yüklemede mevcut müsaitleri doldur
props.zaman_dilimleri.forEach(zd => {
    const musaitlik = props.musaitlikler[zd.id];
    musaitlikDurumlari.value[zd.id] = musaitlik?.musaitlik_tipi || null;
});

const form = useForm({
    musaitlikler: {} as Record<number, string>,
});

const gunIsmi = (gun: number) => {
    const gunler = ['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi', 'Pazar'];
    return gunler[gun - 1] || gun.toString();
};

const formatSaat = (saat: string) => {
    return saat.substring(0, 5);
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

const musaitlikTipleri = [
    { value: 'musait', label: 'Müsait', color: 'bg-green-500 hover:bg-green-600' },
    { value: 'musait_degil', label: 'Müsait Değil', color: 'bg-red-500 hover:bg-red-600' },
    { value: 'tercih_edilen', label: 'Tercih Edilen', color: 'bg-blue-500 hover:bg-blue-600' },
];

const setMusaitlik = (zamanDilimiId: number, tip: string | null) => {
    if (musaitlikDurumlari.value[zamanDilimiId] === tip) {
        // Aynı tipe tıklandıysa, seçimi kaldır
        musaitlikDurumlari.value[zamanDilimiId] = null;
    } else {
        musaitlikDurumlari.value[zamanDilimiId] = tip;
    }
};

const getActiveClass = (zamanDilimiId: number, tip: string) => {
    return musaitlikDurumlari.value[zamanDilimiId] === tip;
};

const submit = () => {
    // Sadece seçili olanları gönder
    const musaitlikler: Record<number, string> = {};
    Object.entries(musaitlikDurumlari.value).forEach(([id, tip]) => {
        if (tip) {
            musaitlikler[Number(id)] = tip;
        }
    });

    form.musaitlikler = musaitlikler;
    form.put(`/ogretmen-musaitlik/${props.ogretmen.id}`, {
        preserveScroll: true,
    });
};

// İstatistikler
const stats = computed(() => {
    let musait = 0;
    let musaitDegil = 0;
    let tercihEdilen = 0;

    Object.values(musaitlikDurumlari.value).forEach(tip => {
        if (tip === 'musait') musait++;
        else if (tip === 'musait_degil') musaitDegil++;
        else if (tip === 'tercih_edilen') tercihEdilen++;
    });

    return { musait, musaitDegil, tercihEdilen };
});
</script>

<template>
    <Head :title="`${ogretmen.isim} - Müsaitlik Düzenle`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-semibold">{{ ogretmen.isim }} - Müsaitlik Düzenle</h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    {{ ogretmen.unvan }} • Her zaman dilimi için müsaitlik durumunu seçin
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- İstatistikler -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-lg border bg-green-50 p-4">
                        <div class="text-2xl font-bold text-green-800">{{ stats.musait }}</div>
                        <div class="text-sm text-green-600">Müsait</div>
                    </div>
                    <div class="rounded-lg border bg-red-50 p-4">
                        <div class="text-2xl font-bold text-red-800">{{ stats.musaitDegil }}</div>
                        <div class="text-sm text-red-600">Müsait Değil</div>
                    </div>
                    <div class="rounded-lg border bg-blue-50 p-4">
                        <div class="text-2xl font-bold text-blue-800">{{ stats.tercihEdilen }}</div>
                        <div class="text-sm text-blue-600">Tercih Edilen</div>
                    </div>
                </div>

                <!-- Haftalık Takvim -->
                <div class="space-y-4">
                    <div v-for="gun in [1, 2, 3, 4, 5, 6, 7]" :key="gun" class="rounded-lg border bg-card">
                        <div class="border-b bg-muted/50 px-4 py-3">
                            <h3 class="font-semibold">{{ gunIsmi(gun) }}</h3>
                        </div>
                        <div class="p-4">
                            <div v-if="zamanDilimleriByGun[gun] && zamanDilimleriByGun[gun].length > 0" class="space-y-3">
                                <div
                                    v-for="zamanDilimi in zamanDilimleriByGun[gun]"
                                    :key="zamanDilimi.id"
                                    class="rounded-lg border bg-muted/20 p-3"
                                >
                                    <div class="mb-2 font-medium text-sm">
                                        {{ formatSaat(zamanDilimi.baslangic_saati) }} - {{ formatSaat(zamanDilimi.bitis_saati) }}
                                    </div>
                                    <div class="flex gap-2">
                                        <button
                                            v-for="tip in musaitlikTipleri"
                                            :key="tip.value"
                                            type="button"
                                            @click="setMusaitlik(zamanDilimi.id, tip.value)"
                                            :class="[
                                                'flex-1 rounded-md px-3 py-2 text-xs font-medium text-white transition-all',
                                                getActiveClass(zamanDilimi.id, tip.value)
                                                    ? tip.color + ' ring-2 ring-offset-2 ring-primary'
                                                    : 'bg-muted text-muted-foreground hover:bg-muted/80'
                                            ]"
                                        >
                                            {{ tip.label }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center text-sm text-muted-foreground py-4">
                                Bu gün için zaman dilimi tanımlanmamış
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 border-t pt-6">
                    <a
                        :href="`/ogretmen-musaitlik/${ogretmen.id}`"
                        class="inline-flex items-center gap-2 rounded-lg border bg-background px-4 py-2 text-sm font-medium hover:bg-muted"
                    >
                        İptal
                    </a>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg v-if="form.processing" class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span v-else>Kaydet</span>
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
