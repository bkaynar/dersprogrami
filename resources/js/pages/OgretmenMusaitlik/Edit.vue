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

// View mode
const viewMode = ref<'grid' | 'list'>('grid');

// Seçili müsaitlik tipi (toplu işlem için)
const selectedTip = ref<string>('musait');

// Drag state
const isDragging = ref(false);
const dragTip = ref<string | null>(null);

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

const musaitlikTipleri = [
    { value: 'musait', label: 'Müsait', color: 'bg-green-500', borderColor: 'border-green-500', textColor: 'text-green-700' },
    { value: 'tercih_edilen', label: 'Tercih Edilen', color: 'bg-blue-500', borderColor: 'border-blue-500', textColor: 'text-blue-700' },
    { value: 'musait_degil', label: 'Müsait Değil', color: 'bg-red-500', borderColor: 'border-red-500', textColor: 'text-red-700' },
];

const setMusaitlik = (zamanDilimiId: number, tip: string | null) => {
    if (musaitlikDurumlari.value[zamanDilimiId] === tip) {
        musaitlikDurumlari.value[zamanDilimiId] = null;
    } else {
        musaitlikDurumlari.value[zamanDilimiId] = tip;
    }
};

const getMusaitlikColor = (zamanDilimiId: number) => {
    const tip = musaitlikDurumlari.value[zamanDilimiId];
    if (!tip) return 'bg-muted/50 border-muted';

    const tipObj = musaitlikTipleri.find(t => t.value === tip);
    return tipObj ? `${tipObj.color} ${tipObj.borderColor} text-white` : 'bg-muted/50 border-muted';
};

// Drag handlers
const handleMouseDown = (zamanDilimiId: number) => {
    isDragging.value = true;
    const currentTip = musaitlikDurumlari.value[zamanDilimiId];

    // İlk tıklamada seçili tip ile değiştir
    if (currentTip === selectedTip.value) {
        dragTip.value = null; // Temizle
        musaitlikDurumlari.value[zamanDilimiId] = null;
    } else {
        dragTip.value = selectedTip.value;
        musaitlikDurumlari.value[zamanDilimiId] = selectedTip.value;
    }
};

const handleMouseEnter = (zamanDilimiId: number) => {
    if (isDragging.value && dragTip.value !== undefined) {
        musaitlikDurumlari.value[zamanDilimiId] = dragTip.value;
    }
};

const handleMouseUp = () => {
    isDragging.value = false;
    dragTip.value = null;
};

// Toplu işlemler
const applyToAll = (tip: string) => {
    props.zaman_dilimleri.forEach(zd => {
        musaitlikDurumlari.value[zd.id] = tip;
    });
};

const clearAll = () => {
    props.zaman_dilimleri.forEach(zd => {
        musaitlikDurumlari.value[zd.id] = null;
    });
};

const applyToGun = (gun: string, tip: string) => {
    const zamanDilimleri = zamanDilimleriByGun.value[gun] || [];
    zamanDilimleri.forEach(zd => {
        musaitlikDurumlari.value[zd.id] = tip;
    });
};

const clearGun = (gun: string) => {
    const zamanDilimleri = zamanDilimleriByGun.value[gun] || [];
    zamanDilimleri.forEach(zd => {
        musaitlikDurumlari.value[zd.id] = null;
    });
};

const submit = () => {
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
    let toplam = props.zaman_dilimleri.length;

    Object.values(musaitlikDurumlari.value).forEach(tip => {
        if (tip === 'musait') musait++;
        else if (tip === 'musait_degil') musaitDegil++;
        else if (tip === 'tercih_edilen') tercihEdilen++;
    });

    return { musait, musaitDegil, tercihEdilen, toplam, bos: toplam - musait - musaitDegil - tercihEdilen };
});
</script>

<template>
    <Head :title="`${ogretmen.isim} - Müsaitlik Düzenle`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6" @mouseup="handleMouseUp" @mouseleave="handleMouseUp">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">{{ ogretmen.isim }} - Müsaitlik Takvimi</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        {{ ogretmen.unvan }} • Haftalık zaman dilimlerini tıklayarak veya sürükleyerek işaretleyin
                    </p>
                </div>

                <!-- View Toggle -->
                <div class="inline-flex rounded-lg border bg-background p-1">
                    <button
                        @click="viewMode = 'grid'"
                        :class="[
                            'inline-flex items-center gap-2 rounded-md px-3 py-1.5 text-sm font-medium transition-colors',
                            viewMode === 'grid'
                                ? 'bg-primary text-primary-foreground'
                                : 'hover:bg-muted'
                        ]"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                        </svg>
                        Takvim
                    </button>
                    <button
                        @click="viewMode = 'list'"
                        :class="[
                            'inline-flex items-center gap-2 rounded-md px-3 py-1.5 text-sm font-medium transition-colors',
                            viewMode === 'list'
                                ? 'bg-primary text-primary-foreground'
                                : 'hover:bg-muted'
                        ]"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        Liste
                    </button>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- İstatistikler ve Kontroller -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- İstatistikler -->
                    <div class="lg:col-span-2 grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="rounded-lg border bg-card p-4">
                            <div class="text-2xl font-bold">{{ stats.toplam }}</div>
                            <div class="text-xs text-muted-foreground mt-1">Toplam Dilim</div>
                        </div>
                        <div class="rounded-lg border border-green-200 bg-green-50 p-4">
                            <div class="text-2xl font-bold text-green-800">{{ stats.musait }}</div>
                            <div class="text-xs text-green-600 mt-1">Müsait</div>
                        </div>
                        <div class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                            <div class="text-2xl font-bold text-blue-800">{{ stats.tercihEdilen }}</div>
                            <div class="text-xs text-blue-600 mt-1">Tercih Edilen</div>
                        </div>
                        <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                            <div class="text-2xl font-bold text-red-800">{{ stats.musaitDegil }}</div>
                            <div class="text-xs text-red-600 mt-1">Müsait Değil</div>
                        </div>
                    </div>

                    <!-- Hızlı İşlemler -->
                    <div class="rounded-lg border bg-card p-4">
                        <div class="text-sm font-semibold mb-3">⚡ Toplu İşlemler</div>
                        <div class="space-y-2">
                            <button
                                v-for="tip in musaitlikTipleri"
                                :key="tip.value"
                                type="button"
                                @click="applyToAll(tip.value)"
                                :class="['w-full rounded-md px-3 py-1.5 text-xs font-medium text-white transition-all', tip.color]"
                            >
                                Tümü {{ tip.label }}
                            </button>
                            <button
                                type="button"
                                @click="clearAll"
                                class="w-full rounded-md border px-3 py-1.5 text-xs font-medium hover:bg-muted"
                            >
                                Tümünü Temizle
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Çizim Modu Seçici -->
                <div class="rounded-lg border bg-card p-4">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-semibold">🎨 Çizim Modu (Sürükle & Bırak)</div>
                        <div class="flex items-center gap-2">
                            <button
                                v-for="tip in musaitlikTipleri"
                                :key="tip.value"
                                type="button"
                                @click="selectedTip = tip.value"
                                :class="[
                                    'rounded-md px-4 py-2 text-xs font-medium transition-all',
                                    selectedTip === tip.value
                                        ? `${tip.color} text-white ring-2 ring-offset-2 ${tip.borderColor}`
                                        : 'bg-muted text-muted-foreground hover:bg-muted/80'
                                ]"
                            >
                                {{ tip.label }}
                            </button>
                        </div>
                    </div>
                    <p class="text-xs text-muted-foreground mt-2">
                        💡 İpucu: Bir renk seçin, sonra zaman dilimlerini tıklayın veya üzerinden sürükleyin
                    </p>
                </div>

                <!-- Grid View -->
                <div v-if="viewMode === 'grid'" class="rounded-lg border bg-card overflow-hidden">
                    <div class="grid grid-cols-7 divide-x border-b bg-muted/50">
                        <div
                            v-for="gun in gunSirasi"
                            :key="gun"
                            class="p-3 text-center"
                        >
                            <div class="font-semibold text-sm">{{ gunIsimleri[gun] }}</div>
                            <div class="text-xs text-muted-foreground mt-1">
                                {{ zamanDilimleriByGun[gun]?.length || 0 }} dilim
                            </div>
                            <div class="flex gap-1 justify-center mt-2">
                                <button
                                    v-for="tip in musaitlikTipleri.slice(0, 2)"
                                    :key="tip.value"
                                    type="button"
                                    @click="applyToGun(gun, tip.value)"
                                    :class="['w-4 h-4 rounded-sm', tip.color]"
                                    :title="`${gunIsimleri[gun]} - ${tip.label}`"
                                />
                                <button
                                    type="button"
                                    @click="clearGun(gun)"
                                    class="w-4 h-4 rounded-sm border bg-background hover:bg-muted"
                                    :title="`${gunIsimleri[gun]} - Temizle`"
                                >
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-7 divide-x min-h-[500px]">
                        <div
                            v-for="gun in gunSirasi"
                            :key="gun"
                            class="p-2 space-y-1.5"
                        >
                            <button
                                v-for="zamanDilimi in zamanDilimleriByGun[gun]"
                                :key="zamanDilimi.id"
                                type="button"
                                @mousedown="handleMouseDown(zamanDilimi.id)"
                                @mouseenter="handleMouseEnter(zamanDilimi.id)"
                                :class="[
                                    'w-full rounded-md border-2 px-2 py-2 text-xs font-medium transition-all select-none',
                                    getMusaitlikColor(zamanDilimi.id)
                                ]"
                            >
                                <div class="font-semibold">{{ formatSaat(zamanDilimi.baslangic_saati) }}</div>
                                <div class="text-[10px] opacity-80">{{ formatSaat(zamanDilimi.bitis_saati) }}</div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- List View -->
                <div v-else class="space-y-4">
                    <div v-for="gun in gunSirasi" :key="gun" class="rounded-lg border bg-card">
                        <div class="border-b bg-muted/50 px-4 py-3 flex items-center justify-between">
                            <h3 class="font-semibold">{{ gunIsimleri[gun] }}</h3>
                            <div class="flex gap-2">
                                <button
                                    v-for="tip in musaitlikTipleri"
                                    :key="tip.value"
                                    type="button"
                                    @click="applyToGun(gun, tip.value)"
                                    :class="['rounded px-2 py-1 text-xs font-medium text-white', tip.color]"
                                >
                                    {{ tip.label }}
                                </button>
                                <button
                                    type="button"
                                    @click="clearGun(gun)"
                                    class="rounded border px-2 py-1 text-xs font-medium hover:bg-muted"
                                >
                                    Temizle
                                </button>
                            </div>
                        </div>
                        <div class="p-4">
                            <div v-if="zamanDilimleriByGun[gun] && zamanDilimleriByGun[gun].length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                                <button
                                    v-for="zamanDilimi in zamanDilimleriByGun[gun]"
                                    :key="zamanDilimi.id"
                                    type="button"
                                    @click="setMusaitlik(zamanDilimi.id, selectedTip)"
                                    :class="[
                                        'rounded-lg border-2 px-3 py-2 text-sm font-medium transition-all',
                                        getMusaitlikColor(zamanDilimi.id)
                                    ]"
                                >
                                    <div class="font-semibold">{{ formatSaat(zamanDilimi.baslangic_saati) }}</div>
                                    <div class="text-xs opacity-80">{{ formatSaat(zamanDilimi.bitis_saati) }}</div>
                                </button>
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
                        class="inline-flex items-center gap-2 rounded-lg bg-primary px-6 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg v-if="form.processing" class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span v-else>💾 Kaydet</span>
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
