<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { ref } from 'vue';

interface ZamanDilimi {
    id: number;
    haftanin_gunu: string;
    baslangic_saati: string;
    bitis_saati: string;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    zaman_dilimi: ZamanDilimi;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Anasayfa',
        href: dashboard().url,
    },
    {
        title: 'Zaman Dilimleri',
        href: '/zaman-dilimleri',
    },
    {
        title: `${props.zaman_dilimi.haftanin_gunu.charAt(0).toUpperCase() + props.zaman_dilimi.haftanin_gunu.slice(1)} - ${props.zaman_dilimi.baslangic_saati.substring(0, 5)}`,
        href: `/zaman-dilimleri/${props.zaman_dilimi.id}`,
    },
];

const showDeleteDialog = ref(false);
const isDeleting = ref(false);

const deleteDilim = () => {
    isDeleting.value = true;
    router.delete(`/zaman-dilimleri/${props.zaman_dilimi.id}`, {
        onFinish: () => {
            isDeleting.value = false;
            showDeleteDialog.value = false;
        },
    });
};

const formatTime = (time: string) => {
    return time.substring(0, 5);
};

const calculateDuration = () => {
    const start = new Date(`2000-01-01 ${props.zaman_dilimi.baslangic_saati}`);
    const end = new Date(`2000-01-01 ${props.zaman_dilimi.bitis_saati}`);
    const minutes = Math.floor((end.getTime() - start.getTime()) / (1000 * 60));
    const hours = Math.floor(minutes / 60);
    const remainingMinutes = minutes % 60;

    if (hours > 0 && remainingMinutes > 0) {
        return `${hours} saat ${remainingMinutes} dakika`;
    } else if (hours > 0) {
        return `${hours} saat`;
    } else {
        return `${remainingMinutes} dakika`;
    }
};
</script>

<template>
  <Head :title="`${zaman_dilimi.haftanin_gunu.charAt(0).toUpperCase() + zaman_dilimi.haftanin_gunu.slice(1)} - ${formatTime(zaman_dilimi.baslangic_saati)}`" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-semibold">{{ zaman_dilimi.haftanin_gunu.charAt(0).toUpperCase() + zaman_dilimi.haftanin_gunu.slice(1) }} - {{ formatTime(zaman_dilimi.baslangic_saati) }}</h1>
          <p class="mt-1 text-sm text-muted-foreground">
            Zaman dilimi detaylarını görüntüleyin
          </p>
        </div>
        <div class="flex items-center gap-3">
          <Link
            :href="`/zaman-dilimleri/${zaman_dilimi.id}/edit`"
            class="inline-flex items-center gap-2 rounded-lg border bg-card px-4 py-2 text-sm font-medium hover:bg-accent"
          >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Düzenle
          </Link>
          <button
            @click="showDeleteDialog = true"
            class="inline-flex items-center gap-2 rounded-lg border border-destructive bg-destructive/10 px-4 py-2 text-sm font-medium text-destructive hover:bg-destructive/20"
          >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Sil
          </button>
          <Link
            href="/zaman-dilimleri"
            class="inline-flex items-center gap-2 rounded-lg border bg-card px-4 py-2 text-sm font-medium hover:bg-accent"
          >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Geri Dön
          </Link>
        </div>
      </div>

      <!-- Details Card -->
      <div class="max-w-2xl rounded-lg border bg-card p-6">
        <div class="space-y-6">
          <div>
            <h3 class="text-sm font-medium text-muted-foreground">Gün</h3>
            <p class="mt-1">
              <span class="inline-flex items-center rounded-md bg-primary/10 px-2.5 py-1 text-sm font-medium text-primary capitalize">
                {{ zaman_dilimi.haftanin_gunu.charAt(0).toUpperCase() + zaman_dilimi.haftanin_gunu.slice(1) }}
              </span>
            </p>
          </div>

          <div class="grid grid-cols-2 gap-6">
            <div>
              <h3 class="text-sm font-medium text-muted-foreground">Başlangıç Saati</h3>
              <p class="mt-1 text-lg font-medium">{{ formatTime(zaman_dilimi.baslangic_saati) }}</p>
            </div>

            <div>
              <h3 class="text-sm font-medium text-muted-foreground">Bitiş Saati</h3>
              <p class="mt-1 text-lg font-medium">{{ formatTime(zaman_dilimi.bitis_saati) }}</p>
            </div>
          </div>

          <div>
            <h3 class="text-sm font-medium text-muted-foreground">Süre</h3>
            <p class="mt-1 text-lg font-medium">{{ calculateDuration() }}</p>
          </div>

          <div class="border-t pt-6">
            <div class="grid grid-cols-2 gap-6">
              <div>
                <h3 class="text-sm font-medium text-muted-foreground">Oluşturulma Tarihi</h3>
                <p class="mt-1 text-sm">{{ new Date(zaman_dilimi.created_at).toLocaleDateString('tr-TR') }}</p>
              </div>

              <div>
                <h3 class="text-sm font-medium text-muted-foreground">Son Güncelleme</h3>
                <p class="mt-1 text-sm">{{ new Date(zaman_dilimi.updated_at).toLocaleDateString('tr-TR') }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Dialog -->
    <div v-if="showDeleteDialog" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
      <div class="w-full max-w-md rounded-lg border bg-card p-6 shadow-lg">
        <h2 class="text-xl font-semibold">Zaman Dilimini Sil</h2>
        <p class="mt-2 text-sm text-muted-foreground">
          <strong>{{ zaman_dilimi.haftanin_gunu.charAt(0).toUpperCase() + zaman_dilimi.haftanin_gunu.slice(1) }} - {{ formatTime(zaman_dilimi.baslangic_saati) }}</strong> zaman dilimini silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.
        </p>
        <div class="mt-6 flex items-center justify-end gap-3">
          <button
            @click="showDeleteDialog = false"
            :disabled="isDeleting"
            class="inline-flex items-center gap-2 rounded-lg border bg-card px-4 py-2 text-sm font-medium hover:bg-accent disabled:opacity-50"
          >
            İptal
          </button>
          <button
            @click="deleteDilim"
            :disabled="isDeleting"
            class="inline-flex items-center gap-2 rounded-lg bg-destructive px-4 py-2 text-sm font-medium text-destructive-foreground hover:bg-destructive/90 disabled:opacity-50"
          >
            <svg v-if="isDeleting" class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            {{ isDeleting ? 'Siliniyor...' : 'Evet, Sil' }}
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
