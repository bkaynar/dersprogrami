<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

interface Ogretmen {
    id: number;
    isim: string;
    unvan: string;
}

interface Ders {
    id: number;
    ders_kodu: string;
    isim: string;
}

interface OgretmenDers {
    ogretmen_id: number;
    ders_id: number;
    ogretmen: Ogretmen;
    ders: Ders;
}

defineProps<{
    ogretmen_dersleri: OgretmenDers[];
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
];

const deleteForm = useForm({});

const destroy = (ogretmenId: number, dersId: number) => {
  if (confirm('Bu öğretmen dersini silmek istediğinizden emin misiniz?')) {
    deleteForm.delete(`/ogretmen-dersleri/${ogretmenId}/${dersId}`);
  }
};
</script>

<template>
    <Head title="Öğretmen Dersleri" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Öğretmen Dersleri</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Öğretmenlerin verdiği dersleri görüntüleyin ve yönetin
                    </p>
                </div>
                <Link
                    href="/ogretmen-dersleri/create"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Yeni Öğretmen Dersi Ekle
                </Link>
            </div>

            <!-- Table -->
            <div v-if="ogretmen_dersleri.length > 0" class="overflow-hidden rounded-lg border bg-card">
                <table class="w-full">
                    <thead>
                        <tr class="border-b bg-muted/50">
                            <th class="px-6 py-3 text-left text-sm font-medium">Öğretmen</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Ders</th>
                            <th class="px-6 py-3 text-right text-sm font-medium">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr
                            v-for="ogretmenDers in ogretmen_dersleri"
                            :key="`${ogretmenDers.ogretmen_id}-${ogretmenDers.ders_id}`"
                            class="hover:bg-muted/50"
                        >
                            <td class="px-6 py-4 font-medium">
                                <div>{{ ogretmenDers.ogretmen.isim }}</div>
                                <div class="text-xs text-muted-foreground">{{ ogretmenDers.ogretmen.unvan }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ ogretmenDers.ders.isim }}</div>
                                <div class="text-xs text-muted-foreground">{{ ogretmenDers.ders.ders_kodu }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        @click="destroy(ogretmenDers.ogretmen_id, ogretmenDers.ders_id)"
                                        :disabled="deleteForm.processing"
                                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium text-destructive hover:bg-destructive/10 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Sil
                                    </button>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="mt-4 font-semibold">Henüz öğretmen dersi yok</h3>
                <p class="mt-2 text-center text-sm text-muted-foreground">
                    Başlamak için ilk öğretmen dersini oluşturun
                </p>
                <Link
                    href="/ogretmen-dersleri/create"
                    class="mt-4 inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    İlk Öğretmen Dersini Oluştur
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
