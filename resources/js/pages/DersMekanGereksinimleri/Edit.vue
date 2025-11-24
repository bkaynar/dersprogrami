<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { ref, watch } from 'vue';

interface DersMekanGereksinimi {
  id: number;
  mekan_tipi: string;
  gereksinim_tipi: string;
}

interface Ders {
  id: number;
  ders_kodu: string;
  isim: string;
  mekan_gereksinimi: DersMekanGereksinimi | null;
}

const props = defineProps<{
  ders: Ders;
}>();

const hasRequirement = ref(!!props.ders.mekan_gereksinimi);

const form = useForm({
  mekan_tipi: props.ders.mekan_gereksinimi?.mekan_tipi || '',
  gereksinim_tipi: props.ders.mekan_gereksinimi?.gereksinim_tipi || '',
  has_requirement: hasRequirement.value,
});

// Gereksinim durumu değiştiğinde formu güncelle
watch(hasRequirement, (val) => {
  form.has_requirement = val;
  if (!val) {
    form.mekan_tipi = '';
    form.gereksinim_tipi = '';
  } else if (!form.mekan_tipi && !form.gereksinim_tipi) {
    // Varsayılan değerler
    form.mekan_tipi = 'derslik';
    form.gereksinim_tipi = 'zorunlu';
  }
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
        title: props.ders.isim,
        href: `/ders-mekan-gereksinimleri/${props.ders.id}/edit`,
    },
];

const submit = () => {
  form.put(`/ders-mekan-gereksinimleri/${props.ders.id}`);
};
</script>

<template>
  <Head title="Mekan Gereksinimi Düzenle" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-semibold">{{ ders.isim }}</h1>
          <p class="mt-1 text-sm text-muted-foreground">
            {{ ders.ders_kodu }} • Mekan gereksinimi ayarları
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
          
          <!-- Gereksinim Durumu Toggle -->
          <div class="flex items-center justify-between rounded-lg border p-4 bg-muted/20">
            <div>
              <h3 class="font-medium">Mekan Gereksinimi</h3>
              <p class="text-sm text-muted-foreground">Bu ders için özel bir mekan gereksinimi var mı?</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
              <input type="checkbox" v-model="hasRequirement" class="sr-only peer">
              <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
            </label>
          </div>

          <!-- Gereksinim Detayları -->
          <div v-if="hasRequirement" class="space-y-6 animate-in fade-in slide-in-from-top-2 duration-300">
            <!-- Mekan Tipi -->
            <div class="space-y-3">
              <label class="text-sm font-medium">Mekan Tipi</label>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <label class="cursor-pointer">
                  <input type="radio" v-model="form.mekan_tipi" value="derslik" class="sr-only peer" />
                  <div class="rounded-lg border-2 p-4 text-center hover:bg-muted peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                    <div class="font-medium">Derslik</div>
                    <div class="text-xs text-muted-foreground mt-1">Standart sınıf</div>
                  </div>
                </label>
                <label class="cursor-pointer">
                  <input type="radio" v-model="form.mekan_tipi" value="laboratuvar" class="sr-only peer" />
                  <div class="rounded-lg border-2 p-4 text-center hover:bg-muted peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                    <div class="font-medium">Laboratuvar</div>
                    <div class="text-xs text-muted-foreground mt-1">Bilgisayar/Fen</div>
                  </div>
                </label>
                <label class="cursor-pointer">
                  <input type="radio" v-model="form.mekan_tipi" value="konferans_salonu" class="sr-only peer" />
                  <div class="rounded-lg border-2 p-4 text-center hover:bg-muted peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                    <div class="font-medium">Konferans</div>
                    <div class="text-xs text-muted-foreground mt-1">Büyük salon</div>
                  </div>
                </label>
              </div>
              <p v-if="form.errors.mekan_tipi" class="text-sm text-destructive">
                {{ form.errors.mekan_tipi }}
              </p>
            </div>

            <!-- Gereksinim Tipi -->
            <div class="space-y-3">
              <label class="text-sm font-medium">Gereksinim Tipi</label>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <label class="cursor-pointer">
                  <input type="radio" v-model="form.gereksinim_tipi" value="zorunlu" class="sr-only peer" />
                  <div class="rounded-lg border-2 p-4 hover:bg-muted peer-checked:border-red-500 peer-checked:bg-red-50 transition-all">
                    <div class="flex items-center gap-2 font-medium text-red-700">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                      Zorunlu
                    </div>
                    <div class="text-xs text-muted-foreground mt-1">Mutlaka bu mekan tipinde yapılmalı</div>
                  </div>
                </label>
                <label class="cursor-pointer">
                  <input type="radio" v-model="form.gereksinim_tipi" value="olabilir" class="sr-only peer" />
                  <div class="rounded-lg border-2 p-4 hover:bg-muted peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                    <div class="flex items-center gap-2 font-medium text-blue-700">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                      Tercih Edilen
                    </div>
                    <div class="text-xs text-muted-foreground mt-1">Mümkünse bu mekan tipi olsun</div>
                  </div>
                </label>
              </div>
              <p v-if="form.errors.gereksinim_tipi" class="text-sm text-destructive">
                {{ form.errors.gereksinim_tipi }}
              </p>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex items-center justify-end gap-3 pt-4 border-t">
            <Link
              href="/ders-mekan-gereksinimleri"
              class="inline-flex items-center gap-2 rounded-lg border bg-card px-4 py-2 text-sm font-medium hover:bg-accent"
            >
              İptal
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="inline-flex items-center gap-2 rounded-lg bg-primary px-6 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg v-if="form.processing" class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              <span v-else>💾 Kaydet</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>