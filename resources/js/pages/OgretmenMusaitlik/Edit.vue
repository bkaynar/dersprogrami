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
  ogretmenler: Ogretmen[];
  zaman_dilimleri: ZamanDilimi[];
}>();

const form = useForm({
  ogretmen_id: props.musaitlik.ogretmen_id,
  zaman_dilimi_id: props.musaitlik.zaman_dilimi_id,
  musaitlik_tipi: props.musaitlik.musaitlik_tipi,
});

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
        title: 'Düzenle',
        href: `/ogretmen-musaitlik/${props.musaitlik.id}/edit`,
    },
];

const gunIsmi = (gun: number) => {
    const gunler = ['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi', 'Pazar'];
    return gunler[gun - 1] || gun.toString();
};

const formatSaat = (saat: string) => {
    return saat.substring(0, 5);
};

const submit = () => {
  form.put(`/ogretmen-musaitlik/${props.musaitlik.id}`);
};
</script>

<template>
  <Head title="Müsaitlik Düzenle" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-semibold">Müsaitlik Düzenle</h1>
          <p class="mt-1 text-sm text-muted-foreground">
            Öğretmen müsaitlik bilgilerini güncelleyin
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

      <!-- Form Card -->
      <div class="max-w-2xl rounded-lg border bg-card p-6">
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Öğretmen -->
          <div class="space-y-2">
            <label for="ogretmen_id" class="text-sm font-medium">
              Öğretmen
              <span class="text-destructive">*</span>
            </label>
            <select
              id="ogretmen_id"
              v-model.number="form.ogretmen_id"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
              :class="{ 'border-destructive': form.errors.ogretmen_id }"
            >
              <option :value="null">Bir öğretmen seçiniz</option>
              <option v-for="ogretmen in ogretmenler" :key="ogretmen.id" :value="ogretmen.id">
                {{ ogretmen.unvan }} {{ ogretmen.isim }}
              </option>
            </select>
            <p v-if="form.errors.ogretmen_id" class="text-sm text-destructive">
              {{ form.errors.ogretmen_id }}
            </p>
          </div>

          <!-- Zaman Dilimi -->
          <div class="space-y-2">
            <label for="zaman_dilimi_id" class="text-sm font-medium">
              Zaman Dilimi
              <span class="text-destructive">*</span>
            </label>
            <select
              id="zaman_dilimi_id"
              v-model.number="form.zaman_dilimi_id"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
              :class="{ 'border-destructive': form.errors.zaman_dilimi_id }"
            >
              <option :value="null">Bir zaman dilimi seçiniz</option>
              <option v-for="zaman in zaman_dilimleri" :key="zaman.id" :value="zaman.id">
                {{ gunIsmi(zaman.haftanin_gunu) }} {{ formatSaat(zaman.baslangic_saati) }}-{{ formatSaat(zaman.bitis_saati) }}
              </option>
            </select>
            <p v-if="form.errors.zaman_dilimi_id" class="text-sm text-destructive">
              {{ form.errors.zaman_dilimi_id }}
            </p>
          </div>

          <!-- Müsaitlik Tipi -->
          <div class="space-y-2">
            <label for="musaitlik_tipi" class="text-sm font-medium">
              Müsaitlik Tipi
              <span class="text-destructive">*</span>
            </label>
            <select
              id="musaitlik_tipi"
              v-model="form.musaitlik_tipi"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
              :class="{ 'border-destructive': form.errors.musaitlik_tipi }"
            >
              <option value="">Bir müsaitlik tipi seçiniz</option>
              <option value="musait">Müsait</option>
              <option value="musait_degil">Müsait Değil</option>
              <option value="tercih_edilen">Tercih Edilen</option>
            </select>
            <p v-if="form.errors.musaitlik_tipi" class="text-sm text-destructive">
              {{ form.errors.musaitlik_tipi }}
            </p>
            <p class="text-xs text-muted-foreground">
              Müsait: Öğretmen bu zaman diliminde ders verebilir. Müsait Değil: Öğretmen bu zaman diliminde ders veremez. Tercih Edilen: Öğretmen bu zaman dilimini tercih eder.
            </p>
          </div>

          <!-- Actions -->
          <div class="flex items-center gap-3 pt-4">
            <button
              type="submit"
              :disabled="form.processing"
              class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg v-if="form.processing" class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              {{ form.processing ? 'Güncelleniyor...' : 'Müsaitliği Güncelle' }}
            </button>
            <Link
              href="/ogretmen-musaitlik"
              class="inline-flex items-center gap-2 rounded-lg border bg-card px-4 py-2 text-sm font-medium hover:bg-accent"
            >
              İptal
            </Link>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
