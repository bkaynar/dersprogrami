<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';

const form = useForm({
  haftanin_gunu: '',
  baslangic_saati: '',
  bitis_saati: '',
});

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
        title: 'Yeni Zaman Dilimi',
        href: '/zaman-dilimleri/create',
    },
];

const submit = () => {
  form.post('/zaman-dilimleri');
};

const gunler = [
    { value: 'pazartesi', label: 'Pazartesi' },
    { value: 'sali', label: 'Salı' },
    { value: 'carsamba', label: 'Çarşamba' },
    { value: 'persembe', label: 'Perşembe' },
    { value: 'cuma', label: 'Cuma' },
    { value: 'cumartesi', label: 'Cumartesi' },
    { value: 'pazar', label: 'Pazar' }
];
</script>

<template>
  <Head title="Yeni Zaman Dilimi" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-semibold">Yeni Zaman Dilimi Oluştur</h1>
          <p class="mt-1 text-sm text-muted-foreground">
            Gerekli bilgileri doldurarak yeni bir zaman dilimi ekleyin
          </p>
        </div>
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

      <!-- Form Card -->
      <div class="max-w-2xl rounded-lg border bg-card p-6">
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Gün -->
          <div class="space-y-2">
            <label for="haftanin_gunu" class="text-sm font-medium">
              Gün
              <span class="text-destructive">*</span>
            </label>
            <select
              id="haftanin_gunu"
              v-model="form.haftanin_gunu"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
              :class="{ 'border-destructive': form.errors.haftanin_gunu }"
            >
              <option value="">Bir gün seçiniz</option>
              <option v-for="gun in gunler" :key="gun.value" :value="gun.value">
                {{ gun.label }}
              </option>
            </select>
            <p v-if="form.errors.haftanin_gunu" class="text-sm text-destructive">
              {{ form.errors.haftanin_gunu }}
            </p>
          </div>

          <!-- Başlangıç Saati -->
          <div class="space-y-2">
            <label for="baslangic_saati" class="text-sm font-medium">
              Başlangıç Saati
              <span class="text-destructive">*</span>
            </label>
            <input
              id="baslangic_saati"
              v-model="form.baslangic_saati"
              type="time"
              class="flex h-10 w-48 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
              :class="{ 'border-destructive': form.errors.baslangic_saati }"
            />
            <p v-if="form.errors.baslangic_saati" class="text-sm text-destructive">
              {{ form.errors.baslangic_saati }}
            </p>
          </div>

          <!-- Bitiş Saati -->
          <div class="space-y-2">
            <label for="bitis_saati" class="text-sm font-medium">
              Bitiş Saati
              <span class="text-destructive">*</span>
            </label>
            <input
              id="bitis_saati"
              v-model="form.bitis_saati"
              type="time"
              class="flex h-10 w-48 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
              :class="{ 'border-destructive': form.errors.bitis_saati }"
            />
            <p v-if="form.errors.bitis_saati" class="text-sm text-destructive">
              {{ form.errors.bitis_saati }}
            </p>
            <p class="text-xs text-muted-foreground">
              Zaman diliminin başlangıç ve bitiş saatlerini giriniz
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
              {{ form.processing ? 'Oluşturuluyor...' : 'Zaman Dilimini Oluştur' }}
            </button>
            <Link
              href="/zaman-dilimleri"
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
