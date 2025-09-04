<template>
    <Head :title="`Lead: ${lead.name}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-2 sm:p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-3 sm:p-5 md:min-h-min dark:border-sidebar-border">
                <PlaceholderPattern class="pointer-events-none" />

                <div class="relative z-10 grid gap-6 rounded-md p-4">
                    <!-- Header Section -->
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-foreground">Lead Details</h2>
                            <p class="text-muted-foreground">View detailed information about this lead</p>
                        </div>

                        <div class="flex flex-col gap-2 sm:flex-row sm:gap-2">
                            <Button
                                variant="outline"
                                size="lg"
                                @click="() => router.visit(`/leads/${lead.id}/edit`)"
                                class="w-full cursor-pointer sm:w-auto"
                            >
                                <Edit class="mr-2 h-4 w-4" />
                                Edit Lead
                            </Button>
                            <Button variant="outline" size="lg" @click="() => router.visit('/leads')" class="w-full cursor-pointer sm:w-auto">
                                <ArrowLeft class="mr-2 h-4 w-4" />
                                Back to List
                            </Button>
                        </div>
                    </div>

                    <!-- Lead Information Card -->
                    <Card class="w-full">
                        <CardHeader>
                            <CardTitle class="flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                                    <Users class="h-6 w-6 text-primary" />
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">{{ lead.name }}</h3>
                                    <p class="text-sm text-muted-foreground">{{ lead.email }}</p>
                                </div>
                                <div class="ml-auto">
                                    <span :class="getStatusBadgeClass(lead.status)">
                                        {{ getStatusLabel(lead.status) }}
                                    </span>
                                </div>
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="grid gap-6 md:grid-cols-2">
                                <!-- Contact Information -->
                                <div class="space-y-4">
                                    <div>
                                        <Label class="text-sm font-medium text-muted-foreground">Full Name</Label>
                                        <p class="mt-1 text-lg font-semibold">{{ lead.name }}</p>
                                    </div>
                                    <div>
                                        <Label class="text-sm font-medium text-muted-foreground">Email Address</Label>
                                        <p class="mt-1">
                                            <a :href="`mailto:${lead.email}`" class="text-blue-600 hover:underline">
                                                {{ lead.email }}
                                            </a>
                                        </p>
                                    </div>
                                    <div>
                                        <Label class="text-sm font-medium text-muted-foreground">Phone Number</Label>
                                        <p class="mt-1">
                                            <a :href="`tel:${lead.phone_number}`" class="text-blue-600 hover:underline">
                                                {{ lead.phone_number }}
                                            </a>
                                        </p>
                                    </div>
                                    <div>
                                        <Label class="text-sm font-medium text-muted-foreground">Address</Label>
                                        <p class="mt-1">{{ lead.address || 'No address provided' }}</p>
                                    </div>
                                </div>

                                <!-- Lead Information -->
                                <div class="space-y-4">
                                    <div>
                                        <Label class="text-sm font-medium text-muted-foreground">Status</Label>
                                        <p class="mt-1">
                                            <span :class="getStatusBadgeClass(lead.status)">
                                                {{ getStatusLabel(lead.status) }}
                                            </span>
                                        </p>
                                    </div>
                                    <div>
                                        <Label class="text-sm font-medium text-muted-foreground">Needs</Label>
                                        <p class="mt-1">{{ lead.needs || 'No specific needs mentioned' }}</p>
                                    </div>
                                    <div v-if="lead.converted_at">
                                        <Label class="text-sm font-medium text-muted-foreground">Converted Date</Label>
                                        <p class="mt-1">{{ formatDate(lead.converted_at) }}</p>
                                    </div>
                                    <div v-if="lead.converted_to_customer_id">
                                        <Label class="text-sm font-medium text-muted-foreground">Customer ID</Label>
                                        <p class="mt-1 font-mono">#{{ lead.converted_to_customer_id }}</p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Quick Stats -->
                    <div class="grid gap-4 md:grid-cols-3">
                        <Card>
                            <CardContent class="p-4">
                                <div class="flex items-center gap-3">
                                    <div :class="getStatusIconStyle(lead.status)">
                                        <component :is="getStatusIcon(lead.status)" class="h-5 w-5" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-muted-foreground">Current Status</p>
                                        <p class="text-lg font-semibold">{{ getStatusLabel(lead.status) }}</p>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardContent class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/20">
                                        <Mail class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-muted-foreground">Email</p>
                                        <p class="max-w-32 truncate text-sm font-semibold">{{ lead.email }}</p>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardContent class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/20">
                                        <Phone class="h-5 w-5 text-green-600 dark:text-green-400" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-muted-foreground">Phone</p>
                                        <p class="text-sm font-semibold">{{ lead.phone_number }}</p>
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
import { BreadcrumbItem } from '@/types';
import { Lead } from '@/types/lead';
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeft, CheckCircle, Clock, Edit, Mail, Phone, UserCheck, Users, XCircle } from 'lucide-vue-next';

const props = defineProps<{
    lead: Lead;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Leads',
        href: '/leads',
    },
    {
        title: props.lead.name,
        href: `/leads/${props.lead.id}`,
    },
];

// Helper functions for status display
const getStatusLabel = (status: string) => {
    const statusLabels: Record<string, string> = {
        new: 'New',
        contacted: 'Contacted',
        qualified: 'Qualified',
        lost: 'Lost',
        converted: 'Converted',
    };
    return statusLabels[status] || status;
};

const getStatusBadgeClass = (status: string) => {
    const baseClass = 'inline-flex items-center rounded-md px-5 py-1 text-sm font-medium';
    const classes: Record<string, string> = {
        new: `${baseClass} bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100`,
        contacted: `${baseClass} bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100`,
        qualified: `${baseClass} bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100`,
        lost: `${baseClass} bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100`,
        converted: `${baseClass} bg-emerald-100 text-emerald-800 dark:bg-emerald-800 dark:text-emerald-100`,
    };
    return classes[status] || classes.new;
};

const getStatusIcon = (status: string) => {
    const icons: Record<string, any> = {
        new: Clock,
        contacted: Phone,
        qualified: UserCheck,
        lost: XCircle,
        converted: CheckCircle,
    };
    return icons[status] || Clock;
};

const getStatusIconStyle = (status: string) => {
    const baseClass = 'flex h-10 w-10 items-center justify-center rounded-lg';
    const styles: Record<string, string> = {
        new: `${baseClass} bg-gray-100 dark:bg-gray-900/20 text-gray-600 dark:text-gray-400`,
        contacted: `${baseClass} bg-blue-100 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400`,
        qualified: `${baseClass} bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400`,
        lost: `${baseClass} bg-red-100 dark:bg-red-900/20 text-red-600 dark:text-red-400`,
        converted: `${baseClass} bg-emerald-100 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400`,
    };
    return styles[status] || styles.new;
};

const formatDate = (date: Date | string) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>

<style scoped></style>
