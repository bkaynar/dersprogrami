<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import derslerRoute from '@/routes/dersler';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';

const props = defineProps<{ ders: { id: number; ders_kodu: string; isim: string; haftalik_saat: number } }>();

const form = useForm({
  ders_kodu: props.ders.ders_kodu,
  isim: props.ders.isim,
  haftalik_saat: props.ders.haftalik_saat,
});

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
    {
        title: 'Düzenle',
        href: derslerRoute.edit(props.ders.id).url,
    },
];

const submit = () => {
  form.put(derslerRoute.update(props.ders.id).url);
};
</script>

<template>
  <Head :title="`Ders Düzenle - ${props.ders.isim}`" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-semibold">Dersi Düzenle</h1>
          <p class="mt-1 text-sm text-muted-foreground">
            Ders bilgilerini güncelleyin
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

      <!-- Form Card -->
      <div class="max-w-2xl rounded-lg border bg-card p-6">
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Ders Kodu -->
          <div class="space-y-2">
            <label for="ders_kodu" class="text-sm font-medium">
              Ders Kodu
              <span class="text-destructive">*</span>
            </label>
            <input
              id="ders_kodu"
              v-model="form.ders_kodu"
              type="text"
              placeholder="Örn: MAT101"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
              :class="{ 'border-destructive': form.errors.ders_kodu }"
            />
            <p v-if="form.errors.ders_kodu" class="text-sm text-destructive">
              {{ form.errors.ders_kodu }}
            </p>
          </div>

          <!-- İsim -->
          <div class="space-y-2">
            <label for="isim" class="text-sm font-medium">
              Ders Adı
              <span class="text-destructive">*</span>
            </label>
            <input
              id="isim"
              v-model="form.isim"
              type="text"
              placeholder="Örn: Matematik I"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
              :class="{ 'border-destructive': form.errors.isim }"
            />
            <p v-if="form.errors.isim" class="text-sm text-destructive">
              {{ form.errors.isim }}
            </p>
          </div>

          <!-- Haftalık Saat -->
          <div class="space-y-2">
            <label for="haftalik_saat" class="text-sm font-medium">
              Haftalık Saat
              <span class="text-destructive">*</span>
            </label>
            <input
              id="haftalik_saat"
              v-model.number="form.haftalik_saat"
              type="number"
              min="0"
              placeholder="Örn: 4"
              class="flex h-10 w-32 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
              :class="{ 'border-destructive': form.errors.haftalik_saat }"
            />
            <p v-if="form.errors.haftalik_saat" class="text-sm text-destructive">
              {{ form.errors.haftalik_saat }}
            </p>
            <p class="text-xs text-muted-foreground">
              Dersin haftalık toplam saat sayısını giriniz
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
              {{ form.processing ? 'Güncelleniyor...' : 'Dersi Güncelle' }}
            </button>
            <Link
              :href="derslerRoute.index().url"
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
