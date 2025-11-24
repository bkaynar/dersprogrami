<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Check, Trash2, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';

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
    {
        title: 'Düzenle',
        href: `/grup-kisitlamalari/${props.grup.id}/edit`,
    },
];

// Kısıtlama durumlarını tut (true: müsait, false: müsait değil, null: tanımsız)
const kisitlamaDurumlari = ref<Record<number, boolean | null>>({});

// İlk yüklemede mevcut kısıtlamaları doldur
props.zaman_dilimleri.forEach(zd => {
    const kisitlama = props.kisitlamalar[zd.id];
    if (kisitlama) {
        kisitlamaDurumlari.value[zd.id] = kisitlama.musait_mi;
    } else {
        kisitlamaDurumlari.value[zd.id] = null;
    }
});

const form = useForm({
    kisitlamalar: {} as Record<number, boolean>,
});

// View mode
const viewMode = ref<'grid' | 'list'>('grid');

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

const durumTipleri = [
    { value: true, label: 'Müsait', color: 'text-green-600 bg-green-50 border-green-200 hover:bg-green-100', activeColor: 'bg-green-500 text-white border-green-600' },
    { value: false, label: 'Müsait Değil', color: 'text-red-600 bg-red-50 border-red-200 hover:bg-red-100', activeColor: 'bg-red-500 text-white border-red-600' },
];

const setDurum = (zamanDilimiId: number, durum: boolean) => {
    if (kisitlamaDurumlari.value[zamanDilimiId] === durum) {
        kisitlamaDurumlari.value[zamanDilimiId] = null;
    } else {
        kisitlamaDurumlari.value[zamanDilimiId] = durum;
    }
};

const getButtonClass = (zamanDilimiId: number, durumValue: boolean, typeConfig: any) => {
    const isSelected = kisitlamaDurumlari.value[zamanDilimiId] === durumValue;
    return [
        'p-1.5 rounded-md border transition-all duration-200',
        isSelected ? typeConfig.activeColor : 'bg-background border-transparent hover:bg-muted text-muted-foreground'
    ];
};

const getContainerClass = (zamanDilimiId: number) => {
    const status = kisitlamaDurumlari.value[zamanDilimiId];
    if (status === null || status === undefined) return 'bg-card border-border';
    
    if (status === true) return 'bg-green-50/50 border-green-200';
    if (status === false) return 'bg-red-50/50 border-red-200';
    
    return 'bg-card border-border';
};

// Toplu işlemler
const applyToAll = (durum: boolean) => {
    props.zaman_dilimleri.forEach(zd => {
        kisitlamaDurumlari.value[zd.id] = durum;
    });
};

const clearAll = () => {
    props.zaman_dilimleri.forEach(zd => {
        kisitlamaDurumlari.value[zd.id] = null;
    });
};

const applyToGun = (gun: string, durum: boolean) => {
    const zamanDilimleri = zamanDilimleriByGun.value[gun] || [];
    zamanDilimleri.forEach(zd => {
        kisitlamaDurumlari.value[zd.id] = durum;
    });
};

const clearGun = (gun: string) => {
    const zamanDilimleri = zamanDilimleriByGun.value[gun] || [];
    zamanDilimleri.forEach(zd => {
        kisitlamaDurumlari.value[zd.id] = null;
    });
};

const submit = () => {
    const kisitlamalar: Record<number, boolean> = {};
    Object.entries(kisitlamaDurumlari.value).forEach(([id, durum]) => {
        if (durum !== null) {
            kisitlamalar[Number(id)] = durum;
        }
    });

    form.kisitlamalar = kisitlamalar;
    form.put(`/grup-kisitlamalari/${props.grup.id}`, {
        preserveScroll: true,
    });
};

// İstatistikler
const stats = computed(() => {
    let musait = 0;
    let musaitDegil = 0;
    let toplam = props.zaman_dilimleri.length;

    Object.values(kisitlamaDurumlari.value).forEach(durum => {
        if (durum === true) musait++;
        else if (durum === false) musaitDegil++;
    });

    return { musait, musaitDegil, toplam, bos: toplam - musait - musaitDegil };
});
</script>

<template>
    <Head :title="`${grup.isim} - Kısıtlama Düzenle`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">{{ grup.isim }} - Müsaitlik Kısıtlamaları</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        {{ grup.yil }}. Yıl • Zaman dilimlerinin müsaitlik durumunu belirleyin
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
                    <div class="lg:col-span-2 grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <div class="rounded-lg border bg-card p-4">
                            <div class="text-2xl font-bold">{{ stats.toplam }}</div>
                            <div class="text-xs text-muted-foreground mt-1">Toplam Dilim</div>
                        </div>
                        <div class="rounded-lg border border-green-200 bg-green-50 p-4">
                            <div class="text-2xl font-bold text-green-800">{{ stats.musait }}</div>
                            <div class="text-xs text-green-600 mt-1">Müsait</div>
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
                                v-for="tip in durumTipleri"
                                :key="String(tip.value)"
                                type="button"
                                @click="applyToAll(tip.value)"
                                :class="['w-full rounded-md px-3 py-1.5 text-xs font-medium text-white transition-all', tip.value ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700']"
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
                            <div class="flex gap-1 justify-center mt-2 opacity-50 hover:opacity-100 transition-opacity">
                                <button
                                    v-for="tip in durumTipleri"
                                    :key="String(tip.value)"
                                    type="button"
                                    @click="applyToGun(gun, tip.value)"
                                    :class="['w-4 h-4 rounded-sm border transition-colors', tip.value ? 'bg-green-500 border-green-600' : 'bg-red-500 border-red-600']"
                                    :title="`${gunIsimleri[gun]} - ${tip.label}`"
                                />
                                <button
                                    type="button"
                                    @click="clearGun(gun)"
                                    class="w-4 h-4 rounded-sm border bg-background hover:bg-muted"
                                    :title="`${gunIsimleri[gun]} - Temizle`"
                                >
                                    <Trash2 class="w-3 h-3" />
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-7 divide-x min-h-[500px]">
                        <div
                            v-for="gun in gunSirasi"
                            :key="gun"
                            class="p-2 space-y-2 h-full"
                        >
                            <div
                                v-for="zamanDilimi in zamanDilimleriByGun[gun]"
                                :key="zamanDilimi.id"
                                :class="['rounded-lg border p-2 transition-colors', getContainerClass(zamanDilimi.id)]"
                            >
                                <div class="text-center mb-2">
                                    <div class="font-semibold text-xs">{{ formatSaat(zamanDilimi.baslangic_saati) }}</div>
                                    <div class="text-[10px] opacity-70">{{ formatSaat(zamanDilimi.bitis_saati) }}</div>
                                </div>
                                
                                <div class="flex justify-center gap-1">
                                    <button
                                        type="button"
                                        @click="setDurum(zamanDilimi.id, true)"
                                        :class="getButtonClass(zamanDilimi.id, true, durumTipleri[0])"
                                        title="Müsait"
                                    >
                                        <Check class="w-3 h-3" />
                                    </button>
                                    <button
                                        type="button"
                                        @click="setDurum(zamanDilimi.id, false)"
                                        :class="getButtonClass(zamanDilimi.id, false, durumTipleri[1])"
                                        title="Müsait Değil"
                                    >
                                        <X class="w-3 h-3" />
                                    </button>
                                </div>
                            </div>
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
                                    v-for="tip in durumTipleri"
                                    :key="String(tip.value)"
                                    type="button"
                                    @click="applyToGun(gun, tip.value)"
                                    :class="['rounded px-2 py-1 text-xs font-medium text-white', tip.value ? 'bg-green-600' : 'bg-red-600']"
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
                            <div v-if="zamanDilimleriByGun[gun] && zamanDilimleriByGun[gun].length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                <div
                                    v-for="zamanDilimi in zamanDilimleriByGun[gun]"
                                    :key="zamanDilimi.id"
                                    :class="['rounded-lg border p-3 transition-colors flex items-center justify-between', getContainerClass(zamanDilimi.id)]"
                                >
                                    <div>
                                        <div class="font-semibold text-sm">{{ formatSaat(zamanDilimi.baslangic_saati) }}</div>
                                        <div class="text-xs opacity-70">{{ formatSaat(zamanDilimi.bitis_saati) }}</div>
                                    </div>
                                    
                                    <div class="flex gap-1">
                                        <button
                                            type="button"
                                            @click="setDurum(zamanDilimi.id, true)"
                                            :class="getButtonClass(zamanDilimi.id, true, durumTipleri[0])"
                                            title="Müsait"
                                        >
                                            <Check class="w-4 h-4" />
                                        </button>
                                        <button
                                            type="button"
                                            @click="setDurum(zamanDilimi.id, false)"
                                            :class="getButtonClass(zamanDilimi.id, false, durumTipleri[1])"
                                            title="Müsait Değil"
                                        >
                                            <X class="w-4 h-4" />
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
                        :href="`/grup-kisitlamalari/${grup.id}`"
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