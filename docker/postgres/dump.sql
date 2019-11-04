--
-- PostgreSQL database dump
--

-- Dumped from database version 11.5 (Debian 11.5-3.pgdg90+1)
-- Dumped by pg_dump version 11.5 (Ubuntu 11.5-3.pgdg18.04+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: migrations; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO app;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.migrations_id_seq OWNER TO app;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.users (
    id integer NOT NULL,
    admin boolean DEFAULT false NOT NULL,
    status character varying(255) DEFAULT ''::character varying NOT NULL,
    name character varying(255) DEFAULT ''::character varying NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) DEFAULT ''::character varying NOT NULL,
    api_key character varying(255),
    options json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.users OWNER TO app;

--
-- Name: users_follow; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.users_follow (
    user_id integer NOT NULL,
    follow_user_id integer NOT NULL,
    disable boolean DEFAULT false NOT NULL
);


ALTER TABLE public.users_follow OWNER TO app;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO app;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.migrations (id, migration, batch) FROM stdin;
6	2018_10_14_000000_create_users_table	1
7	2019_11_04_072023_create_users_follow_table	1
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.users (id, admin, status, name, email, password, api_key, options, created_at, updated_at) FROM stdin;
1	t	yellow	Мистер X	admin@evarun.ru	$2y$10$ukPfoahWgmNuX5kDamVR7OpW3N6kKNro726IoODUz.CTuYkVYKPC6	TkRVem4yTERSQTNQRHFxcmo4SUozNWZp	{"a":true,"b":1,"c":"on"}	2019-11-04 09:37:42	2019-11-04 09:37:42
2	f	green	Мистер A	a@evarun.ru	$2y$10$cylIHu918GcYBMS1eFDgxeJq96ZqsNcamzSmMgDVTkBMpocAQORqW	M3GVem4ySWESQTNQRHFxcmo4SUozNWKA	{"a":true,"b":1,"c":"on"}	2019-11-04 09:37:43	2019-11-04 09:37:43
3	f	maroon	Мистер B	b@evarun.ru	$2y$10$kHt6e40RfQjlFfbLAKPrAu025N/w5bk1JSI8T4dmOyTFuYvaZayZS	M3GVem4ySWESQTNQRHFxcmo4SUozNWKB	{"a":true,"b":1,"c":"on"}	2019-11-04 09:37:43	2019-11-04 09:37:43
4	f	aqua	Мистер C	c@evarun.ru	$2y$10$MaG/ZSk0CcNuzhzXug7.teR9fEZfvZF./NMV44nAuTvgiOj59Brxi	M3GVem4ySWESQTNQRHFxcmo4SUozNWKC	{"a":true,"b":1,"c":"on"}	2019-11-04 09:37:43	2019-11-04 09:37:43
\.


--
-- Data for Name: users_follow; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.users_follow (user_id, follow_user_id, disable) FROM stdin;
1	2	f
1	3	f
2	1	f
2	4	f
3	2	f
\.


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.migrations_id_seq', 7, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.users_id_seq', 4, true);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: users users_api_key_unique; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_api_key_unique UNIQUE (api_key);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users_follow users_follow_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.users_follow
    ADD CONSTRAINT users_follow_pkey PRIMARY KEY (user_id, follow_user_id);


--
-- Name: users users_name_unique; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_name_unique UNIQUE (name);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users_follow users_follow_follow_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.users_follow
    ADD CONSTRAINT users_follow_follow_user_id_foreign FOREIGN KEY (follow_user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: users_follow users_follow_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.users_follow
    ADD CONSTRAINT users_follow_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

