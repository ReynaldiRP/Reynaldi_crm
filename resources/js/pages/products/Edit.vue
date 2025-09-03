<template>
    <Head title="Products" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-2 sm:p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-3 sm:p-5 md:min-h-min dark:border-sidebar-border">
                <PlaceholderPattern class="pointer-events-none" />
                <form @submit.prevent="submit" class="grid gap-6 rounded-md p-4">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-foreground">Edit Product</h2>
                        <p class="text-muted-foreground">Update product information</p>
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
                            v-model="form.sale_price"
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

                    <div class="grid gap-2">
                        <Label for="active">Product Status</Label>
                        <RadioButtonGroup v-model="form.active" class="flex flex-wrap gap-4">
                            <div class="flex items-center gap-2">
                                <RadioButton :value="true" inputId="radio-active" name="status" />
                                <Label for="radio-active">Active</Label>
                            </div>
                            <div class="flex items-center gap-2">
                                <RadioButton :value="false" inputId="radio-inactive" name="status" />
                                <Label for="radio-inactive">Inactive</Label>
                            </div>
                        </RadioButtonGroup>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-md bg-green-600 px-4 py-2 text-white transition-colors hover:bg-green-700 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Updating...' : 'Update Product' }}
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
import { update } from '@/routes/products';
import { BreadcrumbItem } from '@/types';
import { Product } from '@/types/products';
import { Head, useForm } from '@inertiajs/vue3';
import { InputNumber, InputText, RadioButton, RadioButtonGroup, useToast } from 'primevue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '/products',
    },
    {
        title: 'Edit',
        href: '/products/edit',
    },
];

const toast = useToast();

const props = defineProps<{
    product: Product;
}>();

const form = useForm({
    id: props.product.id,
    code: props.product.code,
    name: props.product.name,
    description: props.product.description,
    cost_price: props.product.cost_price,
    margin_percent: props.product.margin_percent,
    sale_price: props.product.sale_price,
    active: props.product.active,
});

const submit = () => {
    form.put(update.url(form.id), {
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Product updated successfully',
                life: 3000,
            });
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Failed to update product',
                life: 3000,
            });
        },
    });
};
</script>

<style scoped></style>
