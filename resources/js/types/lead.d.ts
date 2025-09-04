export interface Lead {
    id: number;
    user_id: number;
    name: string;
    phone_number: string;
    email: string;
    address: string;
    needs?: string;
    status: 'new' | 'contacted' | 'qualified' | 'lost' | 'converted';
    converted_at?: Date;
    converted_to_customer_id?: number;
}

export interface LeadForm {
    user_id: number;
    name: string;
    phone_number: string;
    email: string;
    address: string;
    needs?: string;
    status: 'new' | 'contacted' | 'qualified' | 'lost' | 'converted';
}