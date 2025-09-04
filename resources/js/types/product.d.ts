export interface Product {
    id: number;
    code: string;
    name: string;
    description: string;
    cost_price: number;
    margin_percent: number;
    sale_price: number;
    active: boolean;
}

export interface ProductForm {
    product_code: string;
    name: string;
    cost_price: number;
    margin_percent: number;
    sale_price: number;
}