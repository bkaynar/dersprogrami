<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';

interface UstGrup {
  id: number;
  isim: string;
}

defineProps<{
  ustGruplar?: UstGrup[];
}>();

const form = useForm({
  isim: '',
  yil: 1,
  ogrenci_sayisi: 0,
  ust_grup_id: null as number | null,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Anasayfa',
        href: dashboard().url,
    },
    {
        title: 'Öğrenci Grupları',
        href: '/ogrenci-gruplari',
    },
    {
        title: 'Yeni Grup',
        href: '/ogrenci-gruplari/create',
    },
];

const submit = () => {
  form.post('/ogrenci-gruplari');
};
</script>

<template>
  <Head title="Yeni Öğrenci Grubu" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-semibold">Yeni Öğrenci Grubu Oluştur</h1>
          <p class="mt-1 text-sm text-muted-foreground">
            Gerekli bilgileri doldurarak yeni bir öğrenci grubu ekleyin
          </p>
        </div>
        <Link
          href="/ogrenci-gruplari"
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
              Grup Adı
              <span class="text-destructive">*</span>
            </label>
            <input
              id="isim"
              v-model="form.isim"
              type="text"
              placeholder="Örn: Bilgisayar Mühendisliği 1. Sınıf A"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
              :class="{ 'border-destructive': form.errors.isim }"
            />
            <p v-if="form.errors.isim" class="text-sm text-destructive">
              {{ form.errors.isim }}
            </p>
          </div>

          <!-- Yıl -->
          <div class="space-y-2">
            <label for="yil" class="text-sm font-medium">
              Yıl
              <span class="text-destructive">*</span>
            </label>
            <select
              id="yil"
              v-model.number="form.yil"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
              :class="{ 'border-destructive': form.errors.yil }"
            >
              <option :value="1">1. Sınıf</option>
              <option :value="2">2. Sınıf</option>
              <option :value="3">3. Sınıf</option>
              <option :value="4">4. Sınıf</option>
              <option :value="5">5. Sınıf</option>
              <option :value="6">6. Sınıf</option>
            </select>
            <p v-if="form.errors.yil" class="text-sm text-destructive">
              {{ form.errors.yil }}
            </p>
          </div>

          <!-- Öğrenci Sayısı -->
          <div class="space-y-2">
            <label for="ogrenci_sayisi" class="text-sm font-medium">
              Öğrenci Sayısı
              <span class="text-destructive">*</span>
            </label>
            <input
              id="ogrenci_sayisi"
              v-model.number="form.ogrenci_sayisi"
              type="number"
              min="0"
              placeholder="Örn: 30"
              class="flex h-10 w-32 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
              :class="{ 'border-destructive': form.errors.ogrenci_sayisi }"
            />
            <p v-if="form.errors.ogrenci_sayisi" class="text-sm text-destructive">
              {{ form.errors.ogrenci_sayisi }}
            </p>
            <p class="text-xs text-muted-foreground">
              Gruptaki toplam öğrenci sayısını giriniz
            </p>
          </div>

          <!-- Üst Grup (Opsiyonel) -->
          <div class="space-y-2">
            <label for="ust_grup_id" class="text-sm font-medium">
              Üst Grup (Opsiyonel)
            </label>
            <select
              id="ust_grup_id"
              v-model.number="form.ust_grup_id"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
              :class="{ 'border-destructive': form.errors.ust_grup_id }"
            >
              <option :value="null">Üst grup yok</option>
              <option v-for="grup in ustGruplar" :key="grup.id" :value="grup.id">
                {{ grup.isim }}
              </option>
            </select>
            <p v-if="form.errors.ust_grup_id" class="text-sm text-destructive">
              {{ form.errors.ust_grup_id }}
            </p>
            <p class="text-xs text-muted-foreground">
              Bu grup bir ana grubun alt şubesi ise üst grubu seçiniz
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
              {{ form.processing ? 'Oluşturuluyor...' : 'Grubu Oluştur' }}
            </button>
            <Link
              href="/ogrenci-gruplari"
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
