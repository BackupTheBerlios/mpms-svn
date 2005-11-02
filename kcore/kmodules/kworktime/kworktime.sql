--
-- PostgreSQL database dump
--

SET client_encoding = 'UNICODE';
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: kworktime; Type: SCHEMA; Schema: -; Owner: kodmasin
--

CREATE SCHEMA kworktime;


SET search_path = kworktime, pg_catalog;

--
-- Name: new_status(bigint, smallint, character varying, timestamp with time zone); Type: FUNCTION; Schema: kworktime; Owner: kodmasin
--

CREATE FUNCTION new_status(bigint, smallint, character varying, timestamp with time zone) RETURNS void
    AS $_$
DECLARE
	useri ALIAS FOR $1;
	ntype ALIAS FOR $2;
	nnote ALIAS FOR $3;
	ntime ALIAS FOR $4;
	temp RECORD;
BEGIN
	SELECT INTO temp * FROM kworktime.ctime WHERE user_index=useri;
	IF FOUND THEN
		INSERT INTO kworktime.times (user_index, stype, note, stime) VALUES(temp.user_index, temp.stype, temp.note, temp.stime);
		DELETE FROM kworktime.ctime WHERE user_index=useri;
	END IF;
	INSERT INTO kworktime.ctime (user_index, stype, note, stime) VALUES(useri, ntype, nnote, ntime);
	RETURN;
END;
$_$
    LANGUAGE plpgsql;


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: ctime; Type: TABLE; Schema: kworktime; Owner: kodmasin; Tablespace: 
--

CREATE TABLE ctime (
    user_index bigint NOT NULL,
    stype smallint NOT NULL,
    note character varying(250),
    stime timestamp with time zone NOT NULL
);


--
-- Name: times; Type: TABLE; Schema: kworktime; Owner: kodmasin; Tablespace: 
--

CREATE TABLE times (
    user_index bigint NOT NULL,
    stype smallint NOT NULL,
    note character varying(250),
    stime timestamp with time zone NOT NULL
);


--
-- Name: ctime_fk; Type: FK CONSTRAINT; Schema: kworktime; Owner: kodmasin
--

ALTER TABLE ONLY ctime
    ADD CONSTRAINT ctime_fk FOREIGN KEY (user_index) REFERENCES kaute.kaute("index") ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- Name: kwt_fk; Type: FK CONSTRAINT; Schema: kworktime; Owner: kodmasin
--

ALTER TABLE ONLY times
    ADD CONSTRAINT kwt_fk FOREIGN KEY (user_index) REFERENCES kaute.kaute("index") ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- Name: kworktime; Type: ACL; Schema: -; Owner: kodmasin
--

REVOKE ALL ON SCHEMA kworktime FROM PUBLIC;
REVOKE ALL ON SCHEMA kworktime FROM kodmasin;
GRANT ALL ON SCHEMA kworktime TO kodmasin;


--
-- PostgreSQL database dump complete
--

