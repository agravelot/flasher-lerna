CREATE TABLE public.articles (
    id bigint NOT NULL,
    slug text,
    name text,
    meta_description text,
    content text,
    author_uuid text,
    published_at timestamp with time zone,
    created_at timestamp with time zone,
    updated_at timestamp with time zone,
    deleted_at timestamp with time zone
);


CREATE SEQUENCE public.articles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



ALTER SEQUENCE public.articles_id_seq OWNED BY public.articles.id;
ALTER TABLE ONLY public.articles ALTER COLUMN id SET DEFAULT nextval('public.articles_id_seq'::regclass);

ALTER TABLE ONLY public.articles
    ADD CONSTRAINT articles_pkey PRIMARY KEY (id);
CREATE INDEX idx_articles_deleted_at ON public.articles USING btree (deleted_at);
CREATE UNIQUE INDEX idx_articles_slug ON public.articles USING btree (slug);

CREATE TABLE public.album_category (
    id integer NOT NULL,
    album_id integer NOT NULL,
    category_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);

CREATE SEQUENCE public.album_category_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.album_category_id_seq OWNED BY public.album_category.id;

CREATE TABLE public.album_cosplayer (
    id integer NOT NULL,
    album_id integer,
    cosplayer_id integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);

CREATE SEQUENCE public.album_cosplayer_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.album_cosplayer_id_seq OWNED BY public.album_cosplayer.id;


CREATE TABLE public.albums (
    id integer NOT NULL,
    slug character varying(255) NOT NULL,
    title character varying(255) NOT NULL,
    body text,
    published_at timestamp(0) without time zone,
    private boolean DEFAULT true NOT NULL,
    user_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    notify_users_on_published boolean DEFAULT true NOT NULL,
    meta_description character varying(255) NOT NULL,
    sso_id uuid
);

CREATE SEQUENCE public.albums_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.albums_id_seq OWNED BY public.albums.id;


CREATE TABLE public.categories (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    meta_description character varying(155) NOT NULL
);


CREATE SEQUENCE public.categories_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;


CREATE TABLE public.categorizables (
    category_id integer NOT NULL,
    categorizable_type character varying(255) NOT NULL,
    categorizable_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


CREATE TABLE public.comments (
    id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    user_id bigint,
    commentable_id integer NOT NULL,
    commentable_type character varying(255) NOT NULL,
    body text NOT NULL,
    sso_id uuid NOT NULL
);



CREATE SEQUENCE public.comments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



ALTER SEQUENCE public.comments_id_seq OWNED BY public.comments.id;
--

CREATE TABLE public.contacts (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    message text NOT NULL,
    user_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    sso_id uuid
);


CREATE SEQUENCE public.contacts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



ALTER SEQUENCE public.contacts_id_seq OWNED BY public.contacts.id;

--
-- Name: cosplayers
--

CREATE TABLE public.cosplayers (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    description text,
    picture character varying(255),
    user_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    sso_id uuid
);


CREATE SEQUENCE public.cosplayers_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



ALTER SEQUENCE public.cosplayers_id_seq OWNED BY public.cosplayers.id;


CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: testimonials; Type: TABLE; Schema: public; Owner: admin
--

CREATE TABLE public.testimonials (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    body text NOT NULL,
    published_at timestamp(0) without time zone,
    user_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    sso_id uuid
);


CREATE SEQUENCE public.golden_book_posts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



ALTER SEQUENCE public.golden_book_posts_id_seq OWNED BY public.testimonials.id;


CREATE TABLE public.invitations (
    old_id bigint NOT NULL,
    cosplayer_id bigint NOT NULL,
    email character varying(255) NOT NULL,
    message text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    token character varying(255) NOT NULL,
    confirmed_at timestamp(0) without time zone,
    id uuid NOT NULL
);

CREATE SEQUENCE public.invitations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.invitations_id_seq OWNED BY public.invitations.old_id;



CREATE TABLE public.media (
    id integer NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL,
    collection_name character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    file_name character varying(255) NOT NULL,
    mime_type character varying(255),
    disk character varying(255) NOT NULL,
    size bigint NOT NULL,
    manipulations json NOT NULL,
    custom_properties json NOT NULL,
    responsive_images json NOT NULL,
    order_column integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    uuid uuid,
    conversions_disk character varying(255)
);


CREATE SEQUENCE public.media_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


CREATE TABLE public.pages (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


CREATE SEQUENCE public.pages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pages_id_seq OWNED BY public.pages.id;


CREATE TABLE public.posts (
    id integer NOT NULL,
    title character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    seo_title character varying(255),
    body text NOT NULL,
    active boolean DEFAULT false NOT NULL,
    user_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    sso_id uuid NOT NULL
);



CREATE SEQUENCE public.posts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



ALTER SEQUENCE public.posts_id_seq OWNED BY public.posts.id;


CREATE TABLE public.settings (
    name character varying(30) NOT NULL,
    value text,
    title character varying(255) NOT NULL,
    description character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    type character varying(255) NOT NULL,
    id bigint NOT NULL
);


CREATE SEQUENCE public.settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.settings_id_seq OWNED BY public.settings.id;


CREATE TABLE public.social_media (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    url character varying(255) NOT NULL,
    icon character varying(255) NOT NULL,
    color character varying(255) NOT NULL,
    active boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


CREATE SEQUENCE public.social_media_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.social_media_id_seq OWNED BY public.social_media.id;


CREATE TABLE public.users (
    id integer DEFAULT nextval('public.users_id_seq'::regclass) NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    role character varying(255) DEFAULT 'user'::character varying NOT NULL,
    email_verified_at timestamp(0) without time zone,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT users_role_check CHECK (((role)::text = ANY (ARRAY[('user'::character varying)::text, ('redac'::character varying)::text, ('admin'::character varying)::text])))
);



ALTER TABLE ONLY public.album_category ALTER COLUMN id SET DEFAULT nextval('public.album_category_id_seq'::regclass);

ALTER TABLE ONLY public.album_cosplayer ALTER COLUMN id SET DEFAULT nextval('public.album_cosplayer_id_seq'::regclass);

ALTER TABLE ONLY public.albums ALTER COLUMN id SET DEFAULT nextval('public.albums_id_seq'::regclass);


ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);


ALTER TABLE ONLY public.comments ALTER COLUMN id SET DEFAULT nextval('public.comments_id_seq'::regclass);


ALTER TABLE ONLY public.contacts ALTER COLUMN id SET DEFAULT nextval('public.contacts_id_seq'::regclass);


ALTER TABLE ONLY public.cosplayers ALTER COLUMN id SET DEFAULT nextval('public.cosplayers_id_seq'::regclass);


ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


ALTER TABLE ONLY public.invitations ALTER COLUMN old_id SET DEFAULT nextval('public.invitations_id_seq'::regclass);


ALTER TABLE ONLY public.media ALTER COLUMN id SET DEFAULT nextval('public.media_id_seq'::regclass);


ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


ALTER TABLE ONLY public.pages ALTER COLUMN id SET DEFAULT nextval('public.pages_id_seq'::regclass);


ALTER TABLE ONLY public.posts ALTER COLUMN id SET DEFAULT nextval('public.posts_id_seq'::regclass);


ALTER TABLE ONLY public.settings ALTER COLUMN id SET DEFAULT nextval('public.settings_id_seq'::regclass);



ALTER TABLE ONLY public.social_media ALTER COLUMN id SET DEFAULT nextval('public.social_media_id_seq'::regclass);



ALTER TABLE ONLY public.testimonials ALTER COLUMN id SET DEFAULT nextval('public.golden_book_posts_id_seq'::regclass);


--
-- Name: album_category album_category_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.album_category
    ADD CONSTRAINT album_category_pkey PRIMARY KEY (id);


--
-- Name: album_cosplayer album_cosplayer_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.album_cosplayer
    ADD CONSTRAINT album_cosplayer_pkey PRIMARY KEY (id);


--
-- Name: albums albums_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.albums
    ADD CONSTRAINT albums_pkey PRIMARY KEY (id);


--
-- Name: albums albums_slug_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.albums
    ADD CONSTRAINT albums_slug_unique UNIQUE (slug);


--
-- Name: albums albums_title_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.albums
    ADD CONSTRAINT albums_title_unique UNIQUE (title);


--
-- Name: categories categories_name_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_name_unique UNIQUE (name);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: categories categories_slug_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_slug_unique UNIQUE (slug);


--
-- Name: categorizables categorizables_category_id_categorizable_type_categorizable_id_; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.categorizables
    ADD CONSTRAINT categorizables_category_id_categorizable_type_categorizable_id_ UNIQUE (category_id, categorizable_type, categorizable_id);


--
-- Name: comments comments_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (id);


--
-- Name: contacts contacts_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.contacts
    ADD CONSTRAINT contacts_pkey PRIMARY KEY (id);


--
-- Name: cosplayers cosplayers_name_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.cosplayers
    ADD CONSTRAINT cosplayers_name_unique UNIQUE (name);


--
-- Name: cosplayers cosplayers_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.cosplayers
    ADD CONSTRAINT cosplayers_pkey PRIMARY KEY (id);


--
-- Name: cosplayers cosplayers_slug_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.cosplayers
    ADD CONSTRAINT cosplayers_slug_unique UNIQUE (slug);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: testimonials golden_book_posts_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.testimonials
    ADD CONSTRAINT golden_book_posts_pkey PRIMARY KEY (id);


--
-- Name: invitations invitations_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.invitations
    ADD CONSTRAINT invitations_pkey PRIMARY KEY (id);


--
-- Name: media media_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.media
    ADD CONSTRAINT media_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: pages pages_name_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.pages
    ADD CONSTRAINT pages_name_unique UNIQUE (name);


--
-- Name: pages pages_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.pages
    ADD CONSTRAINT pages_pkey PRIMARY KEY (id);


--
-- Name: pages pages_title_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.pages
    ADD CONSTRAINT pages_title_unique UNIQUE (title);


--
-- Name: posts posts_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.posts
    ADD CONSTRAINT posts_pkey PRIMARY KEY (id);


--
-- Name: posts posts_slug_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.posts
    ADD CONSTRAINT posts_slug_unique UNIQUE (slug);


--
-- Name: settings settings_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.settings
    ADD CONSTRAINT settings_pkey PRIMARY KEY (id);


--
-- Name: social_media social_media_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.social_media
    ADD CONSTRAINT social_media_pkey PRIMARY KEY (id);


--
-- Name: telescope_entries telescope_entries_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.telescope_entries
    ADD CONSTRAINT telescope_entries_pkey PRIMARY KEY (sequence);


--
-- Name: telescope_entries telescope_entries_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.telescope_entries
    ADD CONSTRAINT telescope_entries_uuid_unique UNIQUE (uuid);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_name_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_name_unique UNIQUE (name);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: albums_published_at_private_index; Type: INDEX; Schema: public; Owner: admin
--

CREATE INDEX albums_published_at_private_index ON public.albums USING btree (published_at, private);


--
-- Name: categorizables_categorizable_type_categorizable_id_index; Type: INDEX; Schema: public; Owner: admin
--

CREATE INDEX categorizables_categorizable_type_categorizable_id_index ON public.categorizables USING btree (categorizable_type, categorizable_id);


--
-- Name: golden_book_posts_published_at_index; Type: INDEX; Schema: public; Owner: admin
--

CREATE INDEX golden_book_posts_published_at_index ON public.testimonials USING btree (published_at);


--
-- Name: invitations_email_index; Type: INDEX; Schema: public; Owner: admin
--

CREATE INDEX invitations_email_index ON public.invitations USING btree (email);


--
-- Name: media_model_type_model_id_index; Type: INDEX; Schema: public; Owner: admin
--

CREATE INDEX media_model_type_model_id_index ON public.media USING btree (model_type, model_id);


--
-- Name: settings_name_index; Type: INDEX; Schema: public; Owner: admin
--

CREATE INDEX settings_name_index ON public.settings USING btree (name);


--
-- Name: social_media_active_index; Type: INDEX; Schema: public; Owner: admin
--

CREATE INDEX social_media_active_index ON public.social_media USING btree (active);

--
-- Name: album_category album_category_album_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.album_category
    ADD CONSTRAINT album_category_album_id_foreign FOREIGN KEY (album_id) REFERENCES public.albums(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: album_category album_category_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.album_category
    ADD CONSTRAINT album_category_category_id_foreign FOREIGN KEY (category_id) REFERENCES public.categories(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: album_cosplayer album_cosplayer_album_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.album_cosplayer
    ADD CONSTRAINT album_cosplayer_album_id_foreign FOREIGN KEY (album_id) REFERENCES public.albums(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: album_cosplayer album_cosplayer_cosplayer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.album_cosplayer
    ADD CONSTRAINT album_cosplayer_cosplayer_id_foreign FOREIGN KEY (cosplayer_id) REFERENCES public.cosplayers(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: categorizables categorizables_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY public.categorizables
    ADD CONSTRAINT categorizables_category_id_foreign FOREIGN KEY (category_id) REFERENCES public.categories(id) ON UPDATE CASCADE ON DELETE CASCADE;



-- Dummy schema to query for table names in sqlc 
CREATE SCHEMA IF NOT EXISTS information_schema;
CREATE TABLE IF NOT EXISTS information_schema.tables (
    table_name character varying(255) NOT NULL
);
