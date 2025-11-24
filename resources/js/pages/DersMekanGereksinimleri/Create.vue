<script setup lang="ts">
import { onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

// Sayfa yüklendiğinde ders-mekan-gereksinimleri index sayfasına yönlendir
onMounted(() => {
    router.visit('/ders-mekan-gereksinimleri');
});
</script>

<template>
    <!-- Boş bir şablon, kullanıcı hemen yönlendirilecek -->
    <div>Yönlendiriliyorsunuz...</div>
</template>