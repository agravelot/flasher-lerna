export default class Testimonial {
    id!: number;
    name!: string;
    email!: string;
    body?: string | undefined;
    created_at!: Date;
    updated_at?: Date | null;
    published_at!: Date | null;
}
