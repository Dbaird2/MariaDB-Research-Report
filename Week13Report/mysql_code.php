<!DOCTYPE html>
<html>
<head>
<title>SQL CODE USED</title>
</head>
<style>
body{
text-align:left;
</style>

<body>
<div>
CREATE TABLE crime2 ( id INT PRIMARY KEY, dr_no VARCHAR(20), date_reported VARCHAR(40), year_reported INT, date_occ VARCHAR(10), time_occ VARCHAR(10), area VARCHAR(10), area_name VARCHAR(40), rpt_dist VARCHAR(20), part_1_2 VARCHAR(20), crim_cd VARCHAR(40), crim_desc VARCHAR(60), mocodes VARCHAR(60), vict_age INT, vict_sex VARCHAR(2), vict_desc VARCHAR(100), premis_cd VARCHAR(20), premis_desc VARCHAR(80), weap_cd VARCHAR(40), weap_desc VARCHAR(70), status VARCHAR(3), status_desc VARCHAR(15) ) ENGINE=CONNECT TABLE_TYPE=MYSQL DBNAME= cmps4420_s25_group6 
 CONNECTION='mysql://cmps4420_s25_group6:Qif19yipp@solaire.cs.csub.edu/cmps4420_s25_group6/crime';

<br>
<br>
SELECT SUM(CHAR_LENGTH(premis_desc)) AS premis_total_characters, SUM(CHAR_LENGTH(vict_desc)) AS vict_total_characters, SUM(CHAR_LENGTH(mocodes)) AS mocodes_total_characters, SUM(CHAR_LENGTH(weap_desc)) AS weap_total_characters,
SUM(CHAR_LENGTH(crim_desc)) AS crim_total_characters,
SUM(CHAR_LENGTH(status_desc)) AS status_total_characters
SUM(CHAR_LENGTH(area_name)) AS areaname_total_characters
FROM crime;
<br>
<br>

INSERT INTO crime
(
id, dr_no, date_reported, year_reported, date_occ, time_occ, area, area_name, rpt_dist, part_1_2, crim_cd, crim_desc, mocodes, vict_age, vict_sex, vict_desc, premis_cd, premis_desc, weap_cd, weap_desc, status, status_desc
)
SELECT id, dr_no, date_reported, CONVERT(SUBSTRING(date_reported, 7, 4), unsigned), date_occ, time_occ, area, area_name, rpt_dist, part_1_2, crim_cd, crim_desc, mocodes, vict_age, vict_sex, vict_desc, premis_cd, premis_desc, weap_cd, weap_desc, status, status_desc FROM hpart_crime;

<br>
<br>
INSERT INTO hpart_crime
(
id, dr_no, date_reported, year_reported, date_occ, time_occ, area, area_name, rpt_dist, part_1_2, crim_cd, crim_desc, mocodes, vict_age, vict_sex, vict_desc, premis_cd, premis_desc, weap_cd, weap_desc, status, status_desc
)
SELECT id, dr_no, date_reported, CONVERT(SUBSTRING(date_reported, 7, 4), unsigned), date_occ, time_occ, area, area_name, rpt_dist, part_1_2, crim_cd, crim_desc, mocodes, vict_age, vict_sex, vict_desc, premis_cd, premis_desc, weap_cd, weap_desc, status, status_desc FROM crime;

<br>
<br>

INSERT INTO hpart_crime
(
id, dr_no, date_reported, year_reported, date_occ, time_occ, area, area_name, rpt_dist, part_1_2, crim_cd, crim_desc, mocodes, vict_age, vict_sex, vict_desc, premis_cd, premis_desc, weap_cd, weap_desc, status, status_desc
)
SELECT id, dr_no, date_reported, CONVERT(SUBSTRING(date_reported, 7, 4), unsigned), date_occ, time_occ, area, area_name, rpt_dist, part_1_2, crim_cd, crim_desc, mocodes, vict_age, vict_sex, vict_desc, premis_cd, premis_desc, weap_cd, weap_desc, status, status_desc FROM crime; 
<br>
<br>

INSERT INTO hpart_crime SELECT * FROM crime;
<br>
<br>

CREATE TABLE hpart_date_crime ( id INT, dr_no varchar(20), date_reported VARCHAR(40),

year_reported int,  date_occ VARCHAR(10), time_occ VARCHAR(10), area varchar(10), area_name VARCHAR(40), rpt_dist varchar(20), part_1_2 varchar(20),

 crim_cd varchar(40), crim_desc VARCHAR(60), mocodes VARCHAR(60), vict_age INT, vict_sex VARCHAR(2), vict_desc VARCHAR(100), premis_cd varchar(20), premis_desc varchar(80), weap_cd VARCHAR(40), weap_desc VARCHAR(70), status VARCHAR(3), status_desc VARCHAR(15), PRIMARY KEY (id, year_reported) ) PARTITION BY RANGE (year_reported) ( PARTITION P1 VALUES LESS THAN (2024), PARTITION P2 VALUES LESS THAN (2025), PARTITION P3 VALUES LESS THAN (2026));
<br>
<br>

CREATE TABLE hpart_crime ( id INT, dr_no varchar(20), date_reported VARCHAR(40), year_reported int, date_occ VARCHAR(10), time_occ VARCHAR(10), area varchar(10), area_name VARCHAR(40), rpt_dist varchar(20), part_1_2 varchar(20),

 crim_cd varchar(40), crim_desc VARCHAR(60), mocodes VARCHAR(60), vict_age INT, vict_sex VARCHAR(2), vict_desc VARCHAR(100), premis_cd varchar(20), premis_desc varchar(80), weap_cd VARCHAR(40), weap_desc VARCHAR(70), status VARCHAR(3), status_desc VARCHAR(15), PRIMARY KEY (id) ) PARTITION BY RANGE (id) ( PARTITION P1 VALUES LESS THAN (710418), PARTITION P2 VALUES LESS THAN (775865), PARTITION P3 VALUES LESS THAN (841312), PARTITION P4 VALUES LESS THAN (906759), PARTITION P5 VALUES LESS THAN (1035059) );
<br>
<br>

CREATE TABLE crime (
id INT PRIMARY KEY,
dr_no varchar(20),
date_reported VARCHAR(40),
year_reported int,
date_occ VARCHAR(10),
time_occ VARCHAR(10),
area varchar(10),
area_name VARCHAR(40), rpt_dist varchar(20), part_1_2 varchar(20),
crim_cd varchar(40), crim_desc VARCHAR(60), mocodes VARCHAR(60), vict_age INT, vict_sex VARCHAR(2), vict_desc VARCHAR(100), premis_cd varchar(20), premis_desc varchar(80), weap_cd VARCHAR(40), weap_desc VARCHAR(70), status VARCHAR(3), status_desc VARCHAR(15) );
</div>
</body>

</html>
