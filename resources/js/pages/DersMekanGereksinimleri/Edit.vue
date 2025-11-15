<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';

interface Ders {
  id: number;
  ders_kodu: string;
  isim: string;
}

interface Gereksinim {
  id: number;
  ders_id: number;
  ders: Ders;
  mekan_tipi: string;
  gereksinim_tipi: string;
}

const props = defineProps<{
  gereksinim: Gereksinim;
  dersler: Ders[];
}>();

const form = useForm({
  ders_id: props.gereksinim.ders_id,
  mekan_tipi: props.gereksinim.mekan_tipi,
  gereksinim_tipi: props.gereksinim.gereksinim_tipi,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Anasayfa',
        href: dashboard().url,
    },
    {
        title: 'Ders Mekan Gereksinimleri',
        href: '/ders-mekan-gereksinimleri',
    },
    {
        title: 'Düzenle',
        href: `/ders-mekan-gereksinimleri/${props.gereksinim.id}/edit`,
    },
];

const submit = () => {
  form.put(`/ders-mekan-gereksinimleri/${props.gereksinim.id}`);
};
</script>

<template>
  <Head title="Mekan Gereksinimi Düzenle" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-semibold">Mekan Gereksinimi Düzenle</h1>
          <p class="mt-1 text-sm text-muted-foreground">
            Mekan gereksinimi bilgilerini güncelleyin
          </p>
        </div>
        <Link
          href="/ders-mekan-gereksinimleri"
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
          <!-- Ders -->
          <div class="space-y-2">
            <label for="ders_id" class="text-sm font-medium">
              Ders
              <span class="text-destructive">*</span>
            </label>
            <select
              id="ders_id"
              v-model.number="form.ders_id"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
              :class="{ 'border-destructive': form.errors.ders_id }"
            >
              <option :value="null">Bir ders seçiniz</option>
              <option v-for="ders in dersler" :key="ders.id" :value="ders.id">
                {{ ders.ders_kodu }} - {{ ders.isim }}
              </option>
            </select>
            <p v-if="form.errors.ders_id" class="text-sm text-destructive">
              {{ form.errors.ders_id }}
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
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
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

          <!-- Gereksinim Tipi -->
          <div class="space-y-2">
            <label for="gereksinim_tipi" class="text-sm font-medium">
              Gereksinim Tipi
              <span class="text-destructive">*</span>
            </label>
            <select
              id="gereksinim_tipi"
              v-model="form.gereksinim_tipi"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
              :class="{ 'border-destructive': form.errors.gereksinim_tipi }"
            >
              <option value="">Bir gereksinim tipi seçiniz</option>
              <option value="zorunlu">Zorunlu</option>
              <option value="olabilir">Olabilir</option>
            </select>
            <p v-if="form.errors.gereksinim_tipi" class="text-sm text-destructive">
              {{ form.errors.gereksinim_tipi }}
            </p>
            <p class="text-xs text-muted-foreground">
              Zorunlu: Ders mutlaka bu mekan tipinde yapılmalı. Olabilir: Tercih edilir ama zorunlu değil.
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
              {{ form.processing ? 'Güncelleniyor...' : 'Gereksinimi Güncelle' }}
            </button>
            <Link
              href="/ders-mekan-gereksinimleri"
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
