<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

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
    {
        title: 'Anasayfa',
        href: dashboard().url,
    },
    {
        title: 'Program Oluştur',
        href: '/program-olustur',
    },
    {
        title: 'Programı Görüntüle',
        href: '/program-olustur/show',
    },
];

const gunler = ['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi', 'Pazar'];

// Dropdown state
const showExportDropdown = ref(false);

const toggleExportDropdown = () => {
    showExportDropdown.value = !showExportDropdown.value;
};

const closeDropdown = (event: Event) => {
    const target = event.target as HTMLElement;
    const button = document.getElementById('export-dropdown-button');

    if (button && !button.contains(target)) {
        showExportDropdown.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', closeDropdown);
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
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Oluşturulan Ders Programı</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Genetik algoritma ile oluşturulmuş ders programı
                    </p>
                </div>
                <div class="flex gap-2">
                    <!-- Export Butonları -->
                    <div class="relative">
                        <button
                            id="export-dropdown-button"
                            class="inline-flex items-center gap-2 rounded-lg border bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                            @click="toggleExportDropdown"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div
                            v-show="showExportDropdown"
                            class="absolute right-0 z-10 mt-2 w-64 rounded-lg border bg-white shadow-lg"
                        >
                            <div class="p-2">
                                <!-- Standart Export -->
                                <div class="mb-2 px-2 py-1 text-xs font-semibold text-muted-foreground">
                                    Standart Format
                                </div>
                                <a
                                    href="/program-olustur/export/excel"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-muted"
                                >
                                    <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Excel (Standart)
                                </a>
                                <a
                                    href="/program-olustur/export/pdf"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-muted"
                                >
                                    <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    PDF (Standart)
                                </a>

                                <!-- Üniversite Resmi Şablonu -->
                                <div class="mb-2 mt-4 px-2 py-1 text-xs font-semibold text-muted-foreground">
                                    Üniversite Resmi Şablonu
                                </div>

                                <!-- A Şubesi -->
                                <div class="mb-1 px-2 py-1 text-xs font-medium text-blue-600">
                                    A Şubesi (1-A, 2-A, 3-A, 4-A)
                                </div>
                                <a
                                    href="/program-olustur/export/universite/excel/a"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-muted"
                                >
                                    <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Excel (A Şubesi)
                                </a>
                                <a
                                    href="/program-olustur/export/universite/pdf/a"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-muted"
                                >
                                    <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    PDF (A Şubesi)
                                </a>

                                <!-- B Şubesi -->
                                <div class="mb-1 mt-2 px-2 py-1 text-xs font-medium text-purple-600">
                                    B Şubesi (1-B, 2-B, 3-B, 4-B)
                                </div>
                                <a
                                    href="/program-olustur/export/universite/excel/b"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-muted"
                                >
                                    <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Excel (B Şubesi)
                                </a>
                                <a
                                    href="/program-olustur/export/universite/pdf/b"
                                    class="flex items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-muted"
                                >
                                    <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    PDF (B Şubesi)
                                </a>
                            </div>
                        </div>
                    </div>

                    <a
                        href="/program-olustur"
                        class="inline-flex items-center gap-2 rounded-lg border bg-background px-4 py-2 text-sm font-medium hover:bg-muted"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Geri Dön
                    </a>
                </div>
            </div>

            <!-- Program Tabloları - Her grup için ayrı -->
            <div class="space-y-8">
                <div
                    v-for="(item, grupId) in program_tablosu"
                    :key="grupId"
                    class="rounded-lg border bg-card"
                >
                    <!-- Grup Başlığı -->
                    <div class="border-b bg-muted/50 px-6 py-4">
                        <h2 class="text-lg font-semibold">{{ item.grup.isim }}</h2>
                        <p class="text-sm text-muted-foreground">{{ item.grup.yil }}. Yıl</p>
                    </div>

                    <!-- Tablo -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b bg-muted/30">
                                    <th class="px-4 py-3 text-left text-sm font-medium">Gün / Saat</th>
                                    <th
                                        v-for="zamanDilim in zaman_dilimleri"
                                        :key="zamanDilim.id"
                                        class="border-l px-4 py-3 text-center text-sm font-medium"
                                    >
                                        <div>{{ gunler[zamanDilim.gun_sirasi] }}</div>
                                        <div class="text-xs font-normal text-muted-foreground">
                                            {{ zamanDilim.baslangic_saat }} - {{ zamanDilim.bitis_saat }}
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-4 py-2 text-sm font-medium">Dersler</td>
                                    <td
                                        v-for="zamanDilim in zaman_dilimleri"
                                        :key="zamanDilim.id"
                                        class="border-l px-2 py-2"
                                    >
                                        <div
                                            v-if="item.dersler[zamanDilim.id]"
                                            class="rounded-md bg-primary/10 px-3 py-2 text-center"
                                        >
                                            <div class="text-sm font-semibold text-primary">
                                                {{ item.dersler[zamanDilim.id]!.ders.isim }}
                                            </div>
                                            <div class="mt-1 text-xs text-muted-foreground">
                                                {{ item.dersler[zamanDilim.id]!.ogretmen.isim }}
                                            </div>
                                            <div class="mt-1 text-xs text-muted-foreground">
                                                {{ item.dersler[zamanDilim.id]!.mekan.isim }}
                                            </div>
                                        </div>
                                        <div v-else class="text-center text-xs text-muted-foreground">
                                            -
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="Object.keys(program_tablosu).length === 0" class="flex flex-col items-center justify-center rounded-lg border border-dashed py-12">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-muted">
                    <svg class="h-6 w-6 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="mt-4 font-semibold">Program boş</h3>
                <p class="mt-2 text-center text-sm text-muted-foreground">
                    Henüz oluşturulmuş bir program yok
                </p>
            </div>
        </div>
    </AppLayout>
</template>
