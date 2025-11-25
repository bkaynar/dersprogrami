<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { CheckCircle2, AlertCircle, AlertTriangle } from 'lucide-vue-next';

interface Mekan {
    id: number;
    isim: string;
    kapasite: number;
    mekan_tipi: string;
}

defineProps<{
    mekanlar: Mekan[];
}>();

// Flash messages
const page = usePage<any>();
const flashSuccess = computed(() => page.props.flash?.success || page.props.success);
const flashError = computed(() => page.props.flash?.error || page.props.error);
const flashWarning = computed(() => page.props.flash?.warning || page.props.warning);
const flashErrors = computed(() => page.props.flash?.errors || page.props.errors);
const flashImportErrors = computed(() => page.props.flash?.import_errors || page.props.import_errors);

// Validation errors from Inertia
const validationErrors = computed(() => {
    const errors = page.props.errors;
    if (errors && typeof errors === 'object' && !Array.isArray(errors)) {
        return Object.values(errors).flat() as string[];
    }
    return [];
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Anasayfa',
        href: dashboard().url,
    },
    {
        title: 'Mekanlar',
        href: '/mekanlar',
    },
];

// Import form
const fileInput = ref<HTMLInputElement | null>(null);
const importForm = useForm({
    file: null as File | null,
});

const downloadTemplate = () => {
    window.location.href = '/mekanlar/template/download';
};

const downloadExcel = () => {
    window.location.href = '/mekanlar/export';
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

    importForm.post('/mekanlar/import/preview', {
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
</script>

<template>
    <Head title="Mekanlar" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Flash Messages -->
            <div v-if="flashSuccess || flashError || flashWarning || validationErrors.length > 0" class="mb-6 space-y-4">
                <Alert v-if="flashSuccess" variant="default" class="border-green-200 bg-green-50 text-green-800">
                    <CheckCircle2 class="size-4" />
                    <AlertTitle>Başarılı!</AlertTitle>
                    <AlertDescription>{{ flashSuccess }}</AlertDescription>
                </Alert>

                <Alert v-if="flashError" variant="destructive">
                    <AlertCircle class="size-4" />
                    <AlertTitle>Hata!</AlertTitle>
                    <AlertDescription>{{ flashError }}</AlertDescription>
                </Alert>

                <Alert v-if="flashWarning" variant="default" class="border-yellow-200 bg-yellow-50 text-yellow-800">
                    <AlertTriangle class="size-4" />
                    <AlertTitle>Uyarı!</AlertTitle>
                    <AlertDescription>
                        {{ flashWarning }}
                        <ul v-if="(flashErrors && flashErrors.length > 0) || (flashImportErrors && flashImportErrors.length > 0)" class="mt-2 list-inside list-disc text-sm">
                            <li v-for="(error, index) in [...(flashErrors || []), ...(flashImportErrors || [])]" :key="index">{{ error }}</li>
                        </ul>
                    </AlertDescription>
                </Alert>

                <Alert v-if="validationErrors.length > 0" variant="destructive">
                    <AlertCircle class="size-4" />
                    <AlertTitle>Doğrulama Hataları</AlertTitle>
                    <AlertDescription>
                        <ul class="mt-2 list-inside list-disc text-sm">
                            <li v-for="(error, index) in validationErrors" :key="index">{{ error }}</li>
                        </ul>
                    </AlertDescription>
                </Alert>
            </div>

            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Mekanlar</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Tüm mekanları görüntüleyin ve yönetin
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <!-- Excel İşlemleri -->
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
                        @click="downloadExcel"
                        class="inline-flex items-center gap-2 rounded-lg border bg-background px-4 py-2 text-sm font-medium hover:bg-muted"
                        title="Mekanları Excel olarak indir"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Excel İndir
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
                        href="/mekanlar/create"
                        class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Yeni Mekan Ekle
                    </Link>
                </div>
            </div>

            <!-- Table -->
            <div v-if="mekanlar.length > 0" class="overflow-hidden rounded-lg border bg-card">
                <table class="w-full">
                    <thead>
                        <tr class="border-b bg-muted/50">
                            <th class="px-6 py-3 text-left text-sm font-medium">Mekan Adı</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Mekan Tipi</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Kapasite</th>
                            <th class="px-6 py-3 text-right text-sm font-medium">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr
                            v-for="mekan in mekanlar"
                            :key="mekan.id"
                            class="hover:bg-muted/50"
                        >
                            <td class="px-6 py-4 font-medium">
                                {{ mekan.isim }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-md bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary">
                                    {{ mekan.mekan_tipi }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-muted-foreground">
                                {{ mekan.kapasite }} kişi
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <Link
                                        :href="`/mekanlar/${mekan.id}`"
                                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium hover:bg-accent"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Görüntüle
                                    </Link>
                                    <Link
                                        :href="`/mekanlar/${mekan.id}/edit`"
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
                <h3 class="mt-4 font-semibold">Henüz mekan yok</h3>
                <p class="mt-2 text-center text-sm text-muted-foreground">
                    Başlamak için ilk mekanınızı oluşturun
                </p>
                <Link
                    href="/mekanlar/create"
                    class="mt-4 inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    İlk Mekanı Oluştur
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
