<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

interface ZamanDilimi {
    id: number;
    haftanin_gunu: string;
    baslangic_saati: string;
    bitis_saati: string;
}

defineProps<{
    zaman_dilimleri: ZamanDilimi[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Anasayfa',
        href: dashboard().url,
    },
    {
        title: 'Zaman Dilimleri',
        href: '/zaman-dilimleri',
    },
];

const formatTime = (time: string) => {
    return time.substring(0, 5);
};
</script>

<template>
    <Head title="Zaman Dilimleri" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Zaman Dilimleri</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Tüm zaman dilimlerini görüntüleyin ve yönetin
                    </p>
                </div>
                <Link
                    href="/zaman-dilimleri/create"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Yeni Zaman Dilimi Ekle
                </Link>
            </div>

            <!-- Table -->
            <div v-if="zaman_dilimleri.length > 0" class="overflow-hidden rounded-lg border bg-card">
                <table class="w-full">
                    <thead>
                        <tr class="border-b bg-muted/50">
                            <th class="px-6 py-3 text-left text-sm font-medium">Gün</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Başlangıç Saati</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Bitiş Saati</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Süre</th>
                            <th class="px-6 py-3 text-right text-sm font-medium">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr
                            v-for="dilim in zaman_dilimleri"
                            :key="dilim.id"
                            class="hover:bg-muted/50"
                        >
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-md bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary capitalize">
                                    {{ dilim.haftanin_gunu.charAt(0).toUpperCase() + dilim.haftanin_gunu.slice(1) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium">
                                {{ formatTime(dilim.baslangic_saati) }}
                            </td>
                            <td class="px-6 py-4 font-medium">
                                {{ formatTime(dilim.bitis_saati) }}
                            </td>
                            <td class="px-6 py-4 text-muted-foreground">
                                {{
                                    Math.floor(
                                        (new Date(`2000-01-01 ${dilim.bitis_saati}`).getTime() -
                                         new Date(`2000-01-01 ${dilim.baslangic_saati}`).getTime()) /
                                        (1000 * 60)
                                    )
                                }} dakika
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <Link
                                        :href="`/zaman-dilimleri/${dilim.id}`"
                                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium hover:bg-accent"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Görüntüle
                                    </Link>
                                    <Link
                                        :href="`/zaman-dilimleri/${dilim.id}/edit`"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="mt-4 font-semibold">Henüz zaman dilimi yok</h3>
                <p class="mt-2 text-center text-sm text-muted-foreground">
                    Başlamak için ilk zaman diliminizi oluşturun
                </p>
                <Link
                    href="/zaman-dilimleri/create"
                    class="mt-4 inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    İlk Zaman Dilimini Oluştur
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
