<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

interface Ders {
    id: number;
    ders_kodu: string;
    isim: string;
}

interface Gereksinim {
    id: number;
    ders_id: number;
    ders: Ders;
    mekan_tipi: string;
    gereksinim_tipi: string;
}

defineProps<{
    gereksinimler: Gereksinim[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Anasayfa',
        href: dashboard().url,
    },
    {
        title: 'Ders Mekan Gereksinimleri',
        href: '/ders-mekan-gereksinimleri',
    },
];

const mekanTipiLabel = (tip: string) => {
    const labels: Record<string, string> = {
        'derslik': 'Derslik',
        'laboratuvar': 'Laboratuvar',
        'konferans_salonu': 'Konferans Salonu',
    };
    return labels[tip] || tip;
};

const gereksinimTipiLabel = (tip: string) => {
    const labels: Record<string, string> = {
        'zorunlu': 'Zorunlu',
        'olabilir': 'Olabilir',
    };
    return labels[tip] || tip;
};
</script>

<template>
    <Head title="Ders Mekan Gereksinimleri" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Ders Mekan Gereksinimleri</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Derslerin mekan gereksinimlerini görüntüleyin ve yönetin
                    </p>
                </div>
                <Link
                    href="/ders-mekan-gereksinimleri/create"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Yeni Gereksinim Ekle
                </Link>
            </div>

            <!-- Table -->
            <div v-if="gereksinimler.length > 0" class="overflow-hidden rounded-lg border bg-card">
                <table class="w-full">
                    <thead>
                        <tr class="border-b bg-muted/50">
                            <th class="px-6 py-3 text-left text-sm font-medium">Ders</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Mekan Tipi</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Gereksinim Tipi</th>
                            <th class="px-6 py-3 text-right text-sm font-medium">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr
                            v-for="gereksinim in gereksinimler"
                            :key="gereksinim.id"
                            class="hover:bg-muted/50"
                        >
                            <td class="px-6 py-4 font-medium">
                                <div>{{ gereksinim.ders.isim }}</div>
                                <div class="text-xs text-muted-foreground">{{ gereksinim.ders.ders_kodu }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-md bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary">
                                    {{ mekanTipiLabel(gereksinim.mekan_tipi) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-medium"
                                    :class="gereksinim.gereksinim_tipi === 'zorunlu' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'"
                                >
                                    {{ gereksinimTipiLabel(gereksinim.gereksinim_tipi) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <Link
                                        :href="`/ders-mekan-gereksinimleri/${gereksinim.id}`"
                                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium hover:bg-accent"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Görüntüle
                                    </Link>
                                    <Link
                                        :href="`/ders-mekan-gereksinimleri/${gereksinim.id}/edit`"
                                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium hover:bg-accent"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Düzenle
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div v-else class="flex flex-col items-center justify-center rounded-lg border border-dashed py-12">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-muted">
                    <svg class="h-6 w-6 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="mt-4 font-semibold">Henüz gereksinim yok</h3>
                <p class="mt-2 text-center text-sm text-muted-foreground">
                    Başlamak için ilk mekan gereksinimini oluşturun
                </p>
                <Link
                    href="/ders-mekan-gereksinimleri/create"
                    class="mt-4 inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    İlk Gereksinimi Oluştur
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
