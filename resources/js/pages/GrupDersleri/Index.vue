<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

interface OgrenciGrubu {
    id: number;
    isim: string;
    yil: number;
}

interface Ders {
    id: number;
    ders_kodu: string;
    isim: string;
}

interface GrupDers {
    ogrenci_grup_id: number;
    ders_id: number;
    ogrenci_grubu: OgrenciGrubu;
    ders: Ders;
}

defineProps<{
    grup_dersleri: GrupDers[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Anasayfa',
        href: dashboard().url,
    },
    {
        title: 'Grup Dersleri',
        href: '/grup-dersleri',
    },
];

const deleteForm = useForm({});

const destroy = (grupId: number, dersId: number) => {
  if (confirm('Bu grup dersini silmek istediğinizden emin misiniz?')) {
    deleteForm.delete(`/grup-dersleri/${grupId}/${dersId}`);
  }
};
</script>

<template>
    <Head title="Grup Dersleri" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Grup Dersleri</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Öğrenci gruplarının aldığı dersleri görüntüleyin ve yönetin
                    </p>
                </div>
                <Link
                    href="/grup-dersleri/create"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Yeni Grup Dersi Ekle
                </Link>
            </div>

            <!-- Table -->
            <div v-if="grup_dersleri.length > 0" class="overflow-hidden rounded-lg border bg-card">
                <table class="w-full">
                    <thead>
                        <tr class="border-b bg-muted/50">
                            <th class="px-6 py-3 text-left text-sm font-medium">Öğrenci Grubu</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Ders</th>
                            <th class="px-6 py-3 text-right text-sm font-medium">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr
                            v-for="grupDers in grup_dersleri"
                            :key="`${grupDers.ogrenci_grup_id}-${grupDers.ders_id}`"
                            class="hover:bg-muted/50"
                        >
                            <td class="px-6 py-4 font-medium">
                                <div>{{ grupDers.ogrenci_grubu.isim }}</div>
                                <div class="text-xs text-muted-foreground">{{ grupDers.ogrenci_grubu.yil }}. Yıl</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ grupDers.ders.isim }}</div>
                                <div class="text-xs text-muted-foreground">{{ grupDers.ders.ders_kodu }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        @click="destroy(grupDers.ogrenci_grup_id, grupDers.ders_id)"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="mt-4 font-semibold">Henüz grup dersi yok</h3>
                <p class="mt-2 text-center text-sm text-muted-foreground">
                    Başlamak için ilk grup dersini oluşturun
                </p>
                <Link
                    href="/grup-dersleri/create"
                    class="mt-4 inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    İlk Grup Dersini Oluştur
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
