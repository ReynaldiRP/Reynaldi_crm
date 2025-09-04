<template>
    <Head title="Products" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-2 sm:p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-3 sm:p-5 md:min-h-min dark:border-sidebar-border">
                <PlaceholderPattern class="pointer-events-none" />
                <Button variant="default" size="lg" @click="() => router.visit(create.url())" class="mb-4 w-full cursor-pointer sm:w-auto">
                    Add Lead
                </Button>

                <DataTable
                    tableStyle="min-width: 50rem"
                    scrollDirection="horizontal"
                    :scrollHeight="'400px'"
                    v-model:filters="filters"
                    :value="leads"
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
                    <template #empty> No Leads found. </template>

                    <Column header="No.">
                        <template #body="slotProps">
                            {{ slotProps.index + 1 }}
                        </template>
                    </Column>
                    <Column field="name" sortable header="Lead Name">
                        <template #body="{ data }">
                            {{ data.name }}
                        </template>
                    </Column>
                    <Column field="phone_number" sortable header="Phone">
                        <template #body="{ data }">
                            {{ data.phone_number }}
                        </template>
                    </Column>
                    <Column field="email" sortable header="Email">
                        <template #body="{ data }">
                            {{ data.email }}
                        </template>
                    </Column>
                    <Column field="address" sortable header="Address">
                        <template #body="{ data }">
                            {{ data.address }}
                        </template>
                    </Column>
                    <Column field="needs" sortable header="Needs">
                        <template #body="{ data }">
                            {{ data.needs }}
                        </template>
                    </Column>
                    <Column field="status" sortable header="Status">
                        <template #body="{ data }">
                            {{ data.status }}
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
            <Dialog v-model:visible="showModals" modal header="Delete Lead">
                <div class="flex flex-col gap-4">
                    <p>Are you sure you want to delete this lead?</p>
                    <Button variant="destructive" size="lg" @click="() => deleteHandler(selectedLeadId!)" class="cursor-pointer">Confirm</Button>
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
import { create, destroy, edit, show } from '@/routes/leads';
import { BreadcrumbItem } from '@/types';
import { Lead } from '@/types/lead';
import { Head, router } from '@inertiajs/vue3';
import { FilterMatchMode } from '@primevue/core/api';
import { ExternalLink, FilterX, Search } from 'lucide-vue-next';
import { Column, DataTable, Dialog, IconField, InputIcon, InputText, useToast } from 'primevue';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Leads',
        href: '/leads',
    },
];

const props = defineProps<{
    leads: Lead[];
}>();

const leads = ref(props.leads);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const dt = ref<typeof DataTable | null>(null);
const selectedLeadId = ref<number | null>(null);
const showModals = ref(false);
const toast = useToast();

const clearFilter = () => {
    filters.value.global.value = null;
};

const exportCSV = () => {
    if (dt.value) {
        (dt.value as any).exportCSV();
    }
};

const editHandler = (leadId: number) => {
    router.visit(edit.url({ lead: leadId }));
};

const openDeleteModal = (leadId: number) => {
    selectedLeadId.value = leadId;
    showModals.value = true;
};

const closeDeleteModal = () => {
    showModals.value = false;
    selectedLeadId.value = null;
};

const deleteHandler = async (leadId: number) => {
    router.delete(destroy.url({ lead: leadId }), {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Product deleted successfully.',
                life: 3000,
            });

            leads.value = leads.value.filter((lead) => lead.id !== leadId);
        },
        onError: (errors) => {
            console.error(errors);
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

const viewHandler = (leadId: number) => {
    router.visit(show.url({ lead: leadId }));
};
</script>

<style scoped></style>
