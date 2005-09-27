--
-- PostgreSQL database dump
--

SET client_encoding = 'UNICODE';
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: kaddressbook; Type: SCHEMA; Schema: -; Owner: kodmasin
--

CREATE SCHEMA kaddressbook;


ALTER SCHEMA kaddressbook OWNER TO kodmasin;

SET search_path = kaddressbook, pg_catalog;

--
-- Name: add_company(character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, bigint, text, boolean); Type: FUNCTION; Schema: kaddressbook; Owner: kodmasin
--

CREATE FUNCTION add_company(character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, bigint, text, boolean) RETURNS void
    AS $_$
DECLARE
BEGIN
	INSERT INTO kaddressbook.company (name, address, city, state, zip, country, tel, fax, web, vat_no, user_index, note, private) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13);
	RETURN;
END;
$_$
    LANGUAGE plpgsql;


ALTER FUNCTION kaddressbook.add_company(character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, bigint, text, boolean) OWNER TO kodmasin;

--
-- Name: add_person(character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, text, character varying, bigint, boolean); Type: FUNCTION; Schema: kaddressbook; Owner: kodmasin
--

CREATE FUNCTION add_person(character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, text, character varying, bigint, boolean) RETURNS bigint
    AS $_$
DECLARE
	ret int8;
BEGIN
	SELECT INTO ret nextval FROM nextval('kaddressbook.person_sek');
	INSERT INTO kaddressbook.persons (index, first, middle, last, nickname, jtitle, home, work, fax, mobile, pager, email, addres, city, state, zip, country, notes, web, user_index, private) VALUES(ret, $1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17,$18,$19,$20);
	RETURN ret;
END;
$_$
    LANGUAGE plpgsql;


ALTER FUNCTION kaddressbook.add_person(character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, text, character varying, bigint, boolean) OWNER TO kodmasin;

--
-- Name: user_company(bigint, bigint, bigint); Type: FUNCTION; Schema: kaddressbook; Owner: kodmasin
--

CREATE FUNCTION user_company(bigint, bigint, bigint) RETURNS smallint
    AS $_$
DECLARE
	useri ALIAS FOR $1;
	personi ALIAS FOR $2;
	companyi ALIAS FOR $3;
	ret int2;
	temp int8;
BEGIN
	ret:=0;
	UPDATE kaddressbook.persons SET company=companyi WHERE index=personi;
	RETURN ret;
END;
$_$
    LANGUAGE plpgsql;


ALTER FUNCTION kaddressbook.user_company(bigint, bigint, bigint) OWNER TO kodmasin;

SET default_tablespace = '';

SET default_with_oids = true;

--
-- Name: company; Type: TABLE; Schema: kaddressbook; Owner: kodmasin; Tablespace: 
--

CREATE TABLE company (
    "index" bigint DEFAULT nextval('kaddressbook.company_sek'::text) NOT NULL,
    name character varying(20) NOT NULL,
    address character varying(100),
    city character varying(50),
    state character varying(50),
    zip character varying(15),
    country character varying(100),
    tel character varying(30),
    fax character varying(30),
    web character varying(100),
    user_index bigint,
    private boolean DEFAULT false,
    note text,
    vat_no character varying(30)
);


ALTER TABLE kaddressbook.company OWNER TO kodmasin;

--
-- Name: company_sek; Type: SEQUENCE; Schema: kaddressbook; Owner: kodmasin
--

CREATE SEQUENCE company_sek
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE kaddressbook.company_sek OWNER TO kodmasin;

--
-- Name: person_sek; Type: SEQUENCE; Schema: kaddressbook; Owner: kodmasin
--

CREATE SEQUENCE person_sek
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE kaddressbook.person_sek OWNER TO kodmasin;

--
-- Name: persons; Type: TABLE; Schema: kaddressbook; Owner: kodmasin; Tablespace: 
--

CREATE TABLE persons (
    "index" bigint DEFAULT nextval('kaddressbook.person_sek'::text) NOT NULL,
    "first" character varying(30),
    middle character varying(30),
    "last" character varying(30),
    nickname character varying(15),
    jtitle character varying(20),
    home character varying(20),
    "work" character varying(20),
    fax character varying(20),
    mobile character varying(20),
    pager character varying(20),
    email character varying(50),
    addres character varying(100),
    city character varying(50),
    state character varying(50),
    zip character varying(15),
    country character varying(100),
    company bigint,
    notes text,
    web character varying(100),
    user_index bigint,
    private boolean DEFAULT false
);


ALTER TABLE kaddressbook.persons OWNER TO kodmasin;

--
-- Name: adp_uk; Type: CONSTRAINT; Schema: kaddressbook; Owner: kodmasin; Tablespace: 
--

ALTER TABLE ONLY persons
    ADD CONSTRAINT adp_uk UNIQUE (nickname);


ALTER INDEX kaddressbook.adp_uk OWNER TO kodmasin;

--
-- Name: ka_uk_name; Type: CONSTRAINT; Schema: kaddressbook; Owner: kodmasin; Tablespace: 
--

ALTER TABLE ONLY company
    ADD CONSTRAINT ka_uk_name UNIQUE (name, address, city);


ALTER INDEX kaddressbook.ka_uk_name OWNER TO kodmasin;

--
-- Name: pk_kaddressbookcompany; Type: CONSTRAINT; Schema: kaddressbook; Owner: kodmasin; Tablespace: 
--

ALTER TABLE ONLY company
    ADD CONSTRAINT pk_kaddressbookcompany PRIMARY KEY ("index");


ALTER INDEX kaddressbook.pk_kaddressbookcompany OWNER TO kodmasin;

--
-- Name: pk_kaddressbookpersons; Type: CONSTRAINT; Schema: kaddressbook; Owner: kodmasin; Tablespace: 
--

ALTER TABLE ONLY persons
    ADD CONSTRAINT pk_kaddressbookpersons PRIMARY KEY ("index");


ALTER INDEX kaddressbook.pk_kaddressbookpersons OWNER TO kodmasin;

--
-- Name: uk_p_ema; Type: CONSTRAINT; Schema: kaddressbook; Owner: kodmasin; Tablespace: 
--

ALTER TABLE ONLY persons
    ADD CONSTRAINT uk_p_ema UNIQUE (email);


ALTER INDEX kaddressbook.uk_p_ema OWNER TO kodmasin;

--
-- Name: uk_p_mob; Type: CONSTRAINT; Schema: kaddressbook; Owner: kodmasin; Tablespace: 
--

ALTER TABLE ONLY persons
    ADD CONSTRAINT uk_p_mob UNIQUE (mobile);


ALTER INDEX kaddressbook.uk_p_mob OWNER TO kodmasin;

--
-- Name: adre_fk_comp_per; Type: FK CONSTRAINT; Schema: kaddressbook; Owner: kodmasin
--

ALTER TABLE ONLY persons
    ADD CONSTRAINT adre_fk_comp_per FOREIGN KEY (company) REFERENCES company("index");


--
-- Name: kaddr_co_usr_fk; Type: FK CONSTRAINT; Schema: kaddressbook; Owner: kodmasin
--

ALTER TABLE ONLY company
    ADD CONSTRAINT kaddr_co_usr_fk FOREIGN KEY (user_index) REFERENCES kaute.kaute("index") ON DELETE CASCADE;


--
-- Name: kaddr_per_usr_fk; Type: FK CONSTRAINT; Schema: kaddressbook; Owner: kodmasin
--

ALTER TABLE ONLY persons
    ADD CONSTRAINT kaddr_per_usr_fk FOREIGN KEY (user_index) REFERENCES kaute.kaute("index") ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

