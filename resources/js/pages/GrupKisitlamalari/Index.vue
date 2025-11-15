<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

interface OgrenciGrubu {
    id: number;
    isim: string;
    yil: number;
}

interface ZamanDilimi {
    id: number;
    haftanin_gunu: number;
    baslangic_saati: string;
    bitis_saati: string;
}

interface GrupKisitlama {
    id: number;
    ogrenci_grup_id: number;
    zaman_dilimi_id: number;
    musait_mi: boolean;
    ogrenci_grubu: OgrenciGrubu;
    zaman_dilimi: ZamanDilimi;
}

defineProps<{
    kisitlamalar: GrupKisitlama[];
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
];

const gunIsmi = (gun: number) => {
    const gunler = ['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi', 'Pazar'];
    return gunler[gun - 1] || gun.toString();
};

const formatSaat = (saat: string) => {
    return saat.substring(0, 5);
};
</script>

<template>
    <Head title="Grup Kısıtlamaları" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Grup Kısıtlamaları</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Öğrenci gruplarının zaman kısıtlamalarını görüntüleyin ve yönetin
                    </p>
                </div>
                <Link
                    href="/grup-kisitlamalari/create"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Yeni Kısıtlama Ekle
                </Link>
            </div>

            <!-- Table -->
            <div v-if="kisitlamalar.length > 0" class="overflow-hidden rounded-lg border bg-card">
                <table class="w-full">
                    <thead>
                        <tr class="border-b bg-muted/50">
                            <th class="px-6 py-3 text-left text-sm font-medium">Öğrenci Grubu</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Zaman Dilimi</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Durum</th>
                            <th class="px-6 py-3 text-right text-sm font-medium">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr
                            v-for="kisitlama in kisitlamalar"
                            :key="kisitlama.id"
                            class="hover:bg-muted/50"
                        >
                            <td class="px-6 py-4 font-medium">
                                <div>{{ kisitlama.ogrenci_grubu.isim }}</div>
                                <div class="text-xs text-muted-foreground">{{ kisitlama.ogrenci_grubu.yil }}. Yıl</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ gunIsmi(kisitlama.zaman_dilimi.haftanin_gunu) }}</div>
                                <div class="text-xs text-muted-foreground">
                                    {{ formatSaat(kisitlama.zaman_dilimi.baslangic_saati) }} -
                                    {{ formatSaat(kisitlama.zaman_dilimi.bitis_saati) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-medium"
                                    :class="kisitlama.musait_mi ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                >
                                    {{ kisitlama.musait_mi ? 'Müsait' : 'Müsait Değil' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <Link
                                        :href="`/grup-kisitlamalari/${kisitlama.id}`"
                                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium hover:bg-accent"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Görüntüle
                                    </Link>
                                    <Link
                                        :href="`/grup-kisitlamalari/${kisitlama.id}/edit`"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
                <h3 class="mt-4 font-semibold">Henüz kısıtlama yok</h3>
                <p class="mt-2 text-center text-sm text-muted-foreground">
                    Başlamak için ilk grup kısıtlamasını oluşturun
                </p>
                <Link
                    href="/grup-kisitlamalari/create"
                    class="mt-4 inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    İlk Kısıtlamayı Oluştur
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
