<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { ref, computed } from 'vue';

interface Ders {
  id: number;
  ders_kodu: string;
  isim: string;
}

const props = defineProps<{
  dersler: Ders[];
  eklenmis_ders_ids: number[];
}>();

const showOnlyAvailable = ref(true);

// Filtrelenmiş dersler
const filteredDersler = computed(() => {
  if (!showOnlyAvailable.value) {
    return props.dersler;
  }
  return props.dersler.filter(ders => !props.eklenmis_ders_ids.includes(ders.id));
});

const form = useForm({
  ders_id: null as number | null,
  mekan_tipi: '',
  gereksinim_tipi: '',
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
        title: 'Yeni Gereksinim',
        href: '/ders-mekan-gereksinimleri/create',
    },
];

const submit = () => {
  form.post('/ders-mekan-gereksinimleri');
};
</script>

<template>
  <Head title="Yeni Ders Mekan Gereksinimi" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-semibold">Yeni Ders Mekan Gereksinimi Oluştur</h1>
          <p class="mt-1 text-sm text-muted-foreground">
            Bir ders için mekan gereksinimi tanımlayın
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
            <div class="flex items-center justify-between">
              <label for="ders_id" class="text-sm font-medium">
                Ders
                <span class="text-destructive">*</span>
              </label>
              <div class="flex items-center gap-2">
                <input
                  id="showOnlyAvailable"
                  v-model="showOnlyAvailable"
                  type="checkbox"
                  class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-2 focus:ring-primary"
                />
                <label for="showOnlyAvailable" class="text-xs text-muted-foreground cursor-pointer select-none">
                  Sadece eklenmeyen dersleri göster
                </label>
              </div>
            </div>
            <select
              id="ders_id"
              v-model.number="form.ders_id"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
              :class="{ 'border-destructive': form.errors.ders_id }"
            >
              <option :value="null">Bir ders seçiniz</option>
              <option v-for="ders in filteredDersler" :key="ders.id" :value="ders.id">
                {{ ders.ders_kodu }} - {{ ders.isim }}
              </option>
            </select>
            <p v-if="form.errors.ders_id" class="text-sm text-destructive">
              {{ form.errors.ders_id }}
            </p>
            <p v-if="showOnlyAvailable && filteredDersler.length === 0" class="text-xs text-amber-600">
              ℹ️ Tüm derslere mekan gereksinimi eklenmiş. Tüm dersleri görmek için yukarıdaki kutunun işaretini kaldırın.
            </p>
            <p v-else-if="showOnlyAvailable" class="text-xs text-muted-foreground">
              {{ filteredDersler.length }} ders listeleniyor ({{ eklenmis_ders_ids.length }} ders gizlendi)
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              {{ form.processing ? 'Oluşturuluyor...' : 'Gereksinimi Oluştur' }}
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
