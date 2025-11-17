<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps<{ rows: any[]; file: string }>();

const selected = ref<number[]>([]);
const form = useForm({ file: '', selected: [] });

// initialize form
form.file = props.file;

const toggleSelectAll = () => {
    if (selected.value.length === props.rows.length) {
        selected.value = [];
    } else {
        selected.value = props.rows.map((_, i) => i);
    }
    form.selected = selected.value;
};

const importSelected = () => {
    if (selected.value.length === 0) return;
    form.selected = selected.value;
    form.post('/mekanlar/import/selected', {
        onSuccess: () => {
            // noop
        },
    });
};
</script>

<template>
    <Head title="Mekanlar - Import Önizleme" />

    <AppLayout>
        <div class="p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold">Excel Önizleme</h2>
                <div class="flex items-center gap-2">
                    <button @click="toggleSelectAll" class="rounded border px-3 py-1">Toggle Seç</button>
                    <button @click="importSelected" class="rounded bg-primary px-3 py-1 text-white">Seçilenleri İçe Aktar</button>
                    <Link href="/mekanlar" class="rounded border px-3 py-1">Geri</Link>
                </div>
            </div>

            <form @submit.prevent>
                <input type="hidden" name="file" :value="props.file" />
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-muted/50">
                            <th class="px-3 py-2"></th>
                            <th class="px-3 py-2">İsim</th>
                            <th class="px-3 py-2">Kapasite</th>
                            <th class="px-3 py-2">Mekan Tipi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, index) in props.rows" :key="index" class="border-b">
                            <td class="px-3 py-2">
                                <input type="checkbox" :value="index" v-model="selected" />
                            </td>
                            <td class="px-3 py-2">{{ row[0] }}</td>
                            <td class="px-3 py-2">{{ row[1] }}</td>
                            <td class="px-3 py-2">{{ row[2] }}</td>
                        </tr>
                    </tbody>
                </table>
                <input type="hidden" name="selected" :value="selected" />
            </form>
        </div>
    </AppLayout>
</template>
