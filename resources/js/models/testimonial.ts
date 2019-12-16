export default class Testimonial {
    id!: number;
    name!: string;
    email!: string;
    body!: string;
    created_at!: Date;
    updated_at!: Date;
    published_at?: Date | null;
}
