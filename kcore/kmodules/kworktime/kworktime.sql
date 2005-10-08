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


ALTER SCHEMA kworktime OWNER TO kodmasin;

SET search_path = kworktime, pg_catalog;

--
-- Name: new_status(bigint, smallint, character varying, timestamp with time zone, interval); Type: FUNCTION; Schema: kworktime; Owner: kodmasin
--

CREATE FUNCTION new_status(bigint, smallint, character varying, timestamp with time zone, interval) RETURNS void
    AS $_$
DECLARE
	useri ALIAS FOR $1;
	ntype ALIAS FOR $2;
	nnote ALIAS FOR $3;
	ntime ALIAS FOR $4;
	nzone ALIAS FOR $5;
	temp RECORD;
BEGIN
	SELECT INTO temp * FROM kworktime.ctime WHERE user_index=useri;
	IF FOUND THEN
		INSERT INTO kworktime.times (user_index, stype, note, stime, uzone) VALUES(temp.user_index, temp.stype, temp.note, temp.stime, temp.uzone);
		DELETE FROM kworktime.ctime WHERE user_index=useri;
	END IF;
	INSERT INTO kworktime.ctime (user_index, stype, note, stime, uzone) VALUES(useri, ntype, nnote, ntime, nzone);
	RETURN;
END;
$_$
    LANGUAGE plpgsql;


ALTER FUNCTION kworktime.new_status(bigint, smallint, character varying, timestamp with time zone, interval) OWNER TO kodmasin;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: ctime; Type: TABLE; Schema: kworktime; Owner: kodmasin; Tablespace: 
--

CREATE TABLE ctime (
    user_index bigint NOT NULL,
    stype smallint NOT NULL,
    note character varying(250),
    stime timestamp with time zone NOT NULL,
    uzone interval NOT NULL
);


ALTER TABLE kworktime.ctime OWNER TO kodmasin;

--
-- Name: times; Type: TABLE; Schema: kworktime; Owner: kodmasin; Tablespace: 
--

CREATE TABLE times (
    user_index bigint NOT NULL,
    stype smallint NOT NULL,
    note character varying(250),
    stime timestamp with time zone NOT NULL,
    uzone interval NOT NULL
);


ALTER TABLE kworktime.times OWNER TO kodmasin;

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


--
-- PostgreSQL database dump complete
--

