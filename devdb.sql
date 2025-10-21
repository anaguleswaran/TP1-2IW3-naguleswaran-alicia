--
-- PostgreSQL database dump
--

\restrict BgzNaalDU29sryKzTrAvHvhh1aEGtRgixDrL9ztB8AZS76dqur0cHfjUTPYAtuX

-- Dumped from database version 15.14
-- Dumped by pg_dump version 15.14 (Debian 15.14-0+deb12u1)

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

SET default_table_access_method = heap;

--
-- Name: user; Type: TABLE; Schema: public; Owner: devuser
--

CREATE TABLE public."user" (
    id integer NOT NULL,
    firstname character varying(30),
    lastname character varying(30),
    email character varying(50),
    password text
);


ALTER TABLE public."user" OWNER TO devuser;

--
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: devuser
--

CREATE SEQUENCE public.user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_id_seq OWNER TO devuser;

--
-- Name: user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: devuser
--

ALTER SEQUENCE public.user_id_seq OWNED BY public."user".id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: devuser
--

CREATE TABLE public.users (
    id integer NOT NULL,
    firstname character varying(30),
    lastname character varying(30),
    email character varying(50),
    password text
);


ALTER TABLE public.users OWNER TO devuser;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: devuser
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO devuser;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: devuser
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: user id; Type: DEFAULT; Schema: public; Owner: devuser
--

ALTER TABLE ONLY public."user" ALTER COLUMN id SET DEFAULT nextval('public.user_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: devuser
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: devuser
--

COPY public."user" (id, firstname, lastname, email, password) FROM stdin;
1	Jean	\N	aaaa@aaa.com	$2y$10$iJkiMF0XwFt7Z8taUMkcsO9CivbreY1vBDoDu.mff7TopwGrRUavS
2	\N	\N	aze@aaaa.com	$2y$10$6S64/MwOhz7kWicJqSWfXeDRo6F4TihU/wNYbPGuaJbnLOYn3ZK.u
3	Jean	Dupont	aaaa@aaaa.com	$2y$10$qhjDF.1Klj8UXywpHHBn5ux.G/9BEiW4Ymt/c5.WV5aT5aeZnST1u
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: devuser
--

COPY public.users (id, firstname, lastname, email, password) FROM stdin;
\.


--
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devuser
--

SELECT pg_catalog.setval('public.user_id_seq', 3, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devuser
--

SELECT pg_catalog.setval('public.users_id_seq', 1, false);


--
-- Name: user user_pkey; Type: CONSTRAINT; Schema: public; Owner: devuser
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: devuser
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- PostgreSQL database dump complete
--

\unrestrict BgzNaalDU29sryKzTrAvHvhh1aEGtRgixDrL9ztB8AZS76dqur0cHfjUTPYAtuX

