<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';

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

defineProps<{
  gruplar: OgrenciGrubu[];
  zaman_dilimleri: ZamanDilimi[];
}>();

const form = useForm({
  ogrenci_grup_id: null as number | null,
  zaman_dilimi_id: null as number | null,
  musait_mi: true,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Anasayfa',
        href: dashboard().url,
    },
    {
        title: 'Grup Kısıtlamaları',
        href: '/grup-kisitlamalari',
    },
    {
        title: 'Yeni Kısıtlama',
        href: '/grup-kisitlamalari/create',
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
  form.post('/grup-kisitlamalari');
};
</script>

<template>
  <Head title="Yeni Grup Kısıtlaması" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-semibold">Yeni Grup Kısıtlaması Oluştur</h1>
          <p class="mt-1 text-sm text-muted-foreground">
            Bir öğrenci grubu için zaman kısıtlaması tanımlayın
          </p>
        </div>
        <Link
          href="/grup-kisitlamalari"
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
          <!-- Öğrenci Grubu -->
          <div class="space-y-2">
            <label for="ogrenci_grup_id" class="text-sm font-medium">
              Öğrenci Grubu
              <span class="text-destructive">*</span>
            </label>
            <select
              id="ogrenci_grup_id"
              v-model.number="form.ogrenci_grup_id"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
              :class="{ 'border-destructive': form.errors.ogrenci_grup_id }"
            >
              <option :value="null">Bir öğrenci grubu seçiniz</option>
              <option v-for="grup in gruplar" :key="grup.id" :value="grup.id">
                {{ grup.isim }} ({{ grup.yil }}. Yıl)
              </option>
            </select>
            <p v-if="form.errors.ogrenci_grup_id" class="text-sm text-destructive">
              {{ form.errors.ogrenci_grup_id }}
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

          <!-- Müsait Mi -->
          <div class="space-y-2">
            <label for="musait_mi" class="text-sm font-medium">
              Müsaitlik Durumu
              <span class="text-destructive">*</span>
            </label>
            <select
              id="musait_mi"
              v-model="form.musait_mi"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
              :class="{ 'border-destructive': form.errors.musait_mi }"
            >
              <option :value="true">Müsait</option>
              <option :value="false">Müsait Değil</option>
            </select>
            <p v-if="form.errors.musait_mi" class="text-sm text-destructive">
              {{ form.errors.musait_mi }}
            </p>
            <p class="text-xs text-muted-foreground">
              Müsait: Grup bu zaman diliminde ders alabilir. Müsait Değil: Grup bu zaman diliminde ders alamaz.
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              {{ form.processing ? 'Oluşturuluyor...' : 'Kısıtlamayı Oluştur' }}
            </button>
            <Link
              href="/grup-kisitlamalari"
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
