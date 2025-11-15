<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

interface Ogretmen {
    id: number;
    isim: string;
    unvan: string;
    email: string;
}

interface Ders {
    id: number;
    ders_kodu: string;
    isim: string;
    haftalik_saat: number;
}

const props = defineProps<{
    ogretmen: Ogretmen;
    dersler: Ders[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Anasayfa',
        href: dashboard().url,
    },
    {
        title: 'Öğretmen Dersleri',
        href: '/ogretmen-dersleri',
    },
    {
        title: props.ogretmen.isim,
        href: `/ogretmen-dersleri/${props.ogretmen.id}`,
    },
];
</script>

<template>
    <Head :title="`${ogretmen.isim} - Verdiği Dersler`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">{{ ogretmen.isim }}</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        {{ ogretmen.unvan }} - Verdiği Dersler
                    </p>
                </div>
                <Link
                    :href="`/ogretmen-dersleri/${ogretmen.id}/edit`"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Dersleri Düzenle
                </Link>
            </div>

            <!-- Dersler Listesi -->
            <div v-if="dersler.length > 0" class="overflow-hidden rounded-lg border bg-card">
                <table class="w-full">
                    <thead>
                        <tr class="border-b bg-muted/50">
                            <th class="px-6 py-3 text-left text-sm font-medium">Ders Kodu</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Ders Adı</th>
                            <th class="px-6 py-3 text-center text-sm font-medium">Haftalık Saat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr
                            v-for="ders in dersler"
                            :key="ders.id"
                            class="hover:bg-muted/50"
                        >
                            <td class="px-6 py-4 font-mono text-sm font-medium">
                                {{ ders.ders_kodu }}
                            </td>
                            <td class="px-6 py-4">
                                {{ ders.isim }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center rounded-md bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary">
                                    {{ ders.haftalik_saat }} saat
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div v-else class="flex flex-col items-center justify-center rounded-lg border border-dashed py-12">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-muted">
                    <svg class="h-6 w-6 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="mt-4 font-semibold">Bu öğretmen henüz hiç ders vermiyor</h3>
                <p class="mt-2 text-center text-sm text-muted-foreground">
                    Bu öğretmene ders eklemek için düzenle butonuna tıklayın
                </p>
                <Link
                    :href="`/ogretmen-dersleri/${ogretmen.id}/edit`"
                    class="mt-4 inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Ders Ekle
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
