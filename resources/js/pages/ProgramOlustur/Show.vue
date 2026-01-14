<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, reactive, computed } from 'vue';
import draggable from 'vuedraggable';
import axios from 'axios';
import Swal from 'sweetalert2';

// Toast notification helper
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
});

interface ZamanDilim {
    id: number;
    gun: string;
    gun_sirasi: number;
    baslangic_saat: string;
    bitis_saat: string;
}

interface Grup {
    id: number;
    isim: string;
    yil: number;
}

interface Ders {
    id: number;
    ders_kodu: string;
    isim: string;
}

interface Ogretmen {
    id: number;
    isim: string;
    unvan: string;
}

interface Mekan {
    id: number;
    isim: string;
}

interface ProgramSlot {
    id: number;
    ogrenci_grup_id: number;
    ders_id: number;
    ogretmen_id: number;
    zaman_dilimi_id: number;
    mekan_id: number;
    ders: Ders;
    ogretmen: Ogretmen;
    mekan: Mekan;
}

const props = defineProps<{
    zaman_dilimleri: ZamanDilim[];
    gruplar: Grup[];
    program_tablosu: Record<number, {
        grup: Grup;
        dersler: Record<number, ProgramSlot | null>;
    }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Anasayfa', href: dashboard().url },
    { title: 'Program Oluştur', href: '/program-olustur' },
    { title: 'Programı Görüntüle', href: '/program-olustur/show' },
];

const gunler = ['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi', 'Pazar'];

// Grup filtreleme
const selectedGrup = ref<string>('all');

// Mevcut grupları hesapla
const availableGruplar = computed(() => {
    return Object.values(props.program_tablosu).map(item => ({
        id: item.grup.id,
        isim: item.grup.isim
    })).sort((a, b) => a.isim.localeCompare(b.isim));
});

// Filtrelenmiş program tablosu
const filteredProgramTablosu = computed(() => {
    if (selectedGrup.value === 'all') {
        return props.program_tablosu;
    }

    const filtered: typeof props.program_tablosu = {};
    Object.entries(props.program_tablosu).forEach(([grupId, item]) => {
        if (item.grup.isim === selectedGrup.value) {
            filtered[Number(grupId)] = item;
        }
    });
    return filtered;
});

// Dropdown state
const showExportDropdown = ref(false);
const showGrupDropdown = ref(false);

// Drag & Drop state
const isDragging = ref(false);
const isSaving = ref(false);
const isInitialized = ref(false);

// Reactive program data (for drag & drop updates)
const programData = reactive<Record<number, Record<number, ProgramSlot[]>>>({});

// Initialize program data from props
const initializeProgramData = () => {
    Object.keys(props.program_tablosu).forEach(grupId => {
        const gId = Number(grupId);
        programData[gId] = {};

        props.zaman_dilimleri.forEach(zaman => {
            const slot = props.program_tablosu[gId].dersler[zaman.id];
            programData[gId][zaman.id] = slot ? [slot] : [];
        });
    });
    isInitialized.value = true;
};

// Helper to safely get program data
const getSlotData = (grupId: number, zamanId: number): ProgramSlot[] => {
    if (!isInitialized.value || !programData[grupId] || !programData[grupId][zamanId]) {
        return [];
    }
    return programData[grupId][zamanId];
};

// Handle drag change - save to backend
const onDragChange = async (evt: any, grupId: number, zamanDilimiId: number) => {
    console.log('Drag change event:', evt);
    console.log('Target zamanDilimiId:', zamanDilimiId);

    // 'added' event'i item bu slot'a eklendiğinde tetiklenir
    if (evt.added) {
        const movedItem = evt.added.element as ProgramSlot;
        const originalZamanDilimiId = movedItem.zaman_dilimi_id;
        const originalGrupId = movedItem.ogrenci_grup_id;

        console.log('Item added to slot:', movedItem);
        console.log('Original zaman_dilimi_id:', originalZamanDilimiId);
        console.log('New zaman_dilimi_id:', zamanDilimiId);
        console.log('Original grup_id:', originalGrupId, 'Target grup_id:', grupId);

        // Farklı gruba taşınmaya çalışılıyorsa engelle ve geri al
        if (originalGrupId !== grupId) {
            console.log('Cross-group drag not allowed, reverting...');

            Toast.fire({
                icon: 'error',
                title: 'Farklı gruba taşıma yapılamaz!'
            });

            // Yeni slot'tan kaldır
            const newSlotIndex = programData[grupId][zamanDilimiId].findIndex(s => s.id === movedItem.id);
            if (newSlotIndex > -1) {
                programData[grupId][zamanDilimiId].splice(newSlotIndex, 1);
            }

            // Eski slot'a geri ekle
            if (!programData[originalGrupId][originalZamanDilimiId]) {
                programData[originalGrupId][originalZamanDilimiId] = [];
            }
            programData[originalGrupId][originalZamanDilimiId].push(movedItem);
            return;
        }

        // Aynı slot'a bırakıldıysa işlem yapma
        if (originalZamanDilimiId === zamanDilimiId) {
            console.log('Same slot, no action needed');
            return;
        }

        console.log('Saving... program_id:', movedItem.id, 'from:', originalZamanDilimiId, 'to:', zamanDilimiId);

        isSaving.value = true;

        try {
            const response = await axios.post('/program-olustur/update-slot', {
                program_id: movedItem.id,
                new_zaman_dilimi_id: zamanDilimiId,
            });

            console.log('Response:', response.data);

            if (response.data.success) {
                // Başarılı olduğunda item'ın zaman_dilimi_id'sini güncelle
                movedItem.zaman_dilimi_id = zamanDilimiId;

                Toast.fire({
                    icon: 'success',
                    title: response.data.swapped ? 'Dersler yer değiştirdi!' : 'Ders taşındı!'
                });

                setTimeout(() => router.reload(), 500);
            } else {
                // Backend başarısız döndüyse geri al
                revertDrag(movedItem, grupId, zamanDilimiId, originalZamanDilimiId);

                Toast.fire({
                    icon: 'error',
                    title: response.data.message || 'İşlem başarısız'
                });
            }
        } catch (error: any) {
            console.error('Error:', error);
            // Hata durumunda geri al
            revertDrag(movedItem, grupId, zamanDilimiId, originalZamanDilimiId);

            Toast.fire({
                icon: 'error',
                title: error.response?.data?.message || 'Kaydetme başarısız'
            });
        } finally {
            isSaving.value = false;
        }
    }
};

// Drag işlemini geri al
const revertDrag = (movedItem: ProgramSlot, grupId: number, newZamanId: number, originalZamanId: number) => {
    console.log('Reverting drag...', { grupId, newZamanId, originalZamanId });

    // Yeni slot'tan kaldır
    const newSlotIndex = programData[grupId][newZamanId].findIndex(s => s.id === movedItem.id);
    if (newSlotIndex > -1) {
        programData[grupId][newZamanId].splice(newSlotIndex, 1);
    }

    // Eski slot'a geri ekle
    if (!programData[grupId][originalZamanId]) {
        programData[grupId][originalZamanId] = [];
    }
    programData[grupId][originalZamanId].push(movedItem);
};

const onDragEnd = () => {
    isDragging.value = false;
};

const onDragStart = () => {
    isDragging.value = true;
};

const toggleExportDropdown = () => {
    showExportDropdown.value = !showExportDropdown.value;
    showGrupDropdown.value = false;
};

const toggleGrupDropdown = () => {
    showGrupDropdown.value = !showGrupDropdown.value;
    showExportDropdown.value = false;
};

const selectGrup = (grup: string) => {
    selectedGrup.value = grup;
    showGrupDropdown.value = false;
};

const closeDropdown = (event: Event) => {
    const target = event.target as HTMLElement;
    const exportButton = document.getElementById('export-dropdown-button');
    const grupButton = document.getElementById('grup-dropdown-button');

    if (exportButton && !exportButton.contains(target)) {
        showExportDropdown.value = false;
    }
    if (grupButton && !grupButton.contains(target)) {
        showGrupDropdown.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', closeDropdown);
    initializeProgramData();
});

onUnmounted(() => {
    document.removeEventListener('click', closeDropdown);
});
</script>

<template>
    <Head title="Ders Programı" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold">Oluşturulan Ders Programı</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Genetik algoritma ile oluşturulmuş ders programı
                        <span class="ml-2 text-blue-600 dark:text-blue-400">(Dersleri sürükleyerek taşıyabilirsiniz)</span>
                    </p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <!-- Save Status (Loading Spinner) -->
                    <div v-if="isSaving" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-muted">
                        <svg class="animate-spin h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-sm">Kaydediliyor...</span>
                    </div>

                    <!-- Grup Dropdown -->
                    <div class="relative">
                        <button
                            id="grup-dropdown-button"
                            class="inline-flex items-center gap-2 rounded-lg border border-border bg-background px-4 py-2 text-sm font-medium hover:bg-muted"
                            @click="toggleGrupDropdown"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            {{ selectedGrup === 'all' ? 'Tüm Gruplar' : selectedGrup }}
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div v-show="showGrupDropdown" class="absolute right-0 z-10 mt-2 w-48 rounded-lg border border-border bg-background shadow-lg max-h-64 overflow-y-auto">
                            <div class="p-2">
                                <button
                                    @click="selectGrup('all')"
                                    class="w-full flex items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-muted text-left"
                                    :class="{ 'bg-primary/10 text-primary': selectedGrup === 'all' }"
                                >
                                    <svg v-if="selectedGrup === 'all'" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span :class="{ 'ml-6': selectedGrup !== 'all' }">Tüm Gruplar</span>
                                </button>
                                <button
                                    v-for="grup in availableGruplar"
                                    :key="grup.id"
                                    @click="selectGrup(grup.isim)"
                                    class="w-full flex items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-muted text-left"
                                    :class="{ 'bg-primary/10 text-primary': selectedGrup === grup.isim }"
                                >
                                    <svg v-if="selectedGrup === grup.isim" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span :class="{ 'ml-6': selectedGrup !== grup.isim }">{{ grup.isim }}</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Export Dropdown -->
                    <div class="relative">
                        <button
                            id="export-dropdown-button"
                            class="inline-flex items-center gap-2 rounded-lg border bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                            @click="toggleExportDropdown"
                        >
                            Export
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div v-show="showExportDropdown" class="absolute right-0 z-10 mt-2 w-64 rounded-lg border border-border bg-background shadow-lg">
                            <div class="p-2">
                                <div class="mb-2 px-2 py-1 text-xs font-semibold text-muted-foreground">Standart Format</div>
                                <a href="/program-olustur/export/excel" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-muted">Excel (Standart)</a>
                                <a href="/program-olustur/export/pdf" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-muted">PDF (Standart)</a>

                                <div class="mb-2 mt-4 px-2 py-1 text-xs font-semibold text-muted-foreground">Üniversite Şablonu</div>
                                <a href="/program-olustur/export/universite/excel/a" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-muted">Excel (A Şubesi)</a>
                                <a href="/program-olustur/export/universite/excel/b" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-muted">Excel (B Şubesi)</a>

                                <div class="mb-2 mt-4 px-2 py-1 text-xs font-semibold text-orange-600">🎯 Orijinal Şablon</div>
                                <a href="/program-olustur/export/template/a" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-muted bg-orange-50 dark:bg-orange-950/20">Template (A Şubesi)</a>
                                <a href="/program-olustur/export/template/b" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-muted bg-orange-50 dark:bg-orange-950/20">Template (B Şubesi)</a>
                            </div>
                        </div>
                    </div>

                    <a href="/program-olustur" class="inline-flex items-center gap-2 rounded-lg border border-border bg-background px-4 py-2 text-sm font-medium hover:bg-muted">
                        Geri Dön
                    </a>
                </div>
            </div>

            <!-- Program Tabloları -->
            <div v-if="isInitialized" class="space-y-8">
                <div v-for="(item, grupId) in filteredProgramTablosu" :key="grupId" class="rounded-lg border bg-card">
                    <div class="border-b bg-muted/50 px-6 py-4">
                        <h2 class="text-lg font-semibold">{{ item.grup.isim }}</h2>
                        <p class="text-sm text-muted-foreground">{{ item.grup.yil }}. Yıl</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b bg-muted/30">
                                    <th class="px-4 py-3 text-left text-sm font-medium w-24">Gün / Saat</th>
                                    <th v-for="zamanDilim in zaman_dilimleri" :key="zamanDilim.id" class="border-l px-4 py-3 text-center text-sm font-medium min-w-[140px]">
                                        <div>{{ gunler[zamanDilim.gun_sirasi] }}</div>
                                        <div class="text-xs font-normal text-muted-foreground">{{ zamanDilim.baslangic_saat }} - {{ zamanDilim.bitis_saat }}</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-4 py-2 text-sm font-medium">Dersler</td>
                                    <td v-for="zamanDilim in zaman_dilimleri" :key="zamanDilim.id" class="border-l px-1 py-1">
                                        <draggable
                                            v-if="programData[Number(grupId)] && programData[Number(grupId)][zamanDilim.id]"
                                            v-model="programData[Number(grupId)][zamanDilim.id]"
                                            :group="{ name: 'grup-' + grupId, pull: true, put: true }"
                                            item-key="id"
                                            class="min-h-[80px] rounded-md transition-colors"
                                            :class="{ 'bg-blue-50 dark:bg-blue-950/20 border-2 border-dashed border-blue-300': isDragging }"
                                            ghost-class="opacity-50"
                                            @start="onDragStart"
                                            @end="onDragEnd"
                                            @change="(evt: any) => onDragChange(evt, Number(grupId), zamanDilim.id)"
                                        >
                                            <template #item="{ element }">
                                                <div class="rounded-md bg-primary/10 px-3 py-2 text-center cursor-move hover:bg-primary/20 transition-all hover:shadow-md">
                                                    <div class="flex items-center justify-center gap-1 mb-1">
                                                        <svg class="h-3 w-3 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-sm font-semibold text-primary">{{ element.ders.isim }}</div>
                                                    <div class="mt-1 text-xs text-muted-foreground">{{ element.ogretmen.isim }}</div>
                                                    <div class="mt-1 text-xs text-muted-foreground">{{ element.mekan.isim }}</div>
                                                </div>
                                            </template>
                                        </draggable>

                                        <div v-if="!programData[Number(grupId)]?.[zamanDilim.id]?.length && !isDragging" class="min-h-[80px] flex items-center justify-center text-xs text-muted-foreground">-</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="!isInitialized && Object.keys(program_tablosu).length > 0" class="flex items-center justify-center py-12">
                <svg class="animate-spin h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="ml-2 text-muted-foreground">Yükleniyor...</span>
            </div>

            <!-- Empty State -->
            <div v-if="Object.keys(filteredProgramTablosu).length === 0" class="flex flex-col items-center justify-center rounded-lg border border-dashed py-12">
                <h3 class="mt-4 font-semibold">{{ selectedGrup === 'all' ? 'Program boş' : selectedGrup + ' grubunda program yok' }}</h3>
                <p class="mt-2 text-center text-sm text-muted-foreground">{{ selectedGrup === 'all' ? 'Henüz oluşturulmuş bir program yok' : 'Bu grup için henüz ders programı oluşturulmamış' }}</p>
            </div>

            <!-- Info Box -->
            <div v-if="isInitialized" class="mt-6 rounded-lg border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-950/20 p-4">
                <div class="flex items-start gap-3">
                    <svg class="h-5 w-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h4 class="font-medium text-blue-900 dark:text-blue-100">Sürükle & Bırak</h4>
                        <p class="mt-1 text-sm text-blue-700 dark:text-blue-300">
                            Dersleri sürükleyerek farklı zaman dilimlerine taşıyabilirsiniz. Aynı grup içindeki dersler yer değiştirebilir.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.sortable-ghost { opacity: 0.5; background: #c8ebfb; }
</style>
