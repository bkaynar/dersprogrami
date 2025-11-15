<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';

const form = useForm({
  isim: '',
  mekan_tipi: '',
  kapasite: 0,
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
    {
        title: 'Yeni Mekan',
        href: '/mekanlar/create',
    },
];

const submit = () => {
  form.post('/mekanlar');
};
</script>

<template>
  <Head title="Yeni Mekan" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-semibold">Yeni Mekan Oluştur</h1>
          <p class="mt-1 text-sm text-muted-foreground">
            Gerekli bilgileri doldurarak yeni bir mekan ekleyin
          </p>
        </div>
        <Link
          href="/mekanlar"
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
          <!-- İsim -->
          <div class="space-y-2">
            <label for="isim" class="text-sm font-medium">
              Mekan Adı
              <span class="text-destructive">*</span>
            </label>
            <input
              id="isim"
              v-model="form.isim"
              type="text"
              placeholder="Örn: A101 Derslik"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
              :class="{ 'border-destructive': form.errors.isim }"
            />
            <p v-if="form.errors.isim" class="text-sm text-destructive">
              {{ form.errors.isim }}
            </p>
          </div>

          <!-- Mekan Tipi -->
          <div class="space-y-2">
            <label for="mekan_tipi" class="text-sm font-medium">
              Mekan Tipi
              <span class="text-destructive">*</span>
            </label>
            <select
              id="mekan_tipi"
              v-model="form.mekan_tipi"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
              :class="{ 'border-destructive': form.errors.mekan_tipi }"
            >
              <option value="">Bir mekan tipi seçiniz</option>
              <option value="derslik">Derslik</option>
              <option value="laboratuvar">Laboratuvar</option>
              <option value="konferans_salonu">Konferans Salonu</option>
            </select>
            <p v-if="form.errors.mekan_tipi" class="text-sm text-destructive">
              {{ form.errors.mekan_tipi }}
            </p>
          </div>

          <!-- Kapasite -->
          <div class="space-y-2">
            <label for="kapasite" class="text-sm font-medium">
              Kapasite
              <span class="text-destructive">*</span>
            </label>
            <input
              id="kapasite"
              v-model.number="form.kapasite"
              type="number"
              min="0"
              placeholder="Örn: 50"
              class="flex h-10 w-32 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
              :class="{ 'border-destructive': form.errors.kapasite }"
            />
            <p v-if="form.errors.kapasite" class="text-sm text-destructive">
              {{ form.errors.kapasite }}
            </p>
            <p class="text-xs text-muted-foreground">
              Mekanın toplam kişi kapasitesini giriniz
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
              {{ form.processing ? 'Oluşturuluyor...' : 'Mekanı Oluştur' }}
            </button>
            <Link
              href="/mekanlar"
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
