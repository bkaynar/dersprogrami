<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface ZamanDilimi {
    id: number;
    haftanin_gunu: string;
    baslangic_saati: string;
    bitis_saati: string;
}

defineProps<{
    zaman_dilimleri: ZamanDilimi[];
}>();

// (flash variables already declared above) remove duplicate declarations

// Import form
const fileInput = ref<HTMLInputElement | null>(null);
const importForm = useForm({
    file: null as File | null,
});

const downloadTemplate = () => {
    window.location.href = '/zaman-dilimleri/template/download';
};

const selectFile = () => {
    fileInput.value?.click();
};

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        importForm.file = target.files[0];
        uploadFile();
    }
};

const uploadFile = () => {
    if (!importForm.file) return;

    importForm.post('/zaman-dilimleri/import', {
        forceFormData: true,
        onSuccess: () => {
            importForm.reset();
            if (fileInput.value) {
                fileInput.value.value = '';
            }
        },
        onError: () => {
            importForm.reset();
            if (fileInput.value) {
                fileInput.value.value = '';
            }
        },
    });
};

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

// Flash messages
const page = usePage<any>();
const flashSuccess = computed(() => page.props.flash?.success || page.props.success);
const flashError = computed(() => page.props.flash?.error || page.props.error);
</script>

<template>
    <Head title="Zaman Dilimleri" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Flash Messages -->
            <div v-if="flashSuccess || flashError" class="mb-6 space-y-4">
                <div v-if="flashSuccess" class="border-green-200 bg-green-50 text-green-800 rounded-lg p-4">
                    <strong>Başarılı!</strong>
                    <p class="mt-1 text-sm">{{ flashSuccess }}</p>
                </div>

                <div v-if="flashError" class="border-red-200 bg-red-50 text-red-800 rounded-lg p-4">
                    <strong>Hata!</strong>
                    <p class="mt-1 text-sm">{{ flashError }}</p>
                </div>
            </div>

            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Zaman Dilimleri</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Tüm zaman dilimlerini görüntüleyin ve yönetin
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button
                        @click="downloadTemplate"
                        class="inline-flex items-center gap-2 rounded-lg border bg-background px-4 py-2 text-sm font-medium hover:bg-muted"
                        title="Excel şablonunu indir"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Şablon İndir
                    </button>
                    <button
                        @click="selectFile"
                        :disabled="importForm.processing"
                        class="inline-flex items-center gap-2 rounded-lg border bg-background px-4 py-2 text-sm font-medium hover:bg-muted disabled:opacity-50"
                        title="Excel dosyası yükle"
                    >
                        <svg v-if="importForm.processing" class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        {{ importForm.processing ? 'Yükleniyor...' : 'Excel Yükle' }}
                    </button>
                    <input
                        ref="fileInput"
                        type="file"
                        accept=".xlsx,.xls"
                        class="hidden"
                        @change="handleFileChange"
                    />

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
