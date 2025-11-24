<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import derslerRoute from '@/routes/dersler';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { UserRound } from 'lucide-vue-next'; // Yeni eklendi

interface Ogretmen {
    id: number;
    isim: string;
    unvan: string;
    email: string;
}

const props = defineProps<{
    ders: {
        id: number;
        ders_kodu: string;
        isim: string;
        haftalik_saat: number;
        ogretmenler: Ogretmen[]; // Yeni eklendi
    };
}>();

const form = useForm({});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Anasayfa',
        href: dashboard().url,
    },
    {
        title: 'Dersler',
        href: derslerRoute.index().url,
    },
    {
        title: props.ders.isim,
        href: derslerRoute.show(props.ders.id).url,
    },
];

const destroy = () => {
  if (confirm('Bu dersi silmek istediğinizden emin misiniz?')) {
    form.delete(derslerRoute.destroy(props.ders.id).url);
  }
};
</script>

<template>
  <Head :title="props.ders.isim" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-semibold">Ders Detayı</h1>
          <p class="mt-1 text-sm text-muted-foreground">
            Ders bilgilerini görüntüleyin
          </p>
        </div>
        <Link
          :href="derslerRoute.index().url"
          class="inline-flex items-center gap-2 rounded-lg border bg-card px-4 py-2 text-sm font-medium hover:bg-accent"
        >
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Geri Dön
        </Link>
      </div>

      <!-- Content -->
      <div class="max-w-2xl space-y-6">
        <!-- Info Card -->
        <div class="rounded-lg border bg-card p-6">
          <div class="flex items-start justify-between">
            <div class="flex items-center gap-4">
              <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
              <div>
                <h2 class="text-xl font-semibold">{{ props.ders.isim }}</h2>
                <span class="mt-1 inline-flex items-center rounded-md bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary">
                  {{ props.ders.ders_kodu }}
                </span>
              </div>
            </div>
          </div>

          <div class="mt-6 space-y-4">
            <div class="flex items-center justify-between rounded-lg border bg-muted/50 p-4">
              <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-background">
                  <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-muted-foreground">Haftalık Saat</p>
                  <p class="text-lg font-semibold">{{ props.ders.haftalik_saat }} saat</p>
                </div>
              </div>
            </div>

            <div class="flex items-center justify-between rounded-lg border bg-muted/50 p-4">
              <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-background">
                  <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-muted-foreground">Ders Kodu</p>
                  <p class="text-lg font-semibold">{{ props.ders.ders_kodu }}</p>
                </div>
              </div>
            </div>

            <!-- Dersi Veren Hocalar -->
            <div class="flex items-center justify-between rounded-lg border bg-muted/50 p-4">
              <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-background">
                  <UserRound class="h-5 w-5 text-muted-foreground" />
                </div>
                <div>
                  <p class="text-sm font-medium text-muted-foreground">Dersi Veren Hocalar</p>
                  <div v-if="props.ders.ogretmenler && props.ders.ogretmenler.length > 0" class="flex flex-wrap gap-2 mt-1">
                    <span v-for="ogretmen in props.ders.ogretmenler" :key="ogretmen.id"
                      class="inline-flex items-center rounded-md bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800">
                      {{ ogretmen.isim }} ({{ ogretmen.unvan }})
                    </span>
                  </div>
                  <p v-else class="text-lg font-semibold text-muted-foreground">
                    <div class="rounded-md border border-yellow-300 bg-yellow-50 p-3 text-sm text-yellow-800 flex items-center gap-2">
                        <AlertTriangle class="h-5 w-5" />
                        Bu derse atanmış bir hoca bulunmamaktadır.
                        <Link :href="`/ogretmen-dersleri/${props.ders.id}/edit`" class="ml-auto inline-flex items-center gap-1.5 rounded-md bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-900 hover:bg-yellow-200">
                            Hoca Ata <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </Link>
                    </div>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions Card -->
        <div class="rounded-lg border bg-card p-6">
          <h3 class="mb-4 font-semibold">İşlemler</h3>
          <div class="flex flex-wrap gap-3">
            <Link
              :href="derslerRoute.edit(props.ders.id).url"
              class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
            >
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Dersi Düzenle
            </Link>
            <button
              @click="destroy"
              :disabled="form.processing"
              class="inline-flex items-center gap-2 rounded-lg bg-destructive px-4 py-2 text-sm font-medium text-destructive-foreground hover:bg-destructive/90 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
              Dersi Sil
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
