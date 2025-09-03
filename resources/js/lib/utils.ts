import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export const formatCurrency = (value: number): string => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value);
};

export const status = (value: boolean): string => {
    return value ? 'Active' : 'Inactive';
};

export const profitMargin = (costPrice: number, salePrice: number): number => {
    if (costPrice === 0) return 0;
    return ((salePrice - costPrice) / costPrice) * 100;
};
