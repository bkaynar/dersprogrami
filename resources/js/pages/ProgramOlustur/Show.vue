<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

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
    zaman_dilim_id: number;
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
