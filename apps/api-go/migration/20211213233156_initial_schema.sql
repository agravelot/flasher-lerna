-- +goose Up
CREATE TABLE articles (
    id bigint NOT NULL,
    slug text NOT NULL,
    name text NOT NULL,
    meta_description text NOT NULL,
    content text NOT NULL,
    author_uuid text NOT NULL,
    published_at timestamp with time zone,
    created_at timestamp with time zone NOT NULL,
    updated_at timestamp with time zone NOT NULL,
    deleted_at timestamp with time zone
);


CREATE SEQUENCE articles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



ALTER SEQUENCE articles_id_seq OWNED BY articles.id;
ALTER TABLE ONLY articles ALTER COLUMN id SET DEFAULT nextval('articles_id_seq'::regclass);

ALTER TABLE ONLY articles
    ADD CONSTRAINT articles_pkey PRIMARY KEY (id);
CREATE INDEX idx_articles_deleted_at ON articles USING btree (deleted_at);
CREATE UNIQUE INDEX idx_articles_slug ON articles USING btree (slug);

CREATE TABLE album_category (
    id integer NOT NULL,
    album_id integer NOT NULL,
    category_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);

CREATE SEQUENCE album_category_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE album_category_id_seq OWNED BY album_category.id;

CREATE TABLE album_cosplayer (
    id integer NOT NULL,
    album_id integer,
    cosplayer_id integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);

CREATE SEQUENCE album_cosplayer_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE album_cosplayer_id_seq OWNED BY album_cosplayer.id;


CREATE TABLE albums (
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

CREATE SEQUENCE albums_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE albums_id_seq OWNED BY albums.id;


CREATE TABLE categories (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    meta_description character varying(155) NOT NULL
);


CREATE SEQUENCE categories_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE categories_id_seq OWNED BY categories.id;


CREATE TABLE categorizables (
    category_id integer NOT NULL,
    categorizable_type character varying(255) NOT NULL,
    categorizable_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


CREATE TABLE comments (
    id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    user_id bigint,
    commentable_id integer NOT NULL,
    commentable_type character varying(255) NOT NULL,
    body text NOT NULL,
    sso_id uuid NOT NULL
);



CREATE SEQUENCE comments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



ALTER SEQUENCE comments_id_seq OWNED BY comments.id;
--

CREATE TABLE contacts (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    message text NOT NULL,
    user_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    sso_id uuid
);


CREATE SEQUENCE contacts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



ALTER SEQUENCE contacts_id_seq OWNED BY contacts.id;

--
-- Name: cosplayers
--

CREATE TABLE cosplayers (
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


CREATE SEQUENCE cosplayers_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



ALTER SEQUENCE cosplayers_id_seq OWNED BY cosplayers.id;


CREATE TABLE failed_jobs (
    id bigint NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


CREATE SEQUENCE failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



ALTER SEQUENCE failed_jobs_id_seq OWNED BY failed_jobs.id;


--
-- Name: testimonials; Type: TABLE; Schema: public; Owner: admin
--

CREATE TABLE testimonials (
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


CREATE SEQUENCE golden_book_posts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



ALTER SEQUENCE golden_book_posts_id_seq OWNED BY testimonials.id;


CREATE TABLE invitations (
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

CREATE SEQUENCE invitations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE invitations_id_seq OWNED BY invitations.old_id;



CREATE TABLE media (
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


CREATE SEQUENCE media_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


CREATE TABLE migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


CREATE SEQUENCE migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE migrations_id_seq OWNED BY migrations.id;


CREATE TABLE pages (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


CREATE SEQUENCE pages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE pages_id_seq OWNED BY pages.id;


CREATE TABLE posts (
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



CREATE SEQUENCE posts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



ALTER SEQUENCE posts_id_seq OWNED BY posts.id;


CREATE TABLE settings (
    name character varying(30) NOT NULL,
    value text,
    title character varying(255) NOT NULL,
    description character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    type character varying(255) NOT NULL,
    id bigint NOT NULL
);


CREATE SEQUENCE settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE settings_id_seq OWNED BY settings.id;


CREATE TABLE social_media (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    url character varying(255) NOT NULL,
    icon character varying(255) NOT NULL,
    color character varying(255) NOT NULL,
    active boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


CREATE SEQUENCE social_media_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE social_media_id_seq OWNED BY social_media.id;


CREATE TABLE users (
    id integer NOT NULL,
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


CREATE SEQUENCE users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE users_id_seq OWNED BY users.id;


ALTER TABLE ONLY album_category ALTER COLUMN id SET DEFAULT nextval('album_category_id_seq'::regclass);

ALTER TABLE ONLY album_cosplayer ALTER COLUMN id SET DEFAULT nextval('album_cosplayer_id_seq'::regclass);

ALTER TABLE ONLY albums ALTER COLUMN id SET DEFAULT nextval('albums_id_seq'::regclass);


ALTER TABLE ONLY categories ALTER COLUMN id SET DEFAULT nextval('categories_id_seq'::regclass);


ALTER TABLE ONLY comments ALTER COLUMN id SET DEFAULT nextval('comments_id_seq'::regclass);


ALTER TABLE ONLY contacts ALTER COLUMN id SET DEFAULT nextval('contacts_id_seq'::regclass);


ALTER TABLE ONLY cosplayers ALTER COLUMN id SET DEFAULT nextval('cosplayers_id_seq'::regclass);


ALTER TABLE ONLY failed_jobs ALTER COLUMN id SET DEFAULT nextval('failed_jobs_id_seq'::regclass);


ALTER TABLE ONLY invitations ALTER COLUMN old_id SET DEFAULT nextval('invitations_id_seq'::regclass);


ALTER TABLE ONLY media ALTER COLUMN id SET DEFAULT nextval('media_id_seq'::regclass);


ALTER TABLE ONLY migrations ALTER COLUMN id SET DEFAULT nextval('migrations_id_seq'::regclass);


ALTER TABLE ONLY pages ALTER COLUMN id SET DEFAULT nextval('pages_id_seq'::regclass);


ALTER TABLE ONLY posts ALTER COLUMN id SET DEFAULT nextval('posts_id_seq'::regclass);


ALTER TABLE ONLY settings ALTER COLUMN id SET DEFAULT nextval('settings_id_seq'::regclass);



ALTER TABLE ONLY social_media ALTER COLUMN id SET DEFAULT nextval('social_media_id_seq'::regclass);



ALTER TABLE ONLY testimonials ALTER COLUMN id SET DEFAULT nextval('golden_book_posts_id_seq'::regclass);


--
-- Name: album_category album_category_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY album_category
    ADD CONSTRAINT album_category_pkey PRIMARY KEY (id);


--
-- Name: album_cosplayer album_cosplayer_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY album_cosplayer
    ADD CONSTRAINT album_cosplayer_pkey PRIMARY KEY (id);


--
-- Name: albums albums_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY albums
    ADD CONSTRAINT albums_pkey PRIMARY KEY (id);


--
-- Name: albums albums_slug_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY albums
    ADD CONSTRAINT albums_slug_unique UNIQUE (slug);


--
-- Name: albums albums_title_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY albums
    ADD CONSTRAINT albums_title_unique UNIQUE (title);


--
-- Name: categories categories_name_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY categories
    ADD CONSTRAINT categories_name_unique UNIQUE (name);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: categories categories_slug_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY categories
    ADD CONSTRAINT categories_slug_unique UNIQUE (slug);


--
-- Name: categorizables categorizables_category_id_categorizable_type_categorizable_id_; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY categorizables
    ADD CONSTRAINT categorizables_category_id_categorizable_type_categorizable_id_ UNIQUE (category_id, categorizable_type, categorizable_id);


--
-- Name: comments comments_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (id);


--
-- Name: contacts contacts_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY contacts
    ADD CONSTRAINT contacts_pkey PRIMARY KEY (id);


--
-- Name: cosplayers cosplayers_name_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY cosplayers
    ADD CONSTRAINT cosplayers_name_unique UNIQUE (name);


--
-- Name: cosplayers cosplayers_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY cosplayers
    ADD CONSTRAINT cosplayers_pkey PRIMARY KEY (id);


--
-- Name: cosplayers cosplayers_slug_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY cosplayers
    ADD CONSTRAINT cosplayers_slug_unique UNIQUE (slug);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: testimonials golden_book_posts_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY testimonials
    ADD CONSTRAINT golden_book_posts_pkey PRIMARY KEY (id);


--
-- Name: invitations invitations_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY invitations
    ADD CONSTRAINT invitations_pkey PRIMARY KEY (id);


--
-- Name: media media_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY media
    ADD CONSTRAINT media_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: pages pages_name_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY pages
    ADD CONSTRAINT pages_name_unique UNIQUE (name);


--
-- Name: pages pages_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY pages
    ADD CONSTRAINT pages_pkey PRIMARY KEY (id);


--
-- Name: pages pages_title_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY pages
    ADD CONSTRAINT pages_title_unique UNIQUE (title);


--
-- Name: posts posts_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY posts
    ADD CONSTRAINT posts_pkey PRIMARY KEY (id);


--
-- Name: posts posts_slug_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY posts
    ADD CONSTRAINT posts_slug_unique UNIQUE (slug);


--
-- Name: settings settings_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY settings
    ADD CONSTRAINT settings_pkey PRIMARY KEY (id);


--
-- Name: social_media social_media_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY social_media
    ADD CONSTRAINT social_media_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_name_unique; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_name_unique UNIQUE (name);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: albums_published_at_private_index; Type: INDEX; Schema: public; Owner: admin
--

CREATE INDEX albums_published_at_private_index ON albums USING btree (published_at, private);


--
-- Name: categorizables_categorizable_type_categorizable_id_index; Type: INDEX; Schema: public; Owner: admin
--

CREATE INDEX categorizables_categorizable_type_categorizable_id_index ON categorizables USING btree (categorizable_type, categorizable_id);


--
-- Name: golden_book_posts_published_at_index; Type: INDEX; Schema: public; Owner: admin
--

CREATE INDEX golden_book_posts_published_at_index ON testimonials USING btree (published_at);


--
-- Name: invitations_email_index; Type: INDEX; Schema: public; Owner: admin
--

CREATE INDEX invitations_email_index ON invitations USING btree (email);


--
-- Name: media_model_type_model_id_index; Type: INDEX; Schema: public; Owner: admin
--

CREATE INDEX media_model_type_model_id_index ON media USING btree (model_type, model_id);


--
-- Name: settings_name_index; Type: INDEX; Schema: public; Owner: admin
--

CREATE INDEX settings_name_index ON settings USING btree (name);


--
-- Name: social_media_active_index; Type: INDEX; Schema: public; Owner: admin
--

CREATE INDEX social_media_active_index ON social_media USING btree (active);

--
-- Name: album_category album_category_album_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY album_category
    ADD CONSTRAINT album_category_album_id_foreign FOREIGN KEY (album_id) REFERENCES albums(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: album_category album_category_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY album_category
    ADD CONSTRAINT album_category_category_id_foreign FOREIGN KEY (category_id) REFERENCES categories(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: album_cosplayer album_cosplayer_album_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY album_cosplayer
    ADD CONSTRAINT album_cosplayer_album_id_foreign FOREIGN KEY (album_id) REFERENCES albums(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: album_cosplayer album_cosplayer_cosplayer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY album_cosplayer
    ADD CONSTRAINT album_cosplayer_cosplayer_id_foreign FOREIGN KEY (cosplayer_id) REFERENCES cosplayers(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: categorizables categorizables_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: admin
--

ALTER TABLE ONLY categorizables
    ADD CONSTRAINT categorizables_category_id_foreign FOREIGN KEY (category_id) REFERENCES categories(id) ON UPDATE CASCADE ON DELETE CASCADE;




-- +goose Down
-- DROP TABLE IF EXISTS goose_db_version;
DROP TABLE IF EXISTS album_cosplayer;
DROP TABLE IF EXISTS categorizables;
DROP TABLE IF EXISTS album_category;
DROP TABLE IF EXISTS testimonials;
DROP TABLE IF EXISTS social_media;
DROP TABLE IF EXISTS failed_jobs;
DROP TABLE IF EXISTS invitations;
DROP TABLE IF EXISTS migrations;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS cosplayers;
DROP TABLE IF EXISTS contacts;
DROP TABLE IF EXISTS settings;
DROP TABLE IF EXISTS articles;
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS albums;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS media;
DROP TABLE IF EXISTS pages;
DROP TABLE IF EXISTS users;
DROP SEQUENCE IF EXISTS media_id_seq;