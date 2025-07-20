<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SQL Partitioning Report</title>
  <style>
.big-file img {
    width:40%;
height:40%;
}
.query-block img {
        width: 40vw;
        height: 50vh;
}
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f8f8;
      margin: 0;
      padding: 0;
    }
    header {
      background-color: #333;
      color: white;
      padding: 1rem;
      text-align: center;
    }
    nav {
      display: flex;
      justify-content: center;
      background-color: #444;
    }
    nav button {
      padding: 1rem;
      background: none;
      border: none;
      color: white;
      cursor: pointer;
    }
    nav button.active {
      background-color: #666;
    }
    .content {
        width:80%;
        margin:auto;
        padding: 2rem;
    }
    .query-block {
      background-color: white;
      margin-bottom: 1.5rem;
      padding: 1rem;
      border-left: 6px solid #007acc;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }
    .query-block h3 {
      margin-top: 0;
    }
    .sql {
      background-color: #eee;
      padding: 0.5rem;
      font-family: monospace;
      white-space: pre-wrap;
    }
    .hidden {
      display: none;
    }
  </style>
</head>
<body>
  <header>
    <h1>SQL Query Partitioning Report</h1>
  </header>
  <nav>
    <button onclick="showType('insert')" class="active">INSERT</button>
    <button onclick="showType('create')">CREATE</button>
    <button onclick="showType('select')">SELECT</button>
    <button onclick="showType('size')">SIZE</button>
    <button onclick="showType('index')">INDEXES</button>
    <button onclick="showType('php')">PHP CODE</button>
    <button onclick="showType('graphs')">GRAPHS</button>
    <button onclick="showType('report')">REPORT</button>
    <button onclick="showType('misc')">MISC</button>
  </nav>
  <div class="content">
    <!-- INSERT SECTION -->
    <div class="section insert">
      <div class="query-block">
        <h3>Original Table</h3>
        <pre class="sql">INSERT INTO crime  
( 
id, dr_no, date_reported, year_reported, date_occ, time_occ, area, area_name, rpt_dist, part_1_2, crim_cd, crim_desc, mocodes, vict_age, vict_sex, vict_desc, premis_cd, premis_desc, weap_cd, weap_desc, status, status_desc 
)  
SELECT id, dr_no, date_reported, CONVERT(SUBSTRING(date_reported, 7, 4), unsigned), date_occ, time_occ, area, area_name, rpt_dist, part_1_2, crim_cd, crim_desc, mocodes, vict_age, vict_sex, vict_desc, premis_cd, premis_desc, weap_cd, weap_desc, status, status_desc FROM hpart_crime; </pre>
      </div>
      <div class="query-block">
        <h3>Horizontal Partition</h3>
        <pre class="sql">INSERT INTO hpart_crime  
( 
id, dr_no, date_reported, year_reported, date_occ, time_occ, area, area_name, rpt_dist, part_1_2, crim_cd, crim_desc, mocodes, vict_age, vict_sex, vict_desc, premis_cd, premis_desc, weap_cd, weap_desc, status, status_desc 
)  
SELECT id, dr_no, date_reported, CONVERT(SUBSTRING(date_reported, 7, 4), unsigned), date_occ, time_occ, area, area_name, rpt_dist, part_1_2, crim_cd, crim_desc, mocodes, vict_age, vict_sex, vict_desc, premis_cd, premis_desc, weap_cd, weap_desc, status, status_desc FROM crime; </pre>
      </div>
      <div class="query-block">
        <h3>Vertical Partition Type 1</h3>
        <pre class="sql">INSERT INTO vpart_crime 
( 
id, dr_no, date_reported, year_reported, date_occ, time_occ, area, area_name, rpt_dist, part_1_2, vict_age, vict_sex, vict_desc, status, status_desc 
)  
SELECT id, dr_no, date_reported, CONVERT(SUBSTRING(date_reported, 7, 4), unsigned), date_occ, time_occ, area, area_name, rpt_dist, part_1_2, vict_age, vict_sex, vict_desc, status, status_desc FROM crime;</pre>
        <pre class="sql">insert into vpart_premis_table (id, premis_cd, premis_desc) select id, premis_cd, premis_desc from crime;</pre>
        <pre class="sql">insert into vpart_crimecd_table (id, crim_cd, crim_desc) select id, crim_cd, crim_desc from crime;</pre>
        <pre class="sql">insert into vpart_weap_table (id, weap_cd, weap_desc) select id, weap_cd, weap_desc from crime;</pre>
        <pre class="sql">insert into vpart_mocodes_table (id, mocodes) select id, mocodes from crime;</pre>
      </div>
      <div class="query-block">
        <h3>Vertical Partition Type 2</h3>
        <pre class="sql">INSERT INTO new_vpart_crimee  
( 
id, dr_no, date_reported, year_reported, date_occ, time_occ, area, rpt_dist, part_1_2, crim_cd, mocodes, vict_age, vict_sex, vict_desc, premis_cd, weap_cd, status 
)  
SELECT id, dr_no, date_reported, CONVERT(SUBSTRING(date_reported, 7, 4), unsigned), date_occ, time_occ, area, rpt_dist, part_1_2, crim_cd,  mocodes, vict_age, vict_sex, vict_desc, premis_cd, weap_cd, status FROM crime;</pre>
        <pre class="sql">insert into new_vweap_table (weap_cd, weap_desc) select distinct weap_cd, weap_desc from crime;</pre>
        <pre class="sql">insert into new_vcrimecd_table (crim_cd, crim_desc) select distinct crim_cd, crim_desc from crime;</pre>
        <pre class="sql">insert into new_vpremis_table (premis_cd, premis_desc) select distinct premis_cd, premis_desc from crime where premis_cd is not NULL;</pre>
        <pre class="sql">insert into new_varea_table (area, area_name) select distinct area, area_name from crime where area is not null order by CAST(area AS UNSIGNED) ASC;</pre>
        <pre class="sql">insert into new_vstatus_table (status, status_desc) select distinct status, status_desc from crime where status is not null;</pre>
      </div>
      <div class="query-block">
        <h3>Memory-Mapped Original</h3>
        <pre class="sql">Insert into crime_memory select * from crime;</pre>
      </div>
      <div class="query-block">
        <h3>Memory-Mapped Vertical</h3>
        <pre class="sql">INSERT INTO vpart_crime_memory 
( 
id, dr_no, date_reported, year_reported, date_occ, time_occ, area, rpt_dist, part_1_2, crim_cd, mocodes, vict_age, vict_sex, vict_desc, premis_cd, weap_cd, status 
)  
SELECT id, dr_no, date_reported, CONVERT(SUBSTRING(date_reported, 7, 4), unsigned), date_occ, time_occ, area, rpt_dist, part_1_2, crim_cd,  mocodes, vict_age, vict_sex, vict_desc, premis_cd, weap_cd, status FROM crime;</pre>
      </div>
    </div>

    <!-- CREATE SECTION -->
    <div class="section create hidden">
      <div class="query-block">
        <h3>Original Table</h3>
        <pre class="sql">CREATE TABLE crime (  
id INT PRIMARY KEY, dr_no varchar(20),date_reported VARCHAR(40), year_reported int,date_occ VARCHAR(10), time_occ VARCHAR(10), area varchar(10), area_name VARCHAR(40), rpt_dist varchar(20), part_1_2 varchar(20), 
crim_cd varchar(40), crim_desc VARCHAR(60), mocodes VARCHAR(60), vict_age INT, vict_sex VARCHAR(2), vict_desc VARCHAR(100), premis_cd varchar(20), premis_desc varchar(80), weap_cd VARCHAR(40), weap_desc VARCHAR(70), status VARCHAR(3), status_desc VARCHAR(15) );</pre>
      </div>
      <div class="query-block">
        <h3>Horizontal Partition Type 1</h3>
        <pre class="sql">CREATE TABLE hpart_crime ( id INT, dr_no varchar(20), date_reported VARCHAR(40), year_reported int, date_occ VARCHAR(10), time_occ VARCHAR(10), area varchar(10), area_name VARCHAR(40), rpt_dist varchar(20), part_1_2 varchar(20), crim_cd varchar(40), crim_desc VARCHAR(60), mocodes VARCHAR(60), vict_age INT, vict_sex VARCHAR(2), vict_desc VARCHAR(100), premis_cd varchar(20), premis_desc varchar(80), weap_cd VARCHAR(40), weap_desc VARCHAR(70), status VARCHAR(3), status_desc VARCHAR(15), PRIMARY KEY (id) ) PARTITION BY RANGE (id) ( PARTITION P1 VALUES LESS THAN (710418), PARTITION P2 VALUES LESS THAN (775865), PARTITION P3 VALUES LESS THAN (841312), PARTITION P4 VALUES LESS THAN (906759), PARTITION P5 VALUES LESS THAN (1035059) ); </pre>
      </div>
      <div class="query-block">
        <h3>Horizontal Partition Type 2</h3>
        <pre class="sql">CREATE TABLE hpart_date_crime ( id INT, dr_no varchar(20), date_reported VARCHAR(40), year_reported int,  date_occ VARCHAR(10), time_occ VARCHAR(10), area varchar(10), area_name VARCHAR(40), rpt_dist varchar(20), part_1_2 varchar(20), crim_cd varchar(40), crim_desc VARCHAR(60), mocodes VARCHAR(60), vict_age INT, vict_sex VARCHAR(2), vict_desc VARCHAR(100), premis_cd varchar(20), premis_desc varchar(80), weap_cd VARCHAR(40), weap_desc VARCHAR(70), status VARCHAR(3), status_desc VARCHAR(15), PRIMARY KEY (id, year_reported) ) PARTITION BY RANGE (year_reported) ( PARTITION P1 VALUES LESS THAN (2024), PARTITION P2 VALUES LESS THAN (2025), PARTITION P3 VALUES LESS THAN (2026)); </pre>
  </div>
      <div class="query-block">
        <h3>Vertical Partition Type 1</h3>
        <pre class="sql">CREATE TABLE vpart_crime (  
id INT PRIMARY KEY,  
dr_no varchar(20), 
date_reported VARCHAR(40),  
year_reported int, 
date_occ VARCHAR(10),  
time_occ VARCHAR(10),  
area varchar(10),  
area_name VARCHAR(40),  
rpt_dist varchar(20),  
part_1_2 varchar(20),   
vict_age INT,  
vict_sex VARCHAR(2),  
vict_desc VARCHAR(100),  
status VARCHAR(3),  
status_desc VARCHAR(15) ); </pre>
        <pre class="sql">CREATE TABLE vpart_premis_table ( 
id int , 
premis_cd VARCHAR(20), 
premis_desc VARCHAR(80), 
FOREIGN KEY (id) REFERENCES vpart_crime(id) 
);</pre>
        <pre class="sql">CREATE TABLE vpart_crimecd_table ( 
id int, 
crim_cd VARCHAR(40), 
crim_desc VARCHAR(60), 
FOREIGN KEY (id) REFERENCES vpart_crime(id) 
); 
</pre>
        <pre class="sql">CREATE TABLE vpart_weap_table ( 
id int, 
weap_cd VARCHAR(40), 
weap_desc VARCHAR(70), 
FOREIGN KEY (id) REFERENCES vpart_crime(id) 
);</pre>
        <pre class="sql">CREATE TABLE vpart_mocodes_table ( 
id int, 
mocodes varchar(60), 
FOREIGN KEY (id) REFERENCES vpart_crime(id) 
);</pre>
      </div>
      <div class="query-block">
        <h3>Vertical Partition Type 2</h3>
        <pre class="sql">CREATE TABLE new_vpart_crime (  
id INT PRIMARY KEY,  
dr_no varchar(20), 
date_reported VARCHAR(40),  
year_reported int, 
date_occ VARCHAR(10),  
time_occ VARCHAR(10),  
area varchar(10),  
rpt_dist varchar(20), part_1_2 varchar(20), 
crim_cd varchar(40), mocodes VARCHAR(60), vict_age INT, vict_sex VARCHAR(2), vict_desc VARCHAR(100), premis_cd varchar(20), weap_cd VARCHAR(40),  status VARCHAR(3));</pre>
        <pre class="sql">CREATE TABLE new_vweap_table ( 
weap_cd VARCHAR(40), 
weap_desc VARCHAR(70), 
PRIMARY KEY (weap_cd) 
);</pre>
        <pre class="sql">CREATE TABLE new_vcrimecd_table ( 
crim_cd VARCHAR(40), 
crim_desc VARCHAR(60), 
PRIMARY KEY (crim_cd) 
);</pre>
        <pre class="sql">CREATE TABLE new_vpremis_table ( 
premis_cd VARCHAR(20), 
premis_desc VARCHAR(80), 
PRIMARY KEY (premis_cd) 
); </pre>
        <pre class="sql">CREATE TABLE new_varea_table ( 
area VARCHAR(10), 
area_name VARCHAR(40) 
);</pre>
        <pre class="sql">CREATE TABLE new_vstatus_table ( 
status VARCHAR(3), 
status_desc VARCHAR(15) 
);</pre>
      </div>
      <div class="query-block">
        <h3>Memory-Mapped Original</h3>
        <pre class="sql">CREATE TABLE crime_memory (  
id INT PRIMARY KEY,  
dr_no varchar(20), 
date_reported VARCHAR(40),  
year_reported int, 
date_occ VARCHAR(10),  
time_occ VARCHAR(10),  
area varchar(10),  
area_name VARCHAR(40), rpt_dist varchar(20), part_1_2 varchar(20), 
crim_cd varchar(40), crim_desc VARCHAR(60), mocodes VARCHAR(60), vict_age INT, vict_sex VARCHAR(2), vict_desc VARCHAR(100), premis_cd varchar(20), premis_desc varchar(80), weap_cd VARCHAR(40), weap_desc VARCHAR(70), status VARCHAR(3), status_desc VARCHAR(15) ) ENGINE=MEMORY; </pre>
      </div>
      <div class="query-block">
        <h3>Memory-Mapped Vertical</h3>
        <pre class="sql">CREATE TABLE vpart_crime_memory (  
id INT PRIMARY KEY,  
dr_no varchar(20), 
date_reported VARCHAR(40),  
year_reported int, 
date_occ VARCHAR(10),  
time_occ VARCHAR(10),  
area varchar(10),  
rpt_dist varchar(20), part_1_2 varchar(20), 
crim_cd varchar(40), mocodes VARCHAR(60), vict_age INT, vict_sex VARCHAR(2), vict_desc VARCHAR(100), premis_cd varchar(20), weap_cd VARCHAR(40),  status VARCHAR(3)) ENGINE=MEMORY; </pre>
      </div>
    </div>

    <!-- SELECT SECTION -->
    <div class="section select hidden">
      <div class="query-block">
        <h3>Original</h3>
        <pre class="sql">SELECT * FROM crime;</pre>
        <pre>Query Time 0.356s, Rows: 390088</pre>
        <pre class="sql">SELECT count(*) FROM crime;</pre>
        <pre>Query Time 0.047s, Rows: 390088</pre>
        <pre class="sql">select id, weap_cd, weap_desc from crime where weap_cd = 102.0;</pre>
        <pre>Query Time 0.069s, Rows: 6664</pre>
        <pre class="sql">select id, premis_cd, premis_desc from crime where premis_cd = 101.0</pre>
        <pre>Query Time 0.093s, Rows: 108931</pre>
        <pre class="sql">select id, weap_cd, weap_desc, vict_sex, vict_age from crime where weap_cd = 500.0;</pre>
        <pre>Query Time 0.080s, Rows: 14215</pre>
        <pre class="sql">select id, crim_cd, crim_desc, vict_sex, vict_age from crime where crim_cd = 110.0;</pre>
        <pre>Query Time 0.090s, Rows: 448</pre>
        <pre class="sql">select id, area, area_name,vict_sex, vict_age from crime where area = 20;</pre>
        <pre>Query Time 0.096s, Rows: 18861</pre>
        <pre class="sql">select id, status, status_desc,vict_sex, vict_age from crime where status = "IC";</pre>
        <pre>Query Time 0.126s, Rows: 326270</pre>
        <pre class="sql">select id, weap_cd, weap_desc, crim_cd,crim_desc, premis_cd, premis_desc, area, area_name, status, status_desc, vict_sex, vict_age from crime where weap_cd = 104.0;</pre>
        <pre>Query Time 0.130s, Rows: 107</pre>
        <pre class="sql">select * from crime where year_reported = 2023;</pre>
        <pre>Query Time 0.289s, Rows: 27124</pre>
        <pre class="sql">select * from crime where year_reported = 2024;</pre>
        <pre>Query Time 0.255s, Rows: 62886</pre>
        <pre class="sql">select * from crime where year_reported = 2025;</pre>
        <pre>Query Time 0.183s, Rows: 78</pre>
      </div>
      <div class="query-block">
        <h3>Horizontal Partition Type 1</h3>
        <pre class="sql">select * from hpart_crime where year_reported = 2023;</pre>
        <pre>Query Time 0.295s, Rows: 27124</pre>
        <pre class="sql">select * from hpart_crime where year_reported = 2024;</pre>
        <pre>Query Time 0.261s, Rows: 62886</pre>
        <pre class="sql">select * from hpart_crime where year_reported = 2025;</pre>
        <pre>Query Time 0.183s, Rows: 78</pre>
      </div>
      <div class="query-block">
        <h3>Horizontal Partition Type 2</h3>
        <pre class="sql">select * from hpart_date_crime where year_reported = 2023;</pre>
        <pre>Query Time 0.222s, Rows: 27124</pre>
        <pre class="sql">select * from hpart_date_crime where year_reported = 2024;</pre>
        <pre>Query Time 0.156s, Rows: 62886</pre>
        <pre class="sql">select * from hpart_date_crime where year_reported = 2025;</pre>
        <pre>Query Time 0.001s, Rows: 78</pre>
      </div>
      <div class="query-block">
        <h3>Vertical Partition Type 1</h3>
        <pre class="sql">SELECT vpart_crime.id, dr_no, date_reported, year_reported, date_occ, time_occ, area, area_name, rpt_dist, part_1_2, vpart_crimecd_table.crim_cd, vpart_crimecd_table.crim_desc, vpart_mocodes_table.mocodes, vict_age, vict_sex, vict_desc, vpart_premis_table.premis_cd, vpart_premis_table.premis_desc, vpart_weap_table.weap_cd, vpart_weap_table.weap_desc, status, status_desc FROM vpart_crime LEFT JOIN vpart_crimecd_table ON vpart_crime.id = vpart_crimecd_table.id LEFT JOIN vpart_premis_table ON vpart_crimecd_table.id = vpart_premis_table.id LEFT JOIN vpart_mocodes_table ON vpart_premis_table.id = vpart_mocodes_table.id LEFT JOIN vpart_weap_table ON vpart_mocodes_table.id = vpart_weap_table.id;</pre>
        <pre>Query Time 2.684s, Rows: 390088</pre>

        <pre class="sql">select id, weap_cd, weap_desc from vpart_weap_table where weap_cd = 102.0;</pre>
        <pre>Query Time 0.029s, Rows: 6664</pre>

        <pre class="sql">select id, premis_cd, premis_desc from vpart_premis_table where premis_cd = 101.0</pre>
        <pre>Query Time 0.102s, Rows: 108931</pre>

    </div>
      <div class="query-block">
        <h3>Vertical Partition Type 2</h3>
        <pre class="sql">SELECT * FROM new_vpart_crime 
LEFT JOIN new_vweap_table ON new_vpart_crime.weap_cd = new_vweap_table.weap_cd  
LEFT JOIN new_vcrimecd_table ON new_vpart_crime.crim_cd = new_vcrimecd_table.crim_cd  
LEFT JOIN new_vpremis_table ON new_vpart_crime.premis_cd = new_vpremis_table.premis_cd LEFT JOIN new_varea_table ON new_vpart_crime.area = new_varea_table.area  
LEFT JOIN new_vstatus_table ON new_vpart_crime.status = new_vstatus_table.status; </pre>
        <pre>Query Time 2.813s, Rows: 390088</pre>

        <pre class="sql">SELECT count(*) FROM new_vpart_crime  
LEFT JOIN new_vweap_table ON new_vpart_crime.weap_cd = new_vweap_table.weap_cd  
LEFT JOIN new_vcrimecd_table ON new_vpart_crime.crim_cd = new_vcrimecd_table.crim_cd  
LEFT JOIN new_vpremis_table ON new_vpart_crime.premis_cd = new_vpremis_table.premis_cd LEFT JOIN new_varea_table ON new_vpart_crime.area = new_varea_table.area  
LEFT JOIN new_vstatus_table ON new_vpart_crime.status = new_vstatus_table.status; </pre>
        <pre>Query Time 0.683s, Rows: 390088</pre>

        <pre class="sql">select new_vpart_crime.id, new_vpremis_table.premis_cd, new_vpremis_table.premis_desc, new_vpart_crime.vict_sex, new_vpart_crime.vict_age from new_vpart_crime left join new_vpremis_table on new_vpart_crime.premis_cd = new_vpremis_table.premis_cd where new_vpremis_table.premis_cd = 101.0;</pre>
        <pre>Query Time 0.124s, Rows: 108931</pre>

        <pre class="sql">select new_vpart_crime.id, new_vweap_table.weap_cd, new_vweap_table.weap_desc, new_vpart_crime.vict_sex, new_vpart_crime.vict_age from new_vpart_crime left join new_vweap_table on new_vpart_crime.weap_cd = new_vweap_table.weap_cd where new_vweap_table.weap_cd = 500.0;</pre>
        <pre>Query Time 0.024s, Rows: 14215</pre>

        <pre class="sql">select new_vpart_crime.id, new_vcrimecd_table.crim_cd, new_vcrimecd_table.crim_desc, new_vpart_crime.vict_sex, new_vpart_crime.vict_age from new_vpart_crime left join new_vcrimecd_table on new_vpart_crime.crim_cd = new_vcrimecd_table.crim_cd where new_vcrimecd_table.crim_cd = 110.0;</pre>
        <pre>Query Time 0.004s, Rows: 448</pre>

        <pre class="sql">select new_vpart_crime.id, new_varea_table.area, new_varea_table.area_name, new_vpart_crime.vict_sex, new_vpart_crime.vict_age from new_vpart_crime left join new_varea_table on new_vpart_crime.area = new_varea_table.area where new_varea_table.area = 20;</pre>
        <pre>Query Time 0.093s, Rows: 18861</pre>

        <pre class="sql">select new_vpart_crimee.id, new_vstatus_table.status, new_vstatus_table.status_desc, new_vpart_crime.vict_sex, new_vpart_crimee.vict_age from new_vpart_crime left join new_vstatus_table on new_vpart_crimee.status = new_vstatus_table.status where new_vstatus_table.status = "IC";</pre>
        <pre>Query Time 0.136s, Rows: 326270</pre>

        <pre class="sql">SELECT new_vpart_crime.id, new_vweap_table.weap_cd, new_vweap_table.weap_desc, new_vcrimecd_table.crim_cd, new_vcrimecd_table.crim_desc, new_vpremis_table.premis_cd, new_vpremis_table.premis_desc, new_varea_table.area, new_varea_table.area_name, new_vstatus_table.status, new_vstatus_table.status_desc, new_vpart_crime.vict_sex, new_vpart_crime.vict_age FROM new_vpart_crime LEFT JOIN new_vweap_table ON new_vpart_crime.weap_cd = new_vweap_table.weap_cd LEFT JOIN new_vcrimecd_table ON new_vpart_crime.crim_cd = new_vcrimecd_table.crim_cd LEFT JOIN new_vpremis_table ON new_vpart_crime.premis_cd = new_vpremis_table.premis_cd LEFT JOIN new_varea_table ON new_vpart_crime.area = new_varea_table.area LEFT JOIN new_vstatus_table ON new_vpart_crime.status = new_vstatus_table.status WHERE new_vweap_table.weap_cd = 104.0;</pre>
        <pre>Query Time 0.001s, Rows: 107</pre>

      </div>
      <div class="query-block">
        <h3>Memory Mapped Original</h3>
        <pre class="sql">Select * from crime_memory;</pre>
        <pre>Query Time 0.271s, Rows: 390088</pre>

        <pre class="sql">SELECT COUNT(*) from crime_memory;</pre>
        <pre>Query Time 0.001s, Rows: 390088</pre>

        <pre class="sql">select id, weap_cd, weap_desc from crime_memory where weap_cd = 102.0;</pre>
        <pre>Query Time 0.096s, Rows: 6664</pre>

        <pre class="sql">select id, premis_cd, premis_desc, vict_sex, vict_age from crime_memory where premis_cd = 101.0;</pre>
        <pre>Query Time 0.117s, Rows: 108931</pre>

        <pre class="sql">select id, weap_cd, weap_desc, vict_sex, vict_age from crime_memory where weap_cd = 500.0;</pre>
        <pre>Query Time 0.098s, Rows: 14215</pre>

        <pre class="sql">select id, crim_cd, crim_desc, vict_sex, vict_age from crime_memory where crim_cd = 110.0;</pre>
        <pre>Query Time 0.103s, Rows: 448</pre>

        <pre class="sql">select id, area, area_name,vict_sex, vict_age from crime_memory where area = 20;</pre>
        <pre>Query Time 0.106s, Rows: 18861</pre>

        <pre class="sql">select id, status, status_desc,vict_sex, vict_age from crime_memory where status = “IC”;</pre>
        <pre>Query Time 0.141s, Rows: 326270</pre>

        <pre class="sql">select id, weap_cd, weap_desc, crim_cd,crim_desc, premis_cd, premis_desc, area, area_name, status, status_desc, vict_sex, vict_age from crime_memory where weap_cd = 104.0;</pre>
        <pre>Query Time 0.096s, Rows: 107</pre>

      </div>
      <div class="query-block">
        <h3>Memory Mapped Vertical Type 2</h3>
        <pre class="sql">SELECT * FROM vpart_crime_memory  
LEFT JOIN new_vweap_table ON vpart_crime_memory.weap_cd = new_vweap_table.weap_cd  
LEFT JOIN new_vcrimecd_table ON vpart_crime_memory.crim_cd = new_vcrimecd_table.crim_cd  
LEFT JOIN new_vpremis_table ON vpart_crime_memory.premis_cd = new_vpremis_table.premis_cd LEFT JOIN new_varea_table ON vpart_crime_memory.area = new_varea_table.area  
LEFT JOIN new_vstatus_table ON vpart_crime_memory.status = new_vstatus_table.status;</pre>
        <pre>Query Time 2.813s, Rows: 390088</pre>

        <pre class="sql">SELECT count(*) FROM vpart_crime_memory  
LEFT JOIN new_vweap_table ON vpart_crime_memory.weap_cd = new_vweap_table.weap_cd  
LEFT JOIN new_vcrimecd_table ON vpart_crime_memory.crim_cd = new_vcrimecd_table.crim_cd  
LEFT JOIN new_vpremis_table ON vpart_crime_memory.premis_cd = new_vpremis_table.premis_cd LEFT JOIN new_varea_table ON vpart_crime_memory.area = new_varea_table.area  
LEFT JOIN new_vstatus_table ON vpart_crime_memory.status = new_vstatus_table.status; </pre>
        <pre>Query Time 0.667s, Rows: 390088</pre>

        <pre class="sql">select vpart_crime_memory.id, new_vpremis_table.premis_cd, new_vpremis_table.premis_desc, vpart_crime_memory.vict_sex, vpart_crime_memory.vict_age from vpart_crime_memory left join new_vpremis_table on vpart_crime_memory.premis_cd = new_vpremis_table.premis_cd where new_vpremis_table.premis_cd = 101.0;</pre>
        <pre>Query Time 0.050s, Rows: 108931</pre>

        <pre class="sql"> select vpart_crime_memory.id, new_vweap_table.weap_cd, new_vweap_table.weap_desc, vpart_crime_memory.vict_sex, vpart_crime_memory.vict_age from vpart_crime_memory left join new_vweap_table on vpart_crime_memory.weap_cd = new_vweap_table.weap_cd where new_vweap_table.weap_cd = 500.0;</pre>
        <pre>Query Time 0.012s, Rows: 14215</pre>

        <pre class="sql">select vpart_crime_memory.id, new_vcrimecd_table.crim_cd, new_vcrimecd_table.crim_desc, vpart_crime_memory.vict_sex, vpart_crime_memory.vict_age from vpart_crime_memory left join new_vcrimecd_table on vpart_crime_memory.crim_cd = new_vcrimecd_table.crim_cd where new_vcrimecd_table.crim_cd = 110.0;</pre>
        <pre>Query Time 0.002s, Rows: 448</pre>

        <pre class="sql">select vpart_crime_memory.id, new_varea_table.area, new_varea_table.area_name, vpart_crime_memory.vict_sex, vpart_crime_memory.vict_age from vpart_crime_memory left join new_varea_table on vpart_crime_memory.area = new_varea_table.area where new_varea_table.area = 20;</pre>
        <pre>Query Time 0.088s, Rows: 18861</pre>

        <pre class="sql">select vpart_crime_memory.id, new_vstatus_table.status, new_vstatus_table.status_desc, vpart_crime_memory.vict_sex, vpart_crime_memory.vict_age from vpart_crime_memory left join new_vstatus_table on vpart_crime_memory.status = new_vstatus_table.status where new_vstatus_table.status = “IC”;</pre>
        <pre>Query Time 0.168s, Rows: 326270</pre>

        <pre class="sql">SELECT vpart_crime_memory.id, new_vweap_table.weap_cd, new_vweap_table.weap_desc, new_vcrimecd_table.crim_cd, new_vcrimecd_table.crim_desc, new_vpremis_table.premis_cd, new_vpremis_table.premis_desc, new_varea_table.area, new_varea_table.area_name, new_vstatus_table.status, new_vstatus_table.status_desc, vpart_crime_memory.vict_sex, vpart_crime_memory.vict_age FROM vpart_crime_memory LEFT JOIN new_vweap_table ON vpart_crime_memory.weap_cd = new_vweap_table.weap_cd LEFT JOIN new_vcrimecd_table ON vpart_crime_memory.crim_cd = new_vcrimecd_table.crim_cd LEFT JOIN new_vpremis_table ON vpart_crime_memory.premis_cd = new_vpremis_table.premis_cd LEFT JOIN new_varea_table ON vpart_crime_memory.area = new_varea_table.area LEFT JOIN new_vstatus_table ON vpart_crime_memory.status = new_vstatus_table.status WHERE new_vweap_table.weap_cd = 104.0;</pre>
        <pre>Query Time 0.004s, Rows: 107</pre>

      </div>

</div>
    <!-- SIZES SECTION -->
    <div class="section size hidden">
      <div class="query-block">
        <h3>Original</h3>
        <pre class="sql">SELECT  
    table_name AS "Crime Table",  
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'crime'; 
</pre>
        <pre>Data Size: 84.61MB | Index Size: 19.55MB | Total Size: 104.16MB</pre>
<pre class="sql">Alter Table crime ENGINE=INNODB ROW_FORMAT=COMPRESSED;</pre>
        <pre>Data Size: 39.83MB | Index Size: 9.77MB | Total Size: 49.60MB</pre>
      </div>
      <div class="query-block">
        <h3>Horizontal Partition Type 1</h3>
        <pre class="sql">SELECT  
    table_name AS "Horizontally Pertitioned Type 1 Table",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'hpart_crime'; </pre>
        <pre>Data Size: 94.61MB | Index Size: 0MB | Total Size: 94.61MB</pre>
      </div>
      <div class="query-block">
        <h3>Horizontal Partition Type 2</h3>
        <pre class="sql">SELECT  
    table_name AS "Horizontally Pertitioned Type 2 Table",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'hpart_date_crime'; </pre>
        <pre>Data Size: 82.22MB | Index Size: 0MB | Total Size: 82.22MB</pre>
      </div>
      <div class="query-block">
        <h3>Vertical Partition Type 1</h3>
        <pre class="sql">SELECT  
    table_name AS "Vertical Crime Table",  
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'vpart_crime'; 
</pre>
        <pre>Data Size: 47.58MB | Index Size: 0MB | Total Size: 47.58MB</pre>
        <pre class="sql">SELECT  
    table_name AS "Vertical Premise Table",  
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'vpart_premis_table'; </pre>
        <pre>Data Size: 22.55MB | Index Size: 15.03MB | Total Size: 37.58MB</pre>
        <pre class="sql">SELECT  
    table_name AS "Vertical Weap Table",  
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'vpart_weap_table'; </pre>
        <pre>Data Size: 16.41MB | Index Size: 8.77MB | Total Size: 25.17MB</pre>
        <pre class="sql"> SELECT  
    table_name AS "Vertical Crime Cd Table",  
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'vpart_crimecd_table';</pre>
        <pre>Data Size: 26.56MB | Index Size: 14.03MB | Total Size: 40.59MB</pre>
        <pre class="sql">SELECT  
    table_name AS "Vertical Mocodes Table",  
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'vpart_mocodes_table';</pre>
        <pre>Data Size: 17.55MB | Index Size: 6.52MB | Total Size: 24.06MB</pre>
      </div>
      <div class="query-block">
        <h3>Vertical Partition Type 2</h3>
        <pre class="sql">SELECT  
    table_name AS "New Vertical Crime Table",  
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'new_vpart_crime'; </pre>
        <pre>Data Size: 49.58MB | Index Size: 19.55MB | Total Size: 69.13MB</pre>
<pre class="sql">Alter Table new_vpart_crime ENGINE=INNODB ROW_FORMAT=COMPRESSED;</pre>
        <pre>Data Size: 25.30MB | Index Size: 9.77MB | Total Size: 35.07MB</pre>
        <pre class="sql">SELECT  
    table_name AS "New Vertical Status Table",  
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'new_vstatus_table'; 
</pre>
        <pre>Data Size: 0.02MB | Index Size: 0MB | Total Size: 0.02MB</pre>
        <pre class="sql">SELECT  
    table_name AS "New Vertical Area Table",  
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'new_varea_table'; 
</pre>
        <pre>Data Size: 0.02MB | Index Size: 0MB | Total Size: 0.02MB</pre>
        <pre class="sql">SELECT  
    table_name AS "New Vertical Area Table",  
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'new_varea_table'; 
</pre>
        <pre>Data Size: 0.02MB | Index Size: 0MB | Total Size: 0.02MB</pre>
        <pre class="sql">SELECT  
    table_name AS "New Vertical Weap Table",  
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'new_vweap_table'; 
</pre>
        <pre>Data Size: 0.02MB | Index Size: 0.02MB | Total Size: 0.03MB</pre>
        <pre class="sql">SELECT  
    table_name AS "New Vertical Crime Cd Table",  
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
        <pre>Data Size: 0.02MB | Index Size: 0.02MB | Total Size: 0.03MB</pre>
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'new_vcrimecd_table';</pre>
        <pre class="sql">SELECT  
    table_name AS "New Vertical Premis Table",  
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'new_vpremis_table';</pre>
        <pre>Data Size: 0.02MB | Index Size: 0.03MB | Total Size: 0.05MB</pre>
<pre class="sql">Alter Table new_vpremis_table ENGINE=INNODB ROW_FORMAT=COMPRESSED;</pre>
        <pre>Data Size: 0.02MB | Index Size: 0.02MB | Total Size: 0.04MB</pre>
      </div>
      <div class="query-block">
        <h3>Memory Mapped Original</h3>
        <pre class="sql">SELECT  
    table_name AS "Crime Memory Table",  
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'crime_memory'; </pre>
        <pre>Data Size: 997.09MB | Index Size: 26.89MB | Total Size: 1023.98MB</pre>
      </div>
      <div class="query-block">
        <h3>Memory Mapped Vertical Partition Type 2</h3>
        <pre class="sql">SELECT  
    table_name AS "Vertically Partitioned Crime Memory Table",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'vpart_crime_memory'; 
</pre>
        <pre>Data Size: 601.24MB | Index Size: 35.85MB | Total Size: 637.09MB</pre>
      </div>
    </div>

    <!-- INDEX SECTION -->
    <div class="section index hidden">
      <div class="query-block">
        <h3>Original</h3>
        <pre class="sql">create index weap_cd_index on crime (weap_cd);</pre>
        <pre class="sql">create index crim_cd_index on crime (crim_cd);</pre>
        <pre class="sql">create index premis_cd_index on crime (premis_cd);</pre>
      </div>
      <div class="query-block">
        <h3>Horizontal Partition Type 1</h3>
        <pre class="sql">NONE</pre>
      </div>
      <div class="query-block">
        <h3>Horizontal Partition Type 2</h3>
        <pre class="sql">NONE</pre>
      </div>
      <div class="query-block">
        <h3>Vertical Partition Type 1</h3>
        <pre class="sql">create index crim_cd_index on vpart_crimecd_table (crim_cd);</pre>
        <pre class="sql">create index weap_cd_index on vpart_weap_table (weap_cd);</pre>
        <pre class="sql">create index premis_cd_index on vpart_premis_table (premis_cd);</pre>
      </div>
      <div class="query-block">
        <h3>Vertical Partition Type 2</h3>
        <pre class="sql">create index weap_cd_index on new_vpart_crime (weap_cd);</pre>
        <pre class="sql">create index crim_cd_index on new_vpart_crime (crim_cd);</pre>
        <pre class="sql">create index premis_cd_index on new_vpart_crime (premis_cd);</pre>
      </div>
      <div class="query-block">
        <h3>Memory Mapped Original</h3>
        <pre class="sql">create index weap_cd_index on crime_memory (weap_cd);</pre>
        <pre class="sql">create index crim_cd_index on crime_memory (crim_cd);</pre>
        <pre class="sql">create index premis_cd_index on crime_memory (premis_cd);</pre>
      </div>
      <div class="query-block">
        <h3>Memory Mapped Vertical Partition Type 2</h3>
        <pre class="sql">create index weap_cd_index on vpart_crime_memory (weap_cd);</pre>
        <pre class="sql">create index crim_cd_index on vpart_crime_memory (crim_cd);</pre>
        <pre class="sql">create index premis_cd_index on vpart_crime_memory (premis_cd);</pre>
      </div>
    </div>

    <!-- MISC SECTION -->
    <div class="section misc hidden">
        <h3>Misc SQL Code</h3>
      <div class="query-block">
        <h3>Table Counts</h3>
        <pre class="sql">select count(*) from new_vcrimecd_table 
union all 
select count(*) from new_vpremis_table
union all 
select count(*) from new_vstatus_table 
union all 
select count(*) from new_varea_table 
union all 
select count(*) from new_vweap_table;</pre>
      </div>
      <div class="query-block">
        <h3>Profiling</h3>
        <pre class="sql">SET profiling = 1;
SHOW profiles;
SHOW PROFILE FOR QUERY 1;
</pre>
<img class="big-file" src="regular_profiling.png">
<img class="big-file" src="memory_profiling.png">
      </div>
      <div class="query-block">
        <h3>ENGINE=CONNECT</h3>
        <pre class="sql">CREATE TABLE crime2 ( id INT PRIMARY KEY, dr_no VARCHAR(20), date_reported VARCHAR(40), year_reported INT, date_occ VARCHAR(10), time_occ VARCHAR(10), area VARCHAR(10), area_name VARCHAR(40), rpt_dist VARCHAR(20), part_1_2 VARCHAR(20), crim_cd VARCHAR(40), crim_desc VARCHAR(60), mocodes VARCHAR(60), vict_age INT, vict_sex VARCHAR(2), vict_desc VARCHAR(100), premis_cd VARCHAR(20), premis_desc VARCHAR(80), weap_cd VARCHAR(40), weap_desc VARCHAR(70), status VARCHAR(3), status_desc VARCHAR(15) ) ENGINE=CONNECT TABLE_TYPE=MYSQL DBNAME= cmps4420_s25_group6
 CONNECTION='mysql://cmps4420_s25_group6:secretpassword@solaire.cs.csub.edu/cmps4420_s25_group6/crime';
</pre>
      </div>
      <div class="query-block">
        <h3>ROW_FORMAT=COMPRESSED KEY_BLOCK_SIZE</h3>
        <pre class="sql">ALTER TABLE crime_memory ROW_FORMAT=COMPRESSED KEY_BLOCK_SIZE=8;
</pre>
      </div>
      <div class="query-block">
        <h3>Other Commands</h3>
        <pre class="sql">/! clear
</pre>
      </div>
    </div>

    <!-- PHP SECTION -->
    <div class="section php hidden">
      <div class="query-block">
        <h3>PHP Code Used</h3>
        <pre class="sql">try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $file = fopen("LA_Crime_Data_2023_to_Present_data.csv", "r");

    // Skip the header row
    fgetcsv($file);

    $crime_time = microtime(true);
    $stmt = $pdo->prepare("
  INSERT INTO crime (
        id, dr_no, date_reported, date_occ, time_occ,
        area, area_name, rpt_dist, part_1_2, crim_cd,
        crim_desc, mocodes, vict_age, vict_sex, vict_desc,
        premis_cd, premis_desc, weap_cd, weap_desc,
        status, status_desc
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
    )    ");
    $pdo->beginTransaction();

    while (($data = fgetcsv($file)) !== FALSE) {
        //print_r($data);
        //echo "Number of columns in this row: " . count($data) . "\n";
        // Convert empty strings to null
        $data = array_map(function($value) {
            return $value === "" ? null : $value;
        }, $data);
        $stmt->execute($data);
    }
    $pdo->commit();

    fclose($file);
    $crime_time_end = microtime(true);
    $elapsed_time = $crime_time_end - $crime_time;
    $elapsed_time = number_format($elapsed_time, 2);
    echo "crime table CSV import successful.\n";
    echo "Insert CSV file in " . $elapsed_time . "seconds\n";

    $file = fopen("LA_Crime_Data_2023_to_Present_data.csv", "r");
    fgetcsv($file);
    $crime_time = microtime(true);
    $stmt = $pdo->prepare("
  INSERT INTO hpart_crime (
        id, dr_no, date_reported, date_occ, time_occ,
        area, area_name, rpt_dist, part_1_2, crim_cd,
        crim_desc, mocodes, vict_age, vict_sex, vict_desc,
        premis_cd, premis_desc, weap_cd, weap_desc,
        status, status_desc
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
    )
    ");
    $pdo->beginTransaction();

    while (($data = fgetcsv($file)) !== FALSE) {
        //print_r($data);
        //echo "Number of columns in this row: " . count($data) . "\n";
        // Convert empty strings to null
        $data = array_map(function($value) {
            return $value === "" ? null : $value;
        }, $data);
        $stmt->execute($data);
    }
    $pdo->commit();

    fclose($file);
    $crime_time_end = microtime(true);
    $elapsed_time = $crime_time_end - $crime_time;
    $elapsed_time = number_format($elapsed_time, 2);
    echo "Table hpart_crime CSV import successful.\n";
    echo "Insert CSV file in " . $elapsed_time . "seconds\n";


} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

</pre>
      </div>
    </div>

    <!-- GRAPH SECTION -->
    <div class="section graphs hidden">
    <h3>Query Number:</h3>
<pre>        1. table_status = "IC"
        2. crime_id = 110.0
        3. area = 20
        4. premis_cd = 101.0
        5. select *
        6. select count(*)
        7. weap_cd = 104
        8. weap_cd = 500.0
</pre>
        <h3>Graphs</h3>
      <div class="query-block">
        <h3>All Table Query Times</h3>
<pre><b>1. table_status = "IC"        2. crime_id = 110.0        3. area = 20        4. premis_cd = 101.0
5. select *                   6. select count(*)         7. weap_cd = 104    8. weap_cd = 500.0</b>
</pre>
        <img src="all_table.png">
      </div>
      <div class="query-block">
        <h3>Query Time for Select* and Select COUNT(*)</h3>
<pre><b> 5. select *          6. select count(*)</b>
</pre>
        <img src="selectAllAndCount_table.png">
      </div>
      <div class="query-block">
        <h3>Crime table</h3>
<pre><b>1. table_status = "IC"        2. crime_id = 110.0        3. area = 20        4. premis_cd = 101.0
5. select *                   6. select count(*)         7. weap_cd = 104    8. weap_cd = 500.0</b>
</pre>
        <img src="crime_table.png">
      </div>
      <div class="query-block">
        <h3>Crime_memory_table</h3>
<pre><b>1. table_status = "IC"        2. crime_id = 110.0        3. area = 20        4. premis_cd = 101.0
5. select *                   6. select count(*)         7. weap_cd = 104    8. weap_cd = 500.0</b>
</pre>
        <img src="memory_crime_table.png">
      </div>
      <div class="query-block">
        <h3>new_vpart_crime_table</h3>
<pre><b>1. table_status = "IC"        2. crime_id = 110.0        3. area = 20        4. premis_cd = 101.0
5. select *                   6. select count(*)         7. weap_cd = 104    8. weap_cd = 500.0</b>
</pre>
        <img src="new_vpart_crime_table.png">
      </div>
      <div class="query-block">
        <h3>vpart_crime_memory_table</h3>
<pre><b>1. table_status = "IC"        2. crime_id = 110.0        3. area = 20        4. premis_cd = 101.0
5. select *                   6. select count(*)         7. weap_cd = 104    8. weap_cd = 500.0</b>
</pre>
        <img src="vpart_crime_memory_table.png">
      </div>
      <div class="query-block">
        <h3>Horizontal</h3>
<pre>
</pre>
        <img src="../solaire-date-graph.png">
      </div>
    </div>
    <!-- PEPORT SECTION -->
    <div class="section report hidden">
        <h3>Report</h3>
      <div class="query-block">
        <h3>Progress from Week 14</h3>
        <pre class="sql">We already had all of our tables and there query results for vertically partitioned and horizontally partitioned. What was left for us to explore is the effect of memory mapping and data compression and how this would affect the speed of our queries. With memory mapping, you are mapping the database directly to virtual memory which helps when you need to be constantly reading from a dataset. In the CREATE tab, at the bottom you will see the original table memory mapped and the vertically partitioned crime table also memory mapped with the ENGINE=MEMORY option that tells the database to store the table into ram. In theory, this should have sped up queries drastically, but our results gave a varying performance inconsistencies. Due to these changes we have not in full figured out the reason for these inconsistencies. 
        </pre>
        <h3>Final State</h3>
        <pre class="sql">Currently, our website lists all the INSERT, CREATE, SELECT, SIZE, INDEXES, PHP CODE, and MISC. At its final state, we made tables for the LA crime data using horizontal partitioning, vertical partitioning, memory mapping, and finally tried to see if data compression would also make a difference. Under the INSERT tab, it shows the queries we used to insert our data into their respective tables. Under our CREATE tab, you will see the actual creation of these tables and how we decided to partition them. Our SELECT tab shows all the queries we ran to test speedup (if there was any) and tracked our results, in which we graphed seen under the GRAPHS tab. Under INDEXES, it just shows what we decided to index on for the tables. Under SIZE we show the queries and results for the size of each table in MB (and after trying some compression on some as well). PHP shows the code used to retreive the data from the LA crime csv file. Finally, MISC shows code/tips we learned along the way that we thought were interesting (especially /! clear blew my mind).</pre>
        <h3>Comparison to Proposal</h3>
<ul>
<li>
        <h4>How close of a match is it?</h4>
        <pre class="sql">I do wish we had done a little more when it comes to different types of CRUD operations by using mock data, but in terms of what we set out to do, everything has been done. Vertical partitioning, horizontal partitioning, memory mapping, engine connect, and a bonus of compression have been done.</pre>
</li>
<li>
        <h4>What's Changed from the Beginning?</h4>
        <pre class="sql">We were ambitious on the amount of testing we would be able to do with our schedule. Another thing is the additional of ENGINE COMPRESSION for InnoDB. </pre>
</li>
        <ul>
<li>
        <h3>Most valuable insights with respect to database systems (general)</h3>
        <pre class="sql">Optimizing tables often involve multiple different types of techniques. Certain techniques are not always ideal depending on the data. Studying the data you have been given will greatly improve your ability to enhance the performance for this table(s). Different types of optimizations have different use cases and different drawbacks. An interesting concept to note in this entire process is that, the more rows the query obtained, the closer in query execution time the queries arrived to. Visualizing this as a graph looks like a bounded square root curve, where the limit would most often be higher than the original time due to setup overhead. That issue is exceedingly more apparent in horizontal partition tables. </pre>
</li>
<li>
        <h3>Most valuable insights with respect to specific software, platforms, technology, etc. (specific)</h3>
        <pre class="sql">We have learned that depending on how you vertical partition matters. Columns that have high correlations between each other and are unique, often times are very suitable prospects for vertical partitions. Another high candidate are high amount of NULL values with highly correlated columns. Having a high amount of NULL values significantly increased the seen improved time when running vertically partitioned queries. For example, the weap_cd attribute in crime has more than a 50% NULL rate in its entirety. The queries used on the weap_cd attribute would improved by at a minimum of 300%. 

Outside of NULL rates the size of the newly created table and the average size of the new created tables had a notable effect on the performance. The tables that were smaller in row size and in data size typically had a bigger performance increase shown.

Horizontal partitioning depended on the type of columns we used to partition. For example, the primary key of id. This column did provide useful results with some increase in table size, but it did not provide the most significant increase show compared to other columns. Another columns we tried was year_reported. When selecting with year_reported as a condition, it was shown that the less rows obtained the more significant the difference in time was.

Engine memory was an overall let down. We highly expected the performance increase to be dramatic, but in reality it was in most cases on par or slower than the original table without it. The memory tables were also substantially increased in table size. For the table crime_memory we actually hit our limit of 1GB for a table and roughly 660MB for the vpart_part_memory table. This was a shocking difference and we can not recommend doing this as we currently stand.

Compressing data significantly reduced the size of the two main tables we have by roughly 50% on each table. Why? We believe that having this many varchar of highly differing lengths is the key reason. There was no change in performance due to this, it was a very interesting SQL line to use and nice to know.

Connect was really satisfying to see working, but the extra network overhead that has to happen to connect and use was sizeable to say the least. Using this as an extra database to store data that is almost never retrieve is a very viable option for this. The increase in query execution was over 8 seconds for a select all query.

Last but not least, Indexing. Indexing was shown to improve the query times by 300% in a lot of queries. How did we test indexing? Funny enough, after creating some of the tables we forgot to put the indexes into the table. We were surprised on how slow the technique was, but we eventually realized that the indexes were not implemented. This was concerning at first, but ended up working out.
 </pre>
</li>
</ul>
</ul>
      </div>
    </div>
  </div>

<script>
function showType(type) {
    document.querySelectorAll('.section').forEach(s => s.classList.add('hidden'));
    document.querySelectorAll(`.${type}`).forEach(s => s.classList.remove('hidden'));
    document.querySelectorAll('nav button').forEach(b => b.classList.remove('active'));
    event.target.classList.add('active');
}
</script>
</body>
</html>

