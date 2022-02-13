--
-- PostgreSQL database dump
--

-- Dumped from database version 14.1
-- Dumped by pg_dump version 14.1

SET
statement_timeout = 0;
SET
lock_timeout = 0;
SET
idle_in_transaction_session_timeout = 0;
SET
client_encoding = 'UTF8';
SET
standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET
check_function_bodies = false;
SET
xmloption = content;
SET
client_min_messages = warning;
SET
row_security = off;

SET
default_tablespace = '';

SET
default_table_access_method = heap;

--
-- Name: pet; Type: TABLE; Schema: public; Owner: demo
--

CREATE TABLE public.pet
(
    id         uuid                   NOT NULL,
    name       character varying(255) NOT NULL,
    type       character varying(255) NOT NULL,
    birthdate  timestamp without time zone NOT NULL,
    owner_id   uuid                   NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone DEFAULT now() NOT NULL,
    created_by uuid,
    updated_by uuid
);


ALTER TABLE public.pet OWNER TO demo;

--
-- Name: user; Type: TABLE; Schema: public; Owner: demo
--

CREATE TABLE public."user"
(
    id         uuid                   NOT NULL,
    email      character varying(255) NOT NULL,
    name_first character varying(255),
    name_last  character varying(255),
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone DEFAULT now() NOT NULL,
    created_by uuid,
    updated_by uuid
);


ALTER TABLE public."user" OWNER TO demo;

--
-- Data for Name: pet; Type: TABLE DATA; Schema: public; Owner: demo
--

INSERT INTO public.pet
VALUES ('7881003a-5d55-4e8b-a6cc-5718fe70b1ee', 'Rex', 'dog', '2015-05-15 00:00:00',
        'a5b14bac-4c41-440d-b1f7-13c7bd90af6c', '2022-02-13 11:16:14.33178', '2022-02-13 11:16:14.33178',
        'a5b14bac-4c41-440d-b1f7-13c7bd90af6c', 'a5b14bac-4c41-440d-b1f7-13c7bd90af6c');
INSERT INTO public.pet
VALUES ('19233c35-7859-4646-8416-8b033cb2529f', 'Jacques', 'cat', '2018-08-22 00:00:00',
        'a5b14bac-4c41-440d-b1f7-13c7bd90af6c', '2022-02-13 11:16:14.33178', '2022-02-13 11:16:14.33178',
        'a5b14bac-4c41-440d-b1f7-13c7bd90af6c', 'a5b14bac-4c41-440d-b1f7-13c7bd90af6c');
INSERT INTO public.pet
VALUES ('c26ebc1e-2282-450a-b6aa-eaadf2ed885d', 'Ben', 'hamster', '2021-10-03 00:00:00',
        'a5b14bac-4c41-440d-b1f7-13c7bd90af6c', '2022-02-13 11:16:14.33178', '2022-02-13 11:16:14.33178',
        'a5b14bac-4c41-440d-b1f7-13c7bd90af6c', 'a5b14bac-4c41-440d-b1f7-13c7bd90af6c');


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: demo
--

INSERT INTO public."user"
VALUES ('a5b14bac-4c41-440d-b1f7-13c7bd90af6c', 'admin@demoapp.local', 'Admin', NULL, '2022-02-13 11:03:46.272499',
        '2022-02-13 11:03:46.272499', NULL, NULL);


--
-- Name: pet pet_pk; Type: CONSTRAINT; Schema: public; Owner: demo
--

ALTER TABLE ONLY public.pet
    ADD CONSTRAINT pet_pk PRIMARY KEY (id);


--
-- Name: user user_pk; Type: CONSTRAINT; Schema: public; Owner: demo
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pk PRIMARY KEY (id);


--
-- Name: pet_birthdate_index; Type: INDEX; Schema: public; Owner: demo
--

CREATE INDEX pet_birthdate_index ON public.pet USING btree (birthdate);


--
-- Name: pet_created_at_index; Type: INDEX; Schema: public; Owner: demo
--

CREATE INDEX pet_created_at_index ON public.pet USING btree (created_at);


--
-- Name: pet_created_by_index; Type: INDEX; Schema: public; Owner: demo
--

CREATE INDEX pet_created_by_index ON public.pet USING btree (created_by);


--
-- Name: pet_name_index; Type: INDEX; Schema: public; Owner: demo
--

CREATE INDEX pet_name_index ON public.pet USING btree (name);


--
-- Name: pet_owner_id_index; Type: INDEX; Schema: public; Owner: demo
--

CREATE INDEX pet_owner_id_index ON public.pet USING btree (owner_id);


--
-- Name: pet_type_index; Type: INDEX; Schema: public; Owner: demo
--

CREATE INDEX pet_type_index ON public.pet USING btree (type);


--
-- Name: pet_updated_at_index; Type: INDEX; Schema: public; Owner: demo
--

CREATE INDEX pet_updated_at_index ON public.pet USING btree (updated_at);


--
-- Name: pet_updated_by_index; Type: INDEX; Schema: public; Owner: demo
--

CREATE INDEX pet_updated_by_index ON public.pet USING btree (updated_by);


--
-- Name: user_created_at_index; Type: INDEX; Schema: public; Owner: demo
--

CREATE INDEX user_created_at_index ON public."user" USING btree (created_at);


--
-- Name: user_created_by_index; Type: INDEX; Schema: public; Owner: demo
--

CREATE INDEX user_created_by_index ON public."user" USING btree (created_by);


--
-- Name: user_email_index; Type: INDEX; Schema: public; Owner: demo
--

CREATE INDEX user_email_index ON public."user" USING btree (email);


--
-- Name: user_updated_at_index; Type: INDEX; Schema: public; Owner: demo
--

CREATE INDEX user_updated_at_index ON public."user" USING btree (updated_at);


--
-- Name: user_updated_by_index; Type: INDEX; Schema: public; Owner: demo
--

CREATE INDEX user_updated_by_index ON public."user" USING btree (updated_by);


--
-- Name: pet pet_created_by_fk; Type: FK CONSTRAINT; Schema: public; Owner: demo
--

ALTER TABLE ONLY public.pet
    ADD CONSTRAINT pet_created_by_fk FOREIGN KEY (created_by) REFERENCES public."user"(id) ON
DELETE
SET DEFAULT;


--
-- Name: pet pet_owner_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: demo
--

ALTER TABLE ONLY public.pet
    ADD CONSTRAINT pet_owner_id_fk FOREIGN KEY (owner_id) REFERENCES public."user"(id) ON
UPDATE CASCADE
ON
DELETE CASCADE;


--
-- Name: pet pet_updated_by_fk; Type: FK CONSTRAINT; Schema: public; Owner: demo
--

ALTER TABLE ONLY public.pet
    ADD CONSTRAINT pet_updated_by_fk FOREIGN KEY (updated_by) REFERENCES public."user"(id) ON
DELETE
SET DEFAULT;


--
-- Name: user user_created_by_fk; Type: FK CONSTRAINT; Schema: public; Owner: demo
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_created_by_fk FOREIGN KEY (created_by) REFERENCES public."user"(id) ON
DELETE
SET DEFAULT;


--
-- Name: user user_updated_by_fk; Type: FK CONSTRAINT; Schema: public; Owner: demo
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_updated_by_fk FOREIGN KEY (updated_by) REFERENCES public."user"(id) ON
DELETE
SET DEFAULT;


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: demo
--

REVOKE ALL ON SCHEMA public FROM postgres;
REVOKE ALL ON SCHEMA public FROM PUBLIC;
GRANT
ALL
ON SCHEMA public TO demo;
GRANT ALL
ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

