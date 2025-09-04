<template>
    <Head title="Create Lead" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-2 sm:p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-3 sm:p-5 md:min-h-min dark:border-sidebar-border">
                <PlaceholderPattern class="pointer-events-none" />
                <form @submit.prevent="submit" class="grid gap-6 rounded-md p-4">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-foreground">Create Lead</h2>
                        <p class="text-muted-foreground">Create new lead information</p>
                    </div>

                    <div class="grid gap-2">
                        <Label for="lead-name">Lead Name</Label>
                        <InputText id="lead-name" type="text" required autofocus :tabindex="1" v-model="form.name" placeholder="Lead Name" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="lead-email">Lead Email</Label>
                        <InputText id="lead-email" type="email" required :tabindex="2" v-model="form.email" placeholder="Lead Email" />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="lead-phone">Lead Phone</Label>
                        <InputText id="lead-phone" type="tel" required :tabindex="3" v-model="form.phone_number" placeholder="Lead Phone" />
                        <InputError :message="form.errors.phone_number" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="lead-address">Lead Address</Label>
                        <InputText id="lead-address" type="text" :tabindex="4" v-model="form.address" placeholder="Lead Address (Optional)" />
                        <InputError :message="form.errors.address" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="lead-needs">Lead Needs</Label>
                        <Textarea id="lead-needs" :tabindex="5" v-model="form.needs" placeholder="Lead Needs (Optional)" />
                        <InputError :message="form.errors.needs" />
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-md bg-green-600 px-4 py-2 text-white transition-colors hover:bg-green-700 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Creating...' : 'Create Lead' }}
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
import { store } from '@/routes/leads';
import { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { InputText, Textarea, useToast } from 'primevue';

const toast = useToast();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Leads',
        href: '/leads',
    },
    {
        title: 'Create',
        href: '/leads/create',
    },
];

const form = useForm({
    name: '',
    email: '',
    phone_number: '',
    address: '',
    needs: '',
});

const submit = () => {
    form.post(store.url(), {
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Leads created successfully',
                life: 3000,
            });
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Failed to create lead',
                life: 3000,
            });

            console.log(form.errors);
        },
    });
};
</script>

<style scoped></style>
