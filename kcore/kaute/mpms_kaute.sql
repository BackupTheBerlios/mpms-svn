--
-- PostgreSQL database dump
--

SET client_encoding = 'UNICODE';
SET check_function_bodies = false;

--
-- TOC entry 3 (OID 20108)
-- Name: kaute; Type: SCHEMA; Schema: -; Owner: 
--

CREATE SCHEMA kaute AUTHORIZATION kodmasin;


SET SESSION AUTHORIZATION 'kodmasin';

SET search_path = kaute, pg_catalog;

--
-- TOC entry 4 (OID 20109)
-- Name: kaute_sek; Type: SEQUENCE; Schema: kaute; Owner: kodmasin
--

CREATE SEQUENCE kaute_sek
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 6 (OID 20113)
-- Name: kaute; Type: TABLE; Schema: kaute; Owner: kodmasin
--

CREATE TABLE kaute (
    username character varying(20) NOT NULL,
    passwd character varying(41) NOT NULL,
    "index" bigint DEFAULT nextval('kaute.kaute_sek'::text) NOT NULL,
    failed smallint DEFAULT 0 NOT NULL
) WITHOUT OIDS;


--
-- TOC entry 7 (OID 20125)
-- Name: groups; Type: TABLE; Schema: kaute; Owner: kodmasin
--

CREATE TABLE groups (
    "index" bigint DEFAULT nextval('kaute.groups_sek'::text) NOT NULL,
    name character varying(20) NOT NULL,
    description character varying(200),
    enabled boolean DEFAULT true NOT NULL,
    system boolean DEFAULT true
) WITHOUT OIDS;


--
-- TOC entry 8 (OID 20132)
-- Name: user_group; Type: TABLE; Schema: kaute; Owner: kodmasin
--

CREATE TABLE user_group (
    user_index bigint NOT NULL,
    group_index bigint NOT NULL
) WITHOUT OIDS;


--
-- TOC entry 5 (OID 20140)
-- Name: groups_sek; Type: SEQUENCE; Schema: kaute; Owner: kodmasin
--

CREATE SEQUENCE groups_sek
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 23 (OID 20155)
-- Name: get_user_groups(bigint); Type: FUNCTION; Schema: kaute; Owner: kodmasin
--

CREATE FUNCTION get_user_groups(bigint) RETURNS SETOF groups
    AS '
DECLARE
	userid ALIAS FOR $1;
	ret kaute.groups;
BEGIN
	FOR ret IN SELECT g.* FROM kaute.groups AS g, kaute.user_group AS ug WHERE ug.user_index=userid AND g.index=ug.group_index AND g.enabled=''true'' LOOP
		RETURN NEXT ret;
	END LOOP;
	RETURN;
END;
'
    LANGUAGE plpgsql;


--
-- TOC entry 22 (OID 20159)
-- Name: authentify(character varying, character varying, smallint); Type: FUNCTION; Schema: kaute; Owner: kodmasin
--

CREATE FUNCTION authentify(character varying, character varying, smallint) RETURNS bigint
    AS '
DECLARE
	uname ALIAS FOR $1;
	upass ALIAS FOR $2;
	ret int8;
	temp RECORD;
	temp1 RECORD;
BEGIN
	ret := 0;
	SELECT INTO temp1 index, failed FROM  kaute.kaute WHERE username=uname and passwd=upass;
	IF FOUND THEN
		IF temp1.failed < $3 THEN
			UPDATE kaute.kaute SET failed=0 WHERE index=temp1.index;
			ret := temp1.index;
		ELSE
			ret := -1;
		END IF;

	ELSE
		SELECT INTO temp * FROM kaute.kaute WHERE username=uname;
		IF FOUND THEN
			UPDATE kaute.kaute SET failed=temp.failed+1 WHERE index=temp.index;
			IF temp.failed > $3 THEN
				ret := -1;
			ELSE
				ret := -2;
			END IF;
		ELSE
			ret := -3;
		END IF;
	END IF;
	RETURN ret;
END;
'
    LANGUAGE plpgsql;


--
-- TOC entry 15 (OID 20165)
-- Name: new_user(character varying, character varying); Type: FUNCTION; Schema: kaute; Owner: kodmasin
--

CREATE FUNCTION new_user(character varying, character varying) RETURNS smallint
    AS '
DECLARE
	uname ALIAS FOR $1;
	upass ALIAS FOR $2;
	ret int2;
	temp int8;
BEGIN
	ret := 0;
	SELECT INTO temp index FROM kaute.kaute WHERE username=uname;
	IF NOT FOUND THEN
		INSERT INTO kaute.kaute (username, passwd) VALUES(uname, upass);
		IF FOUND THEN
			ret := 1;
		END IF;
	ELSE
		ret := 2;
	END IF;
	RETURN ret;
END;
'
    LANGUAGE plpgsql;


--
-- TOC entry 17 (OID 20168)
-- Name: list_users(character varying, smallint, smallint); Type: FUNCTION; Schema: kaute; Owner: kodmasin
--

CREATE FUNCTION list_users(character varying, smallint, smallint) RETURNS SETOF kaute
    AS '
DECLARE
	filter ALIAS FOR $1;
	offs ALIAS FOR $2;
	lim ALIAS FOR $3;
	ret kaute.kaute;
BEGIN
	FOR ret IN SELECT * FROM kaute.kaute WHERE username~filter ORDER BY username LIMIT lim OFFSET offs LOOP
		RETURN NEXT ret;
	END LOOP;
	RETURN;
END;
'
    LANGUAGE plpgsql;


--
-- TOC entry 18 (OID 20170)
-- Name: count_users(character varying); Type: FUNCTION; Schema: kaute; Owner: kodmasin
--

CREATE FUNCTION count_users(character varying) RETURNS bigint
    AS '
DECLARE
	filter ALIAS FOR $1;
	ret int8;
BEGIN
	SELECT INTO ret count(*) FROM kaute.kaute WHERE username~filter;
	RETURN ret;
END;
'
    LANGUAGE plpgsql;


--
-- TOC entry 19 (OID 20171)
-- Name: chpass_user(bigint, character varying); Type: FUNCTION; Schema: kaute; Owner: kodmasin
--

CREATE FUNCTION chpass_user(bigint, character varying) RETURNS boolean
    AS '
DECLARE
	uindex ALIAS FOR $1;
	nupass ALIAS FOR $2;
	ret boolean;
BEGIN
	ret:=false;
	UPDATE kaute.kaute SET passwd=nupass WHERE index=uindex;
	IF FOUND THEN
		ret:=true;
	END IF;
	RETURN ret;
END;
'
    LANGUAGE plpgsql;


--
-- TOC entry 20 (OID 20172)
-- Name: del_user(bigint); Type: FUNCTION; Schema: kaute; Owner: kodmasin
--

CREATE FUNCTION del_user(bigint) RETURNS boolean
    AS '
DECLARE
	uindex ALIAS FOR $1;
	ret boolean;
BEGIN
	ret:=false;
	DELETE FROM kaute.kaute WHERE index=uindex;
	IF FOUND THEN
		ret:=true;
	END IF;
	RETURN ret;
END;
'
    LANGUAGE plpgsql;


--
-- TOC entry 21 (OID 20173)
-- Name: dis_user(bigint, smallint); Type: FUNCTION; Schema: kaute; Owner: kodmasin
--

CREATE FUNCTION dis_user(bigint, smallint) RETURNS boolean
    AS '
DECLARE
	uindex ALIAS FOR $1;
	ret boolean;
BEGIN
	ret:=false;
	UPDATE kaute.kaute SET failed=$2 WHERE index=uindex;
	IF FOUND THEN
		ret:=true;
	END IF;
	RETURN ret;
END;
'
    LANGUAGE plpgsql;


--
-- TOC entry 24 (OID 20179)
-- Name: list_groups(character varying, smallint, smallint); Type: FUNCTION; Schema: kaute; Owner: kodmasin
--

CREATE FUNCTION list_groups(character varying, smallint, smallint) RETURNS SETOF groups
    AS '
DECLARE
	filter ALIAS FOR $1;
	offs ALIAS FOR $2;
	lim ALIAS FOR $3;
	ret kaute.groups;
BEGIN
	FOR ret IN SELECT * FROM kaute.groups WHERE name~filter ORDER BY name LIMIT lim OFFSET offs LOOP
		RETURN NEXT ret;
	END LOOP;
	RETURN;
END;
'
    LANGUAGE plpgsql;


--
-- TOC entry 25 (OID 20180)
-- Name: count_groups(character varying); Type: FUNCTION; Schema: kaute; Owner: kodmasin
--

CREATE FUNCTION count_groups(character varying) RETURNS bigint
    AS '
DECLARE
	filter ALIAS FOR $1;
	ret int8;
BEGIN
	SELECT INTO ret count(*) FROM kaute.groups WHERE name~filter;
	RETURN ret;
END;
'
    LANGUAGE plpgsql;


--
-- TOC entry 26 (OID 20181)
-- Name: get_group(bigint); Type: FUNCTION; Schema: kaute; Owner: kodmasin
--

CREATE FUNCTION get_group(bigint) RETURNS groups
    AS '
DECLARE
	ind ALIAS FOR $1;
	ret kaute.groups;
BEGIN
	SELECT INTO ret * FROM kaute.groups WHERE index=ind;
	RETURN ret;
END;
'
    LANGUAGE plpgsql;


--
-- TOC entry 27 (OID 20184)
-- Name: del_group(bigint); Type: FUNCTION; Schema: kaute; Owner: kodmasin
--

CREATE FUNCTION del_group(bigint) RETURNS boolean
    AS '
DECLARE
	gindex ALIAS FOR $1;
	ret boolean;
BEGIN
	ret:=false;
	DELETE FROM kaute.groups WHERE index=gindex;
	IF FOUND THEN
		ret:=true;
	END IF;
	RETURN ret;
END;
'
    LANGUAGE plpgsql;


--
-- TOC entry 28 (OID 20186)
-- Name: ed_group(bigint, boolean); Type: FUNCTION; Schema: kaute; Owner: kodmasin
--

CREATE FUNCTION ed_group(bigint, boolean) RETURNS boolean
    AS '
DECLARE
	gindex ALIAS FOR $1;
	ret boolean;
BEGIN
	ret:=false;
	UPDATE kaute.groups SET enabled=$2 WHERE index=gindex;
	IF FOUND THEN
		ret:=true;
	END IF;
	RETURN ret;
END;
'
    LANGUAGE plpgsql;


--
-- TOC entry 16 (OID 20194)
-- Name: user_groups(character varying, smallint, smallint, bigint); Type: FUNCTION; Schema: kaute; Owner: kodmasin
--

CREATE FUNCTION user_groups(character varying, smallint, smallint, bigint) RETURNS SETOF user_group
    AS '
DECLARE
	filter ALIAS FOR $1;
	offs ALIAS FOR $2;
	lim ALIAS FOR $3;
	uindex ALIAS FOR $4;
	rret kaute.user_group;
BEGIN
	FOR rret IN SELECT * FROM kaute.user_group WHERE user_index = uindex AND group_index IN (SELECT index FROM kaute.groups AS gr WHERE gr.name~filter ORDER BY gr.name LIMIT lim OFFSET offs) LOOP
		RETURN NEXT rret;
	END LOOP;
	RETURN;
END;
'
    LANGUAGE plpgsql;


--
-- TOC entry 31 (OID 20195)
-- Name: set_user_groups(bigint, bigint[]); Type: FUNCTION; Schema: kaute; Owner: kodmasin
--

CREATE FUNCTION set_user_groups(bigint, bigint[]) RETURNS void
    AS '
DECLARE
	uindex ALIAS FOR $1;
	gindexes ALIAS FOR $2;
	msize int;
	mcurr int;
	temp RECORD;
	temp1 RECORD;
BEGIN
	SELECT INTO msize * FROM array_upper(gindexes, 1);
	--RAISE NOTICE ''msize (%)'', msize;
	IF FOUND THEN
		FOR mcurr IN 1..msize LOOP
			--RAISE NOTICE ''mcurr (%)'', mcurr;
			SELECT INTO temp1 gindexes[mcurr][1] AS groupi, gindexes[mcurr][2] AS flag;
			--temp1 := gindexes[mcurr];
			--RAISE NOTICE ''gindexes (%)(%)'', temp1.groupi, temp1.flag;
			SELECT INTO temp * FROM kaute.user_group WHERE user_index=uindex AND group_index=gindexes[mcurr][1];
			IF FOUND AND temp1.flag = 0 THEN
				--RAISE NOTICE ''Nasao'', mcurr;
				DELETE FROM kaute.user_group WHERE user_index=uindex AND group_index=gindexes[mcurr][1];
			ELSIF NOT FOUND AND temp1.flag = 1 THEN
				--RAISE NOTICE ''nisam Nasao'', mcurr;
				INSERT INTO kaute.user_group (user_index, group_index) VALUES(uindex, gindexes[mcurr][1]);
			END IF;
		END LOOP;
	END IF;
	RETURN;
END;
'
    LANGUAGE plpgsql;


--
-- TOC entry 30 (OID 20979)
-- Name: change_group(character varying, bigint, boolean); Type: FUNCTION; Schema: kaute; Owner: kodmasin
--

CREATE FUNCTION change_group(character varying, bigint, boolean) RETURNS boolean
    AS '
DECLARE
	gdesc ALIAS FOR $1;
	gin ALIAS FOR $2;
	gsys ALIAS FOR $3;
	ret boolean;
BEGIN
	ret := false;
	UPDATE kaute.groups SET description=gdesc, system=gsys  WHERE index=gin;
	IF FOUND THEN
		ret := true;
	END IF;	
	RETURN ret;
END;
'
    LANGUAGE plpgsql;


--
-- TOC entry 14 (OID 20980)
-- Name: new_group(character varying, character varying, boolean); Type: FUNCTION; Schema: kaute; Owner: kodmasin
--

CREATE FUNCTION new_group(character varying, character varying, boolean) RETURNS smallint
    AS '
DECLARE
	gname ALIAS FOR $1;
	gdesc ALIAS FOR $2;
	gsys ALIAS FOR $3;
	ret int2;
	temp int8;
BEGIN
	ret := 0;
	SELECT INTO temp index FROM kaute.groups WHERE name=gname;
	IF NOT FOUND THEN
		INSERT INTO kaute.groups (name, description, system) VALUES(gname, gdesc, gsys);
		IF FOUND THEN
			ret := 1;
		END IF;
	ELSE
		RET:= 2;
	END IF;
	RETURN ret;
END;
'
    LANGUAGE plpgsql;


--
-- TOC entry 29 (OID 20985)
-- Name: initize(character varying); Type: FUNCTION; Schema: kaute; Owner: kodmasin
--

CREATE FUNCTION initize(character varying) RETURNS void
    AS '
DECLARE
	pass ALIAS FOR $1;
	useri int8;
	groupi int8;
BEGIN
	INSERT INTO kaute.groups (name, description, system) VALUES(''admin'', ''user and group system administration group'', true);
	INSET INTO kaute.kaute (username, passwd) VALUE(""admin"", pass);
	SELECT INTO useri index FROM kaute.kaute WHERE username=""admin"";
	SELECT INTO groupi index FROM kaute.groups WHERE name=""admin"";
	INSERT INTO kaute.user_group (user_index, group_index) VALUES(useri,groupi);
	RETURN;
END;
'
    LANGUAGE plpgsql;


--
-- TOC entry 9 (OID 20116)
-- Name: kauth_pk; Type: CONSTRAINT; Schema: kaute; Owner: kodmasin
--

ALTER TABLE ONLY kaute
    ADD CONSTRAINT kauth_pk PRIMARY KEY ("index");


--
-- TOC entry 10 (OID 20118)
-- Name: kauth_un; Type: CONSTRAINT; Schema: kaute; Owner: kodmasin
--

ALTER TABLE ONLY kaute
    ADD CONSTRAINT kauth_un UNIQUE (username);


--
-- TOC entry 11 (OID 20128)
-- Name: groups_pk; Type: CONSTRAINT; Schema: kaute; Owner: kodmasin
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_pk PRIMARY KEY ("index");


--
-- TOC entry 12 (OID 20130)
-- Name: groups_uni; Type: CONSTRAINT; Schema: kaute; Owner: kodmasin
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_uni UNIQUE (name);


--
-- TOC entry 13 (OID 20134)
-- Name: user_group_pk; Type: CONSTRAINT; Schema: kaute; Owner: kodmasin
--

ALTER TABLE ONLY user_group
    ADD CONSTRAINT user_group_pk PRIMARY KEY (user_index, group_index);


--
-- TOC entry 32 (OID 20136)
-- Name: user_group_user; Type: FK CONSTRAINT; Schema: kaute; Owner: kodmasin
--

ALTER TABLE ONLY user_group
    ADD CONSTRAINT user_group_user FOREIGN KEY (user_index) REFERENCES kaute("index") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 33 (OID 20142)
-- Name: user_group_group; Type: FK CONSTRAINT; Schema: kaute; Owner: kodmasin
--

ALTER TABLE ONLY user_group
    ADD CONSTRAINT user_group_group FOREIGN KEY (group_index) REFERENCES groups("index") ON UPDATE CASCADE ON DELETE CASCADE;


