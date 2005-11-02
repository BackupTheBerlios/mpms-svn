--
-- PostgreSQL database dump
--

SET client_encoding = 'UNICODE';
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: ksmess; Type: SCHEMA; Schema: -; Owner: kodmasin
--

CREATE SCHEMA ksmess;


SET search_path = ksmess, pg_catalog;

--
-- Name: checkm(bigint, smallint); Type: FUNCTION; Schema: ksmess; Owner: kodmasin
--

CREATE FUNCTION checkm(bigint, smallint) RETURNS SETOF character
    AS $_$
DECLARE
	kuser ALIAS FOR $1;
	mtype ALIAS FOR $2;
	ret RECORD;
BEGIN
	IF mtype = 0 THEN
		FOR ret IN SELECT index FROM ksmess.messages WHERE mto=kuser LOOP
			RETURN NEXT ret.index;
		END LOOP;
	ELSE
		FOR ret IN SELECT index FROM ksmess.messages WHERE mto=kuser AND type=mtype LOOP
			RETURN NEXT ret.index;
		END LOOP;
	END IF;
	RETURN;
END;
$_$
    LANGUAGE plpgsql;


--
-- Name: del_mess(character varying); Type: FUNCTION; Schema: ksmess; Owner: kodmasin
--

CREATE FUNCTION del_mess(character varying) RETURNS boolean
    AS $_$
DECLARE
	messid ALIAS FOR $1;
	ret boolean;
BEGIN
	ret := false;
	DELETE FROM ksmess.messages WHERE index=messid;
	IF FOUND THEN
		ret:=true;
	END IF;
	RETURN ret;
END;
$_$
    LANGUAGE plpgsql;


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: attachement; Type: TABLE; Schema: ksmess; Owner: kodmasin; Tablespace: 
--

CREATE TABLE attachement (
    message_index character(41),
    name character varying(250) NOT NULL,
    path character varying(500) NOT NULL,
    "type" character varying(100) NOT NULL
);


--
-- Name: get_attachs(character varying); Type: FUNCTION; Schema: ksmess; Owner: kodmasin
--

CREATE FUNCTION get_attachs(character varying) RETURNS SETOF attachement
    AS $_$
DECLARE
	messid ALIAS FOR $1;
	ret RECORD;
BEGIN
	FOR ret IN SELECT * FROM ksmess.attachement WHERE message_index=messid LOOP
		RETURN NEXT ret;
	END LOOP;
	RETURN;
END;
$_$
    LANGUAGE plpgsql;


--
-- Name: put_attach(character, character varying, character varying, character varying); Type: FUNCTION; Schema: ksmess; Owner: kodmasin
--

CREATE FUNCTION put_attach(character, character varying, character varying, character varying) RETURNS boolean
    AS $_$
DECLARE
	messid ALIAS FOR $1;
	aname ALIAS FOR $2;
	atype ALIAS FOR $3;
	apath ALIAS FOR $4;
	ret boolean;
BEGIN
	ret := false;
	INSERT INTO ksmess.attachement (message_index, name, path, type) VALUES(messid, aname, apath, atype);
	IF FOUND THEN
		ret := true;
	END IF;
	RETURN ret;
END;
$_$
    LANGUAGE plpgsql;


--
-- Name: messages; Type: TABLE; Schema: ksmess; Owner: kodmasin; Tablespace: 
--

CREATE TABLE messages (
    "index" character(41) NOT NULL,
    "type" smallint NOT NULL,
    subject character varying(250),
    body text,
    mfrom bigint NOT NULL,
    mto bigint NOT NULL,
    mdate timestamp with time zone DEFAULT ('now'::text)::timestamp(6) with time zone
);


--
-- Name: receive(character varying); Type: FUNCTION; Schema: ksmess; Owner: kodmasin
--

CREATE FUNCTION receive(character varying) RETURNS messages
    AS $_$
DECLARE
	messid ALIAS FOR $1;
	ret RECORD;
BEGIN
	SELECT INTO ret * FROM ksmess.messages WHERE index=messid;
	RETURN ret;
END;
$_$
    LANGUAGE plpgsql;


--
-- Name: send(bigint, bigint, smallint, character varying, text); Type: FUNCTION; Schema: ksmess; Owner: kodmasin
--

CREATE FUNCTION send(bigint, bigint, smallint, character varying, text) RETURNS character varying
    AS $_$
DECLARE
	nfrom ALIAS FOR $1;
	nto ALIAS FOR $2;
	ntype ALIAS FOR $3;
	nsubject ALIAS FOR $4;
	nbody ALIAS FOR $5;
	messid char(32);
	ret varchar;
BEGIN
	ret :='';
	--generation of primary key little bit of time and some sequence so you could have max 200000000 insertions in second
	SELECT INTO messid to_hex(CAST (to_char(CURRENT_TIMESTAMP, 'YYYYMMDDHHMISS') AS bigint)) || to_hex(nextval('ksmess.messid_helper'));
	INSERT INTO ksmess.messages (index, mfrom, mto, type, subject, body) VALUES(messid, nfrom, nto, ntype, nsubject, nbody);
	IF FOUND THEN
		ret:=messid;
	END IF;
	RETURN ret;
END;
$_$
    LANGUAGE plpgsql;


--
-- Name: messid_helper; Type: SEQUENCE; Schema: ksmess; Owner: kodmasin
--

CREATE SEQUENCE messid_helper
    INCREMENT BY 1
    MAXVALUE 200000000
    NO MINVALUE
    CACHE 1
    CYCLE;


--
-- Name: ksmatt_uk; Type: CONSTRAINT; Schema: ksmess; Owner: kodmasin; Tablespace: 
--

ALTER TABLE ONLY attachement
    ADD CONSTRAINT ksmatt_uk UNIQUE (path);


--
-- Name: ksmessage_pk; Type: CONSTRAINT; Schema: ksmess; Owner: kodmasin; Tablespace: 
--

ALTER TABLE ONLY messages
    ADD CONSTRAINT ksmessage_pk PRIMARY KEY ("index");


--
-- Name: fsma_mesid; Type: FK CONSTRAINT; Schema: ksmess; Owner: kodmasin
--

ALTER TABLE ONLY attachement
    ADD CONSTRAINT fsma_mesid FOREIGN KEY (message_index) REFERENCES messages("index") ON UPDATE RESTRICT ON DELETE SET NULL;


--
-- Name: kmessage_from_fk; Type: FK CONSTRAINT; Schema: ksmess; Owner: kodmasin
--

ALTER TABLE ONLY messages
    ADD CONSTRAINT kmessage_from_fk FOREIGN KEY (mfrom) REFERENCES kaute.kaute("index") ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- Name: ksmessage_to_fk; Type: FK CONSTRAINT; Schema: ksmess; Owner: kodmasin
--

ALTER TABLE ONLY messages
    ADD CONSTRAINT ksmessage_to_fk FOREIGN KEY (mto) REFERENCES kaute.kaute("index") ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

