<template>
    <Head :title="`Product: ${product.name}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-2 sm:p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-3 sm:p-5 md:min-h-min dark:border-sidebar-border">
                <PlaceholderPattern class="pointer-events-none" />

                <div class="relative z-10 grid gap-6 rounded-md p-4">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-foreground">Product Details</h2>
                            <p class="text-muted-foreground">View detailed information about this product</p>
                        </div>

                        <div class="flex flex-col gap-2 sm:flex-row sm:gap-2">
                            <Button
                                variant="outline"
                                size="lg"
                                @click="() => router.visit(edit.url({ product: product.id }))"
                                class="w-full cursor-pointer sm:w-auto"
                            >
                                <Edit class="mr-2 h-4 w-4" />
                                Edit Product
                            </Button>
                            <Button variant="outline" size="lg" @click="() => router.visit('/products')" class="w-full cursor-pointer sm:w-auto">
                                <ArrowLeft class="mr-2 h-4 w-4" />
                                Back to List
                            </Button>
                        </div>
                    </div>

                    <Card class="w-full">
                        <CardHeader>
                            <CardTitle class="flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                                    <Package class="h-6 w-6 text-primary" />
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">{{ product.name }}</h3>
                                    <p class="text-sm text-muted-foreground">Code: {{ product.code }}</p>
                                </div>
                                <div class="ml-auto">
                                    <Badge :severity="product.active ? 'success' : 'danger'" size="xlarge">
                                        {{ status(product.active) }}
                                    </Badge>
                                </div>
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="space-y-4">
                                    <div>
                                        <Label class="text-sm font-medium text-muted-foreground">Product Code</Label>
                                        <p class="mt-1 font-mono text-sm">{{ product.code }}</p>
                                    </div>
                                    <div>
                                        <Label class="text-sm font-medium text-muted-foreground">Product Name</Label>
                                        <p class="mt-1">{{ product.name }}</p>
                                    </div>
                                    <div>
                                        <Label class="text-sm font-medium text-muted-foreground">Description</Label>
                                        <p class="mt-1">{{ product.description || 'No description available' }}</p>
                                    </div>
                                    <div>
                                        <Label class="text-sm font-medium text-muted-foreground">Status</Label>
                                        <p class="mt-1">{{ status(product.active) }}</p>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <Label class="text-sm font-medium text-muted-foreground">Cost Price</Label>
                                        <p class="mt-1 text-lg font-semibold">{{ formatCurrency(product.cost_price) }}</p>
                                    </div>
                                    <div>
                                        <Label class="text-sm font-medium text-muted-foreground">Margin Percent</Label>
                                        <p class="mt-1 text-lg font-semibold text-blue-600">{{ product.margin_percent }}%</p>
                                    </div>
                                    <div>
                                        <Label class="text-sm font-medium text-muted-foreground">Sale Price</Label>
                                        <p class="mt-1 text-xl font-bold text-green-600">{{ formatCurrency(product.sale_price) }}</p>
                                    </div>
                                    <div class="rounded-lg bg-muted/50 p-3">
                                        <Label class="text-xs font-medium text-muted-foreground">Profit Margin</Label>
                                        <p
                                            class="mt-1 text-lg font-semibold"
                                            :class="profitMarginStatusColor(profitMargin(product.cost_price, product.sale_price))"
                                        >
                                            {{ formatCurrency(profitMargin(product.cost_price, product.sale_price)) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <div class="grid gap-4 md:grid-cols-3">
                        <Card>
                            <CardContent class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/20">
                                        <DollarSign class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-muted-foreground">Cost Price</p>
                                        <p class="text-lg font-semibold">{{ formatCurrency(product.cost_price) }}</p>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardContent class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/20">
                                        <TrendingUp class="h-5 w-5 text-green-600 dark:text-green-400" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-muted-foreground">Sale Price</p>
                                        <p class="text-lg font-semibold">{{ formatCurrency(product.sale_price) }}</p>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardContent class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/20">
                                        <Percent class="h-5 w-5 text-emerald-600 dark:text-emerald-400" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-muted-foreground">Margin</p>
                                        <p class="text-lg font-semibold">{{ product.margin_percent }}%</p>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import Button from '@/components/ui/button/Button.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatCurrency, profitMargin, status } from '@/lib/utils';
import { edit } from '@/routes/products';
import { BreadcrumbItem } from '@/types';
import { Product } from '@/types/product';
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeft, DollarSign, Edit, Package, Percent, TrendingUp } from 'lucide-vue-next';
import Badge from 'primevue/badge';

const props = defineProps<{
    product: Product;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '/products',
    },
    {
        title: props.product.name,
        href: `/products/${props.product.id}`,
    },
];

const profitMarginStatusColor = (profitMargin: number) => {
    if (profitMargin > 0) return 'text-emerald-600';
    if (profitMargin < 0) return 'text-red-600';
    return 'text-muted-foreground';
};
</script>

<style scoped></style>
