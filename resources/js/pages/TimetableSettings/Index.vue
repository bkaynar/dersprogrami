<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface TimetableSetting {
    id: number;
    min_block_size: number;
    max_block_size: number;
    enforce_consecutive: boolean;
    split_rules: Record<string, number[]>;
}

const props = defineProps<{
    setting: TimetableSetting;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Anasayfa',
        href: dashboard().url,
    },
    {
        title: 'Ders Programı Ayarları',
        href: '/timetable-settings',
    },
];

// Form
const form = useForm({
    min_block_size: props.setting.min_block_size,
    max_block_size: props.setting.max_block_size,
    enforce_consecutive: props.setting.enforce_consecutive,
    split_rules: props.setting.split_rules,
});

// Split rules için yeni kural ekleme
const newRuleHours = ref<number>(4);
const newRuleBlocks = ref<string>('2,2');

const addSplitRule = () => {
    const blocks = newRuleBlocks.value.split(',').map(b => parseInt(b.trim())).filter(b => !isNaN(b));
    if (blocks.length > 0) {
        form.split_rules[newRuleHours.value.toString()] = blocks;
        newRuleHours.value = 4;
        newRuleBlocks.value = '2,2';
    }
};

const removeSplitRule = (hours: string) => {
    delete form.split_rules[hours];
};

const submit = () => {
    form.put('/timetable-settings', {
        preserveScroll: true,
    });
};

// Flash messages
const page = usePage<any>();
const flashSuccess = computed(() => page.props.flash?.success || page.props.success);
const flashError = computed(() => page.props.flash?.error || page.props.error);
</script>

<template>
    <Head title="Ders Programı Ayarları" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Ders Programı Ayarları
                </h2>
            </div>
        </template>

        <!-- Flash Messages -->
        <div v-if="flashSuccess" class="mb-4 rounded-lg bg-green-50 p-4 text-green-800">
            {{ flashSuccess }}
        </div>

        <div v-if="flashError" class="mb-4 rounded-lg bg-red-50 p-4 text-red-800">
            {{ flashError }}
        </div>

        <!-- Settings Form -->
        <div class="bg-white shadow-sm sm:rounded-lg">
            <form @submit.prevent="submit" class="p-6 space-y-6">
                <!-- Basic Settings -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Min Block Size -->
                    <div>
                        <label for="min_block_size" class="block text-sm font-medium text-gray-700 mb-2">
                            Minimum Blok Süresi (saat)
                        </label>
                        <input
                            id="min_block_size"
                            v-model.number="form.min_block_size"
                            type="number"
                            min="1"
                            max="6"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        <p class="mt-1 text-xs text-gray-500">
                            Bir bloktaki minimum ders saati sayısı
                        </p>
                    </div>

                    <!-- Max Block Size -->
                    <div>
                        <label for="max_block_size" class="block text-sm font-medium text-gray-700 mb-2">
                            Maksimum Blok Süresi (saat)
                        </label>
                        <input
                            id="max_block_size"
                            v-model.number="form.max_block_size"
                            type="number"
                            min="1"
                            max="6"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        <p class="mt-1 text-xs text-gray-500">
                            Bir bloktaki maksimum ders saati sayısı
                        </p>
                    </div>
                </div>

                <!-- Enforce Consecutive -->
                <div class="flex items-center">
                    <input
                        id="enforce_consecutive"
                        v-model="form.enforce_consecutive"
                        type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    />
                    <label for="enforce_consecutive" class="ml-2 block text-sm text-gray-700">
                        Blokların arka arkaya olması zorunlu
                    </label>
                </div>

                <!-- Split Rules -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Haftalık Saat Dağılım Kuralları</h3>

                    <!-- Existing Rules -->
                    <div class="space-y-2 mb-4">
                        <div
                            v-for="(blocks, hours) in form.split_rules"
                            :key="hours"
                            class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                        >
                            <div>
                                <span class="font-medium">{{ hours }} saatlik ders</span>
                                <span class="mx-2">→</span>
                                <span class="text-indigo-600">{{ blocks.join(' + ') }} saat bloklar</span>
                            </div>
                            <button
                                type="button"
                                @click="removeSplitRule(hours)"
                                class="text-red-600 hover:text-red-800"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Add New Rule -->
                    <div class="flex gap-3 items-end">
                        <div class="flex-1">
                            <label for="new_rule_hours" class="block text-sm font-medium text-gray-700 mb-1">
                                Haftalık Saat
                            </label>
                            <input
                                id="new_rule_hours"
                                v-model.number="newRuleHours"
                                type="number"
                                min="1"
                                max="10"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>
                        <div class="flex-1">
                            <label for="new_rule_blocks" class="block text-sm font-medium text-gray-700 mb-1">
                                Bloklar (virgülle ayırın)
                            </label>
                            <input
                                id="new_rule_blocks"
                                v-model="newRuleBlocks"
                                type="text"
                                placeholder="Örn: 3,2"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>
                        <button
                            type="button"
                            @click="addSplitRule"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                        >
                            Ekle
                        </button>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        Örnek: 5 saatlik dersin 3+2 şeklinde bölünmesi için "5" yazın ve bloklar kısmına "3,2" girin
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Kaydediliyor...' : 'Kaydet' }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
