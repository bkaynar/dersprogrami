<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';

interface Ogretmen {
    id: number;
    isim: string;
    unvan: string;
    email: string;
    musaitlikler_count: number;
    musait_saat_sayisi: number;
    gerekli_ders_saati: number;
}

defineProps<{
    ogretmenler: Ogretmen[];
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
];

const handleFileUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];

    if (file) {
        // Dosya tipine göre hangi import endpoint'ini kullanacağımızı belirle
        // Şimdilik gelişmiş import kullan
        const formData = new FormData();
        formData.append('file', file);

        router.post('/ogretmen-musaitlik/import-advanced', formData, {
            forceFormData: true,
            onSuccess: () => {
                target.value = ''; // Input'u temizle
            },
            onError: () => {
                target.value = ''; // Input'u temizle
            }
        });
    }
};
</script>

<template>
    <Head title="Öğretmen Müsaitlik" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Öğretmen Müsaitlik</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Öğretmenlerin müsaitlik durumlarını görüntüleyin ve yönetin
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Excel İşlemleri -->
                    <div class="flex items-center gap-2">
                        <!-- Şablon İndir Dropdown -->
                        <div class="relative group">
                            <button class="inline-flex items-center gap-2 rounded-lg border bg-card px-4 py-2 text-sm font-medium hover:bg-accent">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Şablon İndir
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-64 rounded-lg border bg-card shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                                <div class="p-2">
                                    <a
                                        href="/ogretmen-musaitlik/template"
                                        class="flex items-center gap-3 rounded-md px-3 py-2 text-sm hover:bg-accent"
                                    >
                                        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <div>
                                            <div class="font-medium">Basit Şablon</div>
                                            <div class="text-xs text-muted-foreground">Tek sayfa, tüm öğretmenler</div>
                                        </div>
                                    </a>
                                    <a
                                        href="/ogretmen-musaitlik/template-advanced"
                                        class="flex items-center gap-3 rounded-md px-3 py-2 text-sm hover:bg-accent"
                                    >
                                        <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <div>
                                            <div class="font-medium">Detaylı Şablon ⭐</div>
                                            <div class="text-xs text-muted-foreground">Her öğretmen için ayrı tab</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Import Butonu -->
                        <label class="inline-flex items-center gap-2 rounded-lg border bg-card px-4 py-2 text-sm font-medium hover:bg-accent cursor-pointer">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                            </svg>
                            Excel Yükle
                            <input type="file" class="hidden" accept=".xlsx,.xls" @change="handleFileUpload" />
                        </label>
                    </div>

                    <!-- Yeni Müsaitlik Ekle -->
                    <Link
                        href="/ogretmen-musaitlik/create"
                        class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Yeni Müsaitlik Ekle
                    </Link>
                </div>
            </div>

            <!-- Öğretmen Listesi -->
            <div v-if="ogretmenler.length > 0" class="overflow-hidden rounded-lg border bg-card">
                <table class="w-full">
                    <thead>
                        <tr class="border-b bg-muted/50">
                            <th class="px-6 py-3 text-left text-sm font-medium">Öğretmen</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">E-posta</th>
                            <th class="px-6 py-3 text-center text-sm font-medium">Ders Saati</th>
                            <th class="px-6 py-3 text-center text-sm font-medium">Müsaitlik</th>
                            <th class="px-6 py-3 text-right text-sm font-medium">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr
                            v-for="ogretmen in ogretmenler"
                            :key="ogretmen.id"
                            class="hover:bg-muted/50"
                        >
                            <td class="px-6 py-4 font-medium">
                                <div>{{ ogretmen.isim }}</div>
                                <div class="text-xs text-muted-foreground">{{ ogretmen.unvan }}</div>
                            </td>
                            <td class="px-6 py-4 text-muted-foreground">
                                {{ ogretmen.email }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-sm font-medium">{{ ogretmen.gerekli_ders_saati }} saat</span>
                                    <span class="text-xs text-muted-foreground">gerekli</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span
                                        :class="[
                                            'inline-flex items-center rounded-md px-2.5 py-1 text-xs font-medium',
                                            ogretmen.musait_saat_sayisi >= ogretmen.gerekli_ders_saati
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-red-100 text-red-800'
                                        ]"
                                    >
                                        {{ ogretmen.musait_saat_sayisi }}/{{ ogretmen.musaitlikler_count }} müsait
                                    </span>
                                    <span
                                        v-if="ogretmen.gerekli_ders_saati > 0"
                                        :class="[
                                            'text-xs',
                                            ogretmen.musait_saat_sayisi >= ogretmen.gerekli_ders_saati
                                                ? 'text-green-600'
                                                : 'text-red-600'
                                        ]"
                                    >
                                        {{ ogretmen.musait_saat_sayisi >= ogretmen.gerekli_ders_saati ? '✓ Yeterli' : `${ogretmen.gerekli_ders_saati - ogretmen.musait_saat_sayisi} eksik` }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <Link
                                        :href="`/ogretmen-musaitlik/${ogretmen.id}`"
                                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium hover:bg-accent"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Görüntüle
                                    </Link>
                                    <Link
                                        :href="`/ogretmen-musaitlik/${ogretmen.id}/edit`"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h3 class="mt-4 font-semibold">Henüz öğretmen yok</h3>
                <p class="mt-2 text-center text-sm text-muted-foreground">
                    Önce öğretmen eklemeniz gerekiyor
                </p>
                <Link
                    href="/ogretmenler/create"
                    class="mt-4 inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Öğretmen Ekle
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
