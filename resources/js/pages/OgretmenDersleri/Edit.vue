<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

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
    tum_dersler: Ders[];
    secili_dersler: number[];
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
    {
        title: 'Düzenle',
        href: `/ogretmen-dersleri/${props.ogretmen.id}/edit`,
    },
];

const selectedDersIds = ref<number[]>([...props.secili_dersler]);

const form = useForm({
    ders_ids: selectedDersIds.value,
});

const toggleDers = (dersId: number) => {
    const index = selectedDersIds.value.indexOf(dersId);
    if (index > -1) {
        selectedDersIds.value.splice(index, 1);
    } else {
        selectedDersIds.value.push(dersId);
    }
    form.ders_ids = [...selectedDersIds.value];
};

const isDersSelected = (dersId: number) => {
    return selectedDersIds.value.includes(dersId);
};

const submit = () => {
    form.put(`/ogretmen-dersleri/${props.ogretmen.id}`, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="`${ogretmen.isim} - Dersleri Düzenle`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-semibold">{{ ogretmen.isim }} - Dersleri Düzenle</h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    {{ ogretmen.unvan }} - Verdiği dersleri seçin
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Dersler Seçimi -->
                <div class="rounded-lg border bg-card p-6">
                    <h2 class="mb-4 text-lg font-semibold">Dersler</h2>

                    <div v-if="tum_dersler.length > 0" class="space-y-2">
                        <div
                            v-for="ders in tum_dersler"
                            :key="ders.id"
                            class="flex items-center gap-3 rounded-lg border p-4 transition-colors hover:bg-muted/50"
                            :class="{ 'bg-primary/5 border-primary': isDersSelected(ders.id) }"
                        >
                            <input
                                type="checkbox"
                                :id="`ders-${ders.id}`"
                                :checked="isDersSelected(ders.id)"
                                @change="toggleDers(ders.id)"
                                class="h-4 w-4 rounded border-input text-primary focus:ring-2 focus:ring-primary focus:ring-offset-2"
                            />
                            <label
                                :for="`ders-${ders.id}`"
                                class="flex flex-1 cursor-pointer items-center justify-between"
                            >
                                <div>
                                    <div class="font-medium">{{ ders.isim }}</div>
                                    <div class="text-sm text-muted-foreground">{{ ders.ders_kodu }}</div>
                                </div>
                                <div class="text-sm text-muted-foreground">
                                    {{ ders.haftalik_saat }} saat/hafta
                                </div>
                            </label>
                        </div>
                    </div>

                    <div v-else class="flex flex-col items-center justify-center py-8 text-center">
                        <svg class="h-12 w-12 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <p class="mt-2 text-sm text-muted-foreground">
                            Henüz hiç ders yok. Önce ders eklemeniz gerekiyor.
                        </p>
                    </div>

                    <div v-if="form.errors.ders_ids" class="mt-2 text-sm text-destructive">
                        {{ form.errors.ders_ids }}
                    </div>
                </div>

                <!-- Summary -->
                <div class="rounded-lg border bg-muted/50 p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium">Seçili Ders Sayısı:</span>
                        <span class="text-lg font-semibold text-primary">{{ selectedDersIds.length }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3">
                    <a
                        :href="`/ogretmen-dersleri/${ogretmen.id}`"
                        class="inline-flex items-center gap-2 rounded-lg border bg-background px-4 py-2 text-sm font-medium hover:bg-muted"
                    >
                        İptal
                    </a>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg v-if="form.processing" class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span v-else>Kaydet</span>
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
