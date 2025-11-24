<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';
import axios from 'axios';

const props = defineProps<{
    program_var: boolean;
    missing_data: Array<{ name: string; route: string }>;
    can_generate: boolean;
}>();

const page = usePage();
const flash = computed(() => page.props.flash as any);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Anasayfa',
        href: dashboard().url,
    },
    {
        title: 'Program Oluştur',
        href: '/program-olustur',
    },
];

const generateForm = useForm({});
const deleteForm = useForm({});

// İlerleme durumu
const generationStatus = ref<any>(null);
const isGenerating = ref(false);
let statusInterval: any = null;

const generateProgram = () => {
    if (confirm('Yeni bir ders programı oluşturulacak. Bu işlem birkaç dakika sürebilir. Devam etmek istiyor musunuz?')) {
        generateForm.post('/program-olustur/generate', {
            onSuccess: () => {
                isGenerating.value = true;
                startStatusPolling();
            }
        });
    }
};

const deleteProgram = () => {
    if (confirm('Mevcut programı silmek istediğinizden emin misiniz?')) {
        deleteForm.delete('/program-olustur');
    }
};

// İlerleme durumunu kontrol et
const checkStatus = async () => {
    try {
        const response = await axios.get('/program-olustur/status');
        generationStatus.value = response.data;

        if (generationStatus.value.status === 'completed') {
            isGenerating.value = false;
            stopStatusPolling();
            // Sayfayı yenile
            router.reload();
        } else if (generationStatus.value.status === 'failed') {
            isGenerating.value = false;
            stopStatusPolling();
        }
    } catch (error) {
        console.error('Status check error:', error);
    }
};

// Durum polling başlat
const startStatusPolling = () => {
    checkStatus(); // İlk kontrolü hemen yap
    statusInterval = setInterval(checkStatus, 2000); // 2 saniyede bir kontrol et
};

// Durum polling durdur
const stopStatusPolling = () => {
    if (statusInterval) {
        clearInterval(statusInterval);
        statusInterval = null;
    }
};

// Component mount olduğunda devam eden işlem var mı kontrol et
onMounted(() => {
    checkStatus();
    if (generationStatus.value?.status === 'running') {
        isGenerating.value = true;
        startStatusPolling();
    }
});

// Component unmount olduğunda interval'i temizle
onUnmounted(() => {
    stopStatusPolling();
});
</script>

<template>
    <Head title="Program Oluştur" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-semibold">Program Oluştur</h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Genetik algoritma ile otomatik ders programı oluşturun
                </p>
            </div>

            <!-- Flash Messages -->
            <div v-if="flash?.error" class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-950">
                <div class="flex items-start gap-3">
                    <svg class="h-5 w-5 flex-shrink-0 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm text-red-800 dark:text-red-200">{{ flash.error }}</div>
                </div>
            </div>

            <div v-if="flash?.success" class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 dark:border-green-800 dark:bg-green-950">
                <div class="flex items-start gap-3">
                    <svg class="h-5 w-5 flex-shrink-0 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm text-green-800 dark:text-green-200">{{ flash.success }}</div>
                </div>
            </div>

            <!-- Missing Data Warning -->
            <div v-if="!can_generate && missing_data.length > 0" class="mb-6 rounded-lg border border-amber-200 bg-amber-50 p-6 dark:border-amber-800 dark:bg-amber-950">
                <div class="flex items-start gap-3">
                    <svg class="h-6 w-6 flex-shrink-0 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div class="flex-1">
                        <h3 class="font-semibold text-amber-900 dark:text-amber-100">Program Oluşturulamıyor</h3>
                        <p class="mt-2 text-sm text-amber-800 dark:text-amber-200">
                            Program oluşturmak için aşağıdaki verilerin sisteme girilmesi gerekiyor:
                        </p>
                        <ul class="mt-3 space-y-2">
                            <li v-for="item in missing_data" :key="item.name" class="flex items-center justify-between rounded border border-amber-300 bg-white px-3 py-2 dark:border-amber-700 dark:bg-amber-900/50">
                                <span class="text-sm font-medium text-amber-900 dark:text-amber-100">{{ item.name }}</span>
                                <a
                                    :href="`/${item.route}`"
                                    class="inline-flex items-center gap-1 rounded bg-amber-600 px-3 py-1 text-xs font-medium text-white hover:bg-amber-700 dark:bg-amber-500 dark:hover:bg-amber-600"
                                >
                                    Ekle
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div v-else class="mb-6 rounded-lg border border-blue-200 bg-blue-50 p-6 dark:border-blue-800 dark:bg-blue-950">
                <div class="flex items-start gap-3">
                    <svg class="h-6 w-6 flex-shrink-0 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="font-semibold text-blue-900 dark:text-blue-100">Genetik Algoritma ile Program Oluşturma</h3>
                        <p class="mt-2 text-sm text-blue-800 dark:text-blue-200">
                            Sistem, tüm kısıtlamaları (öğretmen müsaitliği, mekan uygunluğu, grup kısıtlamaları)
                            dikkate alarak en optimum ders programını oluşturacaktır.
                        </p>
                        <ul class="mt-3 space-y-1 text-sm text-blue-700 dark:text-blue-300">
                            <li>• Öğretmen çakışmalarını önler</li>
                            <li>• Sınıf çakışmalarını önler</li>
                            <li>• Mekan kullanımını optimize eder</li>
                            <li>• Müsaitlik ve kısıtlamalara uyar</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-4">
                <!-- İlerleme Göstergesi -->
                <div v-if="isGenerating && generationStatus" class="rounded-lg border border-primary/50 bg-primary/5 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-primary">Program Oluşturuluyor...</h3>
                            <p class="mt-1 text-sm text-muted-foreground">
                                {{ generationStatus.message }}
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-primary">{{ generationStatus.progress }}%</div>
                            <div v-if="generationStatus.generation" class="text-xs text-muted-foreground">
                                Nesil: {{ generationStatus.generation }}/{{ generationStatus.max_generations }}
                            </div>
                        </div>
                    </div>
                    <!-- Progress Bar -->
                    <div class="h-2 w-full overflow-hidden rounded-full bg-muted">
                        <div
                            class="h-full bg-primary transition-all duration-500"
                            :style="{ width: generationStatus.progress + '%' }"
                        ></div>
                    </div>
                    <div v-if="generationStatus.best_fitness" class="mt-2 text-xs text-muted-foreground">
                        En İyi Fitness: {{ Math.round(generationStatus.best_fitness) }}
                    </div>
                </div>

                <!-- Generate Program Card -->
                <div class="rounded-lg border bg-card p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h2 class="text-lg font-semibold">Yeni Program Oluştur</h2>
                            <p class="mt-1 text-sm text-muted-foreground">
                                Genetik algoritma kullanarak tüm dersleri otomatik olarak planla
                            </p>
                        </div>
                        <button
                            @click="generateProgram"
                            :disabled="!can_generate || generateForm.processing || isGenerating"
                            :title="!can_generate ? 'Önce gerekli verileri ekleyin' : ''"
                            class="inline-flex items-center gap-2 rounded-lg bg-primary px-6 py-3 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <svg v-if="generateForm.processing || isGenerating" class="h-5 w-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            {{ (generateForm.processing || isGenerating) ? 'Oluşturuluyor...' : 'Program Oluştur' }}
                        </button>
                    </div>
                </div>

                <!-- View Program Card (if exists) -->
                <div v-if="program_var" class="rounded-lg border bg-card p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h2 class="text-lg font-semibold">Mevcut Program</h2>
                            <p class="mt-1 text-sm text-muted-foreground">
                                Oluşturulmuş ders programını görüntüle veya sil
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <a
                                href="/program-olustur/show"
                                class="inline-flex items-center gap-2 rounded-lg border bg-background px-4 py-2 text-sm font-medium hover:bg-muted"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Programı Görüntüle
                            </a>
                            <button
                                @click="deleteProgram"
                                :disabled="deleteForm.processing"
                                class="inline-flex items-center gap-2 rounded-lg border border-destructive/50 bg-background px-4 py-2 text-sm font-medium text-destructive hover:bg-destructive/10 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Programı Sil
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="flex flex-col items-center justify-center rounded-lg border border-dashed py-12">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-muted">
                        <svg class="h-6 w-6 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-semibold">Henüz program oluşturulmamış</h3>
                    <p class="mt-2 text-center text-sm text-muted-foreground">
                        Yukarıdaki butonu kullanarak ilk ders programınızı oluşturun
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
