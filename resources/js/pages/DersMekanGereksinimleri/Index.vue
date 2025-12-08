<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface DersMekanGereksinimi {
    id: number;
    mekan_tipi: string;
    gereksinim_tipi: string;
}

interface Ders {
    id: number;
    ders_kodu: string;
    isim: string;
    mekan_gereksinimi: DersMekanGereksinimi | null;
}

defineProps<{
    dersler: Ders[];
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

// Import form
const page = usePage<any>();
const flashSuccess = computed(() => page.props.flash?.success || page.props.success);
const flashError = computed(() => page.props.flash?.error || page.props.error);
const flashWarning = computed(() => page.props.flash?.warning || page.props.warning);
const flashImportErrors = computed(() => page.props.flash?.import_errors || page.props.import_errors);
const fileInput = ref<HTMLInputElement | null>(null);
const importForm = useForm({ file: null as File | null, create_missing: false });

const downloadTemplate = () => {
    window.location.href = '/ders-mekan-gereksinimleri/template/download';
};

const downloadExcel = () => {
    window.location.href = '/ders-mekan-gereksinimleri/export';
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

    importForm.post('/ders-mekan-gereksinimleri/import', {
        forceFormData: true,
        onSuccess: () => {
            importForm.reset();
            if (fileInput.value) fileInput.value.value = '';
        },
        onError: () => {
            importForm.reset();
            if (fileInput.value) fileInput.value.value = '';
        },
    });
};

const mekanTipiLabel = (tip: string | undefined) => {
    if (!tip) return '-';
    const labels: Record<string, string> = {
        'derslik': 'Derslik',
        'laboratuvar': 'Laboratuvar',
        'konferans_salonu': 'Konferans Salonu',
    };
    return labels[tip] || tip;
};

const gereksinimTipiLabel = (tip: string | undefined) => {
    if (!tip) return '-';
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
            <!-- Flash Messages -->
            <div v-if="flashSuccess || flashError || flashWarning" class="mb-6 space-y-4">
                <div v-if="flashWarning" class="border-yellow-200 bg-yellow-50 text-yellow-800 rounded-lg p-4">
                    <strong>Uyarı!</strong>
                    <p class="mt-1 text-sm">{{ flashWarning }}</p>
                    <ul v-if="flashImportErrors && flashImportErrors.length > 0" class="mt-2 list-inside list-disc text-sm">
                        <li v-for="(error, index) in flashImportErrors" :key="index">{{ error }}</li>
                    </ul>
                </div>
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
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Ders Mekan Gereksinimleri</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Dersler için gerekli mekan tiplerini yönetin
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <label class="inline-flex items-center gap-2 rounded-lg border bg-background px-3 py-2 text-sm hover:bg-muted cursor-pointer">
                        <input type="checkbox" v-model="importForm.create_missing" class="form-checkbox h-4 w-4 text-primary rounded border-gray-300" />
                        <span>Eksik dersleri oluştur</span>
                    </label>
                    
                    <button
                        @click="downloadTemplate"
                        class="inline-flex items-center gap-2 rounded-lg border bg-background px-4 py-2 text-sm font-medium hover:bg-muted"
                        title="Excel şablonunu indir"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Şablon
                    </button>

                    <button
                        @click="downloadExcel"
                        class="inline-flex items-center gap-2 rounded-lg border bg-background px-4 py-2 text-sm font-medium hover:bg-muted"
                        title="Listeyi Excel olarak indir"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Excel İndir
                    </button>
                    
                    <button
                        @click="selectFile"
                        :disabled="importForm.processing"
                        class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50"
                        title="Excel dosyası yükle"
                    >
                        <svg v-if="importForm.processing" class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Excel Yükle
                    </button>
                    <input
                        ref="fileInput"
                        type="file"
                        accept=".xlsx,.xls"
                        class="hidden"
                        @change="handleFileChange"
                    />
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-hidden rounded-lg border bg-card">
                <table class="w-full">
                    <thead>
                        <tr class="border-b bg-muted/50">
                            <th class="px-6 py-3 text-left text-sm font-medium">Ders Kodu</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Ders Adı</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Mekan Tipi</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Gereksinim</th>
                            <th class="px-6 py-3 text-right text-sm font-medium">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr
                            v-for="ders in dersler"
                            :key="ders.id"
                            class="hover:bg-muted/50"
                        >
                            <td class="px-6 py-4 font-medium text-muted-foreground">{{ ders.ders_kodu }}</td>
                            <td class="px-6 py-4 font-medium">{{ ders.isim }}</td>
                            <td class="px-6 py-4">
                                <span 
                                    v-if="ders.mekan_gereksinimi"
                                    class="inline-flex items-center rounded-md bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary"
                                >
                                    {{ mekanTipiLabel(ders.mekan_gereksinimi.mekan_tipi) }}
                                </span>
                                <span v-else class="text-sm text-muted-foreground">-</span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    v-if="ders.mekan_gereksinimi"
                                    class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-medium"
                                    :class="ders.mekan_gereksinimi.gereksinim_tipi === 'zorunlu' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'"
                                >
                                    {{ gereksinimTipiLabel(ders.mekan_gereksinimi.gereksinim_tipi) }}
                                </span>
                                <span v-else class="text-sm text-muted-foreground">-</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <Link
                                        :href="`/ders-mekan-gereksinimleri/${ders.id}/edit`"
                                        class="inline-flex items-center gap-1.5 rounded-md border bg-background px-3 py-1.5 text-sm font-medium hover:bg-accent transition-colors"
                                    >
                                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        </div>
    </AppLayout>
</template>