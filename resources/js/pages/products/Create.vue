<template>
    <Head title="Products" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-2 sm:p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-3 sm:p-5 md:min-h-min dark:border-sidebar-border">
                <PlaceholderPattern class="pointer-events-none" />
                <form @submit.prevent="submit" class="grid gap-6 rounded-md p-4">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-foreground">Create Product</h2>
                        <p class="text-muted-foreground">Create new product information</p>
                    </div>

                    <div class="grid gap-2">
                        <Label for="product-code">Product Code</Label>
                        <InputText id="product-code" type="text" required autofocus :tabindex="1" v-model="form.code" placeholder="Product Code" />
                        <InputError :message="form.errors.code" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="product-name">Product Name</Label>
                        <InputText id="product-name" type="text" required :tabindex="2" v-model="form.name" placeholder="Product Name" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="description">Product Description</Label>
                        <InputText
                            id="description"
                            type="text"
                            :tabindex="3"
                            v-model="form.description"
                            placeholder="Product Description (Optional)"
                        />
                        <InputError :message="form.errors.description" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="cost_price">Cost Price (IDR)</Label>
                        <InputNumber
                            id="cost_price"
                            v-model="form.cost_price"
                            :min="0"
                            mode="currency"
                            currency="IDR"
                            locale="id-ID"
                            :minFractionDigits="0"
                            :maxFractionDigits="0"
                            placeholder="Price"
                            :tabindex="4"
                        />
                        <InputError :message="form.errors.cost_price" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="margin_percent">Margin Percent</Label>
                        <InputNumber
                            id="margin_percent"
                            v-model="form.margin_percent"
                            :min="0"
                            mode="decimal"
                            :minFractionDigits="0"
                            :maxFractionDigits="2"
                            placeholder="Margin Percent"
                            :tabindex="5"
                            suffix="%"
                        />
                        <InputError :message="form.errors.margin_percent" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="sale_price">Sale Price (IDR)</Label>
                        <InputNumber
                            id="sale_price"
                            v-model="sumSalePrice"
                            :min="0"
                            mode="currency"
                            currency="IDR"
                            locale="id-ID"
                            :minFractionDigits="0"
                            :maxFractionDigits="0"
                            placeholder="Price"
                            :tabindex="6"
                            disabled
                        />
                        <InputError :message="form.errors.sale_price" />
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-md bg-green-600 px-4 py-2 text-white transition-colors hover:bg-green-700 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Creating...' : 'Create Product' }}
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { store } from '@/routes/products';
import { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { InputNumber, InputText, useToast } from 'primevue';
import { computed, ComputedRef } from 'vue';

const toast = useToast();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '/products',
    },
    {
        title: 'Create',
        href: '/products/create',
    },
];

const sumSalePrice: ComputedRef<number> = computed(() => {
    return form.cost_price + (form.cost_price * form.margin_percent) / 100;
});

const form = useForm({
    code: '',
    name: '',
    description: '',
    cost_price: 0,
    margin_percent: 0,
    sale_price: 0,
    active: true,
});

const submit = () => {
    form.sale_price = sumSalePrice.value;

    form.post(store.url(), {
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Product created successfully',
                life: 3000,
            });
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Failed to create product',
                life: 3000,
            });
        },
    });
};
</script>

<style scoped></style>
