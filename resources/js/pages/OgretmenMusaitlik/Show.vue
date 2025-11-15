<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';

interface Ogretmen {
  id: number;
  isim: string;
  unvan: string;
}

interface ZamanDilimi {
  id: number;
  haftanin_gunu: number;
  baslangic_saati: string;
  bitis_saati: string;
}

interface Musaitlik {
  id: number;
  ogretmen_id: number;
  zaman_dilimi_id: number;
  musaitlik_tipi: string;
  ogretmen: Ogretmen;
  zaman_dilimi: ZamanDilimi;
}

const props = defineProps<{
  musaitlik: Musaitlik;
}>();

const form = useForm({});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Anasayfa',
        href: dashboard().url,
    },
    {
        title: 'Öğretmen Müsaitlik',
        href: '/ogretmen-musaitlik',
    },
    {
        title: 'Detay',
        href: `/ogretmen-musaitlik/${props.musaitlik.id}`,
    },
];

const gunIsmi = (gun: number) => {
    const gunler = ['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi', 'Pazar'];
    return gunler[gun - 1] || gun.toString();
};

const formatSaat = (saat: string) => {
    return saat.substring(0, 5);
};

const musaitlikTipiLabel = (tip: string) => {
    const labels: Record<string, string> = {
        'musait': 'Müsait',
        'musait_degil': 'Müsait Değil',
        'tercih_edilen': 'Tercih Edilen',
    };
    return labels[tip] || tip;
};

const destroy = () => {
  if (confirm('Bu müsaitlik kaydını silmek istediğinizden emin misiniz?')) {
    form.delete(`/ogretmen-musaitlik/${props.musaitlik.id}`);
  }
};
</script>

<template>
  <Head title="Müsaitlik Detayı" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-semibold">Müsaitlik Detayı</h1>
          <p class="mt-1 text-sm text-muted-foreground">
            Öğretmen müsaitlik bilgilerini görüntüleyin
          </p>
        </div>
        <Link
          href="/ogretmen-musaitlik"
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
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <h2 class="text-xl font-semibold">{{ props.musaitlik.ogretmen.isim }}</h2>
                <span class="mt-1 inline-flex items-center rounded-md bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary">
                  {{ props.musaitlik.ogretmen.unvan }}
                </span>
              </div>
            </div>
          </div>

          <div class="mt-6 space-y-4">
            <div class="flex items-center justify-between rounded-lg border bg-muted/50 p-4">
              <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-background">
                  <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-muted-foreground">Zaman Dilimi</p>
                  <p class="text-lg font-semibold">
                    {{ gunIsmi(props.musaitlik.zaman_dilimi.haftanin_gunu) }}
                    {{ formatSaat(props.musaitlik.zaman_dilimi.baslangic_saati) }}-{{ formatSaat(props.musaitlik.zaman_dilimi.bitis_saati) }}
                  </p>
                </div>
              </div>
            </div>

            <div class="flex items-center justify-between rounded-lg border bg-muted/50 p-4">
              <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-background">
                  <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-muted-foreground">Müsaitlik Tipi</p>
                  <p class="text-lg font-semibold">{{ musaitlikTipiLabel(props.musaitlik.musaitlik_tipi) }}</p>
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
              :href="`/ogretmen-musaitlik/${props.musaitlik.id}/edit`"
              class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
            >
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Müsaitliği Düzenle
            </Link>
            <button
              @click="destroy"
              :disabled="form.processing"
              class="inline-flex items-center gap-2 rounded-lg bg-destructive px-4 py-2 text-sm font-medium text-destructive-foreground hover:bg-destructive/90 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
              Müsaitliği Sil
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
