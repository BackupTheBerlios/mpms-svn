--
-- PostgreSQL database dump
--

SET client_encoding = 'UNICODE';
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: kmmanager; Type: SCHEMA; Schema: -; Owner: kodmasin
--

CREATE SCHEMA kmmanager;


SET search_path = kmmanager, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: user_prefs; Type: TABLE; Schema: kmmanager; Owner: kodmasin; Tablespace: 
--

CREATE TABLE user_prefs (
    lang character(2),
    "time" character varying(15),
    date character varying(20),
    skin character varying(10),
    user_index bigint NOT NULL
);


--
-- Name: load_prefs(bigint); Type: FUNCTION; Schema: kmmanager; Owner: kodmasin
--

CREATE FUNCTION load_prefs(bigint) RETURNS user_prefs
    AS $_$
DECLARE
	uuser ALIAS FOR $1;
	ret RECORD;
BEGIN
	SELECT INTO ret * FROM kmmanager.user_prefs WHERE user_index=uuser;
	RETURN ret;
END;
$_$
    LANGUAGE plpgsql;


--
-- Name: save_prefs1(character varying, character varying, character varying, character varying, bigint); Type: FUNCTION; Schema: kmmanager; Owner: kodmasin
--

CREATE FUNCTION save_prefs1(character varying, character varying, character varying, character varying, bigint) RETURNS void
    AS $_$
DECLARE
	ulang ALIAS FOR $1;
	utime ALIAS FOR $2;
	udate ALIAS FOR $3;
	uskin ALIAS FOR $4;
	uuser ALIAS FOR $5;
BEGIN
	PERFORM user_index FROM kmmanager.user_prefs WHERE user_index=uuser;
	IF FOUND THEN
		UPDATE kmmanager.user_prefs SET lang=ulang,time=utime,date=udate,skin=uskin WHERE user_index=uuser;
	ELSE
		INSERT INTO kmmanager.user_prefs (lang,time,date,skin,user_index) VALUES(ulang,utime,udate, uskin,uuser);
	END IF;
	RETURN;
END;
$_$
    LANGUAGE plpgsql;


--
-- Name: kmm_uk_us_pre; Type: CONSTRAINT; Schema: kmmanager; Owner: kodmasin; Tablespace: 
--

ALTER TABLE ONLY user_prefs
    ADD CONSTRAINT kmm_uk_us_pre UNIQUE (user_index);


--
-- Name: kmm_fk_user; Type: FK CONSTRAINT; Schema: kmmanager; Owner: kodmasin
--

ALTER TABLE ONLY user_prefs
    ADD CONSTRAINT kmm_fk_user FOREIGN KEY (user_index) REFERENCES kaute.kaute("index") ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

