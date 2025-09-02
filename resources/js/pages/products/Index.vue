<template>
    <Head title="Products" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-2 sm:p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-3 sm:p-5 md:min-h-min dark:border-sidebar-border">
                <PlaceholderPattern class="pointer-events-none" />
                <Button variant="default" size="lg" @click="() => router.visit('/products/create')" class="mb-4 w-full cursor-pointer sm:w-auto">
                    Add Products
                </Button>

                <DataTable
                    tableStyle="min-width: 50rem"
                    scrollDirection="horizontal"
                    :scrollHeight="'400px'"
                    v-model:filters="filters"
                    :value="products"
                    showGridlines
                    dataKey="id"
                    ref="dt"
                    :paginator="true"
                    :rows="5"
                    class="mt-4"
                    :global-filter-fields="['name']"
                >
                    <template #header>
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <Button variant="outline" size="lg" class="w-full cursor-pointer sm:w-auto" @click="clearFilter">
                                <FilterX />
                                Clear
                            </Button>
                            <div class="flex flex-col gap-2 sm:flex-row sm:gap-2">
                                <IconField class="w-full sm:w-auto">
                                    <InputIcon>
                                        <Search :size="18" />
                                    </InputIcon>
                                    <InputText
                                        v-model="filters['global'].value"
                                        placeholder="Keyword Search"
                                        class="w-full min-w-[200px] sm:w-auto"
                                    />
                                </IconField>
                                <Button variant="secondary" size="lg" class="w-full cursor-pointer sm:w-auto" @click="exportCSV">
                                    <ExternalLink />
                                    Export
                                </Button>
                            </div>
                        </div>
                    </template>
                    <template #empty> No Customers found. </template>

                    <Column header="No.">
                        <template #body="slotProps">
                            {{ slotProps.index + 1 }}
                        </template>
                    </Column>
                    <Column field="code" sortable header="Product Code">
                        <template #body="{ data }">
                            {{ data.code }}
                        </template>
                    </Column>
                    <Column field="name" sortable header="Product Name">
                        <template #body="{ data }">
                            {{ data.name }}
                        </template>
                    </Column>
                    <Column field="description" sortable header="Product Description">
                        <template #body="{ data }">
                            {{ data.description }}
                        </template>
                    </Column>
                    <Column field="cost_price" sortable header="Product Cost Price">
                        <template #body="{ data }">
                            {{ formatCurrency(data.cost_price) }}
                        </template>
                    </Column>
                    <Column field="margin_percent" sortable header="Product Margin Percent">
                        <template #body="{ data }">
                            {{ data.margin_percent }}
                        </template>
                    </Column>
                    <Column field="sale_price" sortable header="Product Sale Price">
                        <template #body="{ data }">
                            {{ formatCurrency(data.sale_price) }}
                        </template>
                    </Column>
                    <Column field="active" sortable header="Product Active">
                        <template #body="{ data }">
                            {{ status(data.active) }}
                        </template>
                    </Column>
                    <Column header="Actions">
                        <template #body="{ data }">
                            <div class="flex flex-col gap-2 sm:flex-row sm:gap-2">
                                <Button variant="secondary" size="sm" @click="() => editHandler(data.id)" class="w-full cursor-pointer sm:w-auto">
                                    Edit
                                </Button>
                                <Button
                                    variant="destructive"
                                    size="sm"
                                    class="w-full cursor-pointer sm:w-auto"
                                    @click="() => openDeleteModal(data.id)"
                                >
                                    Delete
                                </Button>
                                <Button variant="default" size="sm" class="w-full cursor-pointer sm:w-auto" @click="() => viewHandler(data.id)">
                                    Detail
                                </Button>
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>
            <Dialog v-model:visible="showModals" modal header="Delete Customer">
                <div class="flex flex-col gap-4">
                    <p>Are you sure you want to delete this customer?</p>
                    <Button variant="destructive" size="lg" @click="() => deleteHandler(selectedProductId!)" class="cursor-pointer">Confirm</Button>
                    <Button variant="secondary" size="lg" @click="closeDeleteModal">Cancel</Button>
                </div>
            </Dialog>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatCurrency, status } from '@/lib/utils';
import { destroy, edit, show } from '@/routes/products';
import { BreadcrumbItem } from '@/types';
import { Product } from '@/types/products';
import { Head, router } from '@inertiajs/vue3';
import { FilterMatchMode } from '@primevue/core/api';
import { ExternalLink, FilterX, Search } from 'lucide-vue-next';
import { Column, DataTable, IconField, InputIcon, InputText, useToast, Dialog } from 'primevue';
import { ref } from 'vue';

const props = defineProps<{
    products: Product[];
}>();

const toast = useToast();

const products = ref<Product[]>(props.products);
const selectedProductId = ref<number | null>(null);
const showModals = ref(false);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '/products',
    },
];

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const dt = ref<typeof DataTable | null>(null);

const clearFilter = () => {
    filters.value.global.value = null;
};

const exportCSV = () => {
    if (dt.value) {
        (dt.value as any).exportCSV();
    }
};

const editHandler = (productId: number) => {
    router.visit(edit.url({ product: productId }));
};

const openDeleteModal = (productId: number) => {
    selectedProductId.value = productId;
    showModals.value = true;
};

const closeDeleteModal = () => {
    showModals.value = false;
    selectedProductId.value = null;
};

const deleteHandler = async (productId: number) => {
    console.log('Deleting product with ID:', productId);
    router.delete(destroy.url({ product: productId }), {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Product deleted successfully.',
                life: 3000,
            });

            products.value = products.value.filter((product) => product.id !== productId);
        },
        onError: (errors) => {
            console.error('Error deleting customer:', errors);
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Failed to delete product.',
                life: 3000,
            });
        },
        onFinish: () => {
            showModals.value = false;
        },
    });
};

const viewHandler = (productId: number) => {
    router.visit(show.url({ product: productId }));
};
</script>

<style scoped></style>
