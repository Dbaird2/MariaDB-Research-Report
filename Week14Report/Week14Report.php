<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vertical Partitioning Comparison</title>
    <style>
        /* Global Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            line-height: 1.6;
            color: #333;
        }

        .report-container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 50px;
        }

        h1 {
            text-align: center;
            color: red;
        }
        h2 {
            text-align: center;
        }

        section {
            margin-bottom: 30px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
        }

        /* Charts / Image Previews */
        .charts-container {
            display: flex;
            justify-content: space-around;
            gap: 20px;
            flex-wrap: wrap;
        }

        .bar-chart {
            width: 100%;
            height: 200px;
            display: flex;
            justify-content: space-evenly;
        }

        .bar {
            height: 100%;
            width: 30px;
            background-color: #4CAF50;
            margin: 0 10px;
            border-radius: 5px;
        }

        .image-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .image-gallery img {
            width: 100%;
            max-width: 300px;
            border-radius: 8px;
            transition: transform 0.3s ease-in-out;
        }

        .image-gallery img:hover {
            transform: scale(1.05);
        }

        /* Custom color schemes */
        .high { background-color: red; }
        .medium { background-color: orange; }
        .low { background-color: green; }

        /* Images for Rows Returned and Partition Set Sizes */
        .images-section h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .image-row {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }

        .image-row img {
            max-width: 400px;
            max-height: 250px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        img {
            margin: auto;
            display: block;            
}
        
        /* Query Box */
        .query-box {
            width: 100%;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            font-family: monospace;
            font-size: 1rem;
            color: #333;
            margin-top: 20px;
            max-height: 200px;
            overflow-y: auto; /* Allows scrolling for long queries */
        }

        /* Add some space between each row */
        .query-container {
            margin-bottom: 40px;
        }

    </style>
</head>
<body>
<pre>
Types of Partitnings
Tables queries
Insert Queries
Random Queries for MySQL
Table Size Queries
Table Size Comparisons
Select Queries
Select Queries Comparisons
Week 15 Stuff
</pre>
    <div class="report-container">
        <h1>Vertical Partitioning Comparison</h1>
       <section>
            <p>
We have two types of vertical partitioning done. <br>First one is with all the tables that are similar having a common primary key of id.
<br>The second is having all the tables with a similar column to each for example, new_vweap_table with weapon code and weapon description
            </p>
        </section> 
<section>
            <div class="image-row">
                <h3>Vertical Partitioned Tables Queries 1</h3>
                <!-- Query 1 SQL Code -->
                <div class="query-container">
                    <div class="query-box">
                        CREATE TABLE vpart_crime (  
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
status_desc VARCHAR(15) 
);<br><br>
CREATE TABLE vpart_premis_table (
id int ,
premis_cd VARCHAR(20),
premis_desc VARCHAR(80),
FOREIGN KEY (id) REFERENCES vpart_crime(id)
); <br>
insert into vpart_premis_table (id, premis_cd, premis_desc) select id, premis_cd, premis_desc from crime; <br>
delete from vpart_premis_table where premis_cd is null; <br><br>

CREATE TABLE vpart_crimecd_table (
id int,
crim_cd VARCHAR(40),
crim_desc VARCHAR(60),
FOREIGN KEY (id) REFERENCES vpart_crime(id)
);<br>
insert into vpart_crimecd_table (id, crim_cd, crim_desc) select id, crim_cd, crim_desc from crime; <br>
delete from vpart_crimecd_table where crim_cd is null;<br><br>

CREATE TABLE vpart_weap_table (
id int,
weap_cd VARCHAR(40),
weap_desc VARCHAR(70),
FOREIGN KEY (id) REFERENCES vpart_crime(id)
);<br>
insert into vpart_weap_table (id, weap_cd, weap_desc) select id, weap_cd, weap_desc from crime; <br>
delete from vpart_weap_table where weap_cd is null;<br><br>

CREATE TABLE vpart_mocodes_table (
id int,
mocodes varchar(60),
FOREIGN KEY (id) REFERENCES vpart_crime(id)
);<br>insert into vpart_mocodes_table (id, mocodes) select id, mocodes from crime; 
<br>
delete from vpart_mocodes_table where mocodes is null;

                    </div>
                </div>
            </div>

            <!-- Query 2 Rows Image -->
            <div class="image-row">
                <h3>Vertical Partitioned Tables Queries 2</h3>
                <!-- Query 2 SQL Code -->
                <div class="query-container">
                    <div class="query-box">
CREATE TABLE new_vpart_crime (
id INT PRIMARY KEY,
dr_no varchar(20),
date_reported VARCHAR(40),
year_reported int,
date_occ VARCHAR(10),
time_occ VARCHAR(10),
area varchar(10),
rpt_dist varchar(20), part_1_2 varchar(20),
crim_cd varchar(40), mocodes VARCHAR(60), vict_age INT, vict_sex VARCHAR(2), vict_desc VARCHAR(100), premis_cd varchar(20), weap_cd VARCHAR(40),  status VARCHAR(3));
<br><br>
CREATE TABLE new_vweap_table (
weap_cd VARCHAR(40),
weap_desc VARCHAR(70),
PRIMARY KEY (weap_cd)
);<br>
insert into new_vweap_table (weap_cd, weap_desc) select distinct weap_cd, weap_desc from crime;<br>
delete from new_vweap_table where weap_cd is null;
<br><br>
CREATE TABLE new_vcrimecd_table (
crim_cd VARCHAR(40),
crim_desc VARCHAR(60),
PRIMARY KEY (crim_cd)
);<br>
insert into new_vcrimecd_table (crim_cd, crim_desc) select distinct crim_cd, crim_desc from crime;<br>
delete from new_vcrimecd_table where crim_cd is null;
<br><br>
CREATE TABLE new_vpremis_table (
premis_cd VARCHAR(20),
premis_desc VARCHAR(80),
PRIMARY KEY (premis_cd)
);<br>
insert into new_vpremis_table (premis_cd, premis_desc) select distinct premis_cd, premis_desc from crime where premis_cd is not NULL;
<br><br>
CREATE TABLE new_varea_table (
area VARCHAR(10),
area_name VARCHAR(40)
);<br>
insert into new_varea_table (area, area_name) select distinct area, area_name from crime where area is not null order by CAST(area AS UNSIGNED) ASC;<br>
<br><br>
CREATE TABLE new_vstatus_table ( 
status VARCHAR(3), 
status_desc VARCHAR(15) 
); <br>
insert into new_vstatus_table (status, status_desc) select distinct status, status_desc from crime where status is not null; 
                    </div>
                </div>
            </div>

        </section>
</section>
<section>
            <div class="image-row">
                <h3>Original Table Size</h3>
                <!-- Query 1 SQL Code -->
                <div class="query-container">
                    <div class="query-box">
SELECT
    table_name AS "Crime Table",
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"
FROM information_schema.tables
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'crime';
                    </div>
                </div>
            </div>
            <div class="image-row">
                <h3>Vertical Partitioned 1 Table Sizes</h3>
                <!-- Query 1 SQL Code -->
                <div class="query-container">
                    <div class="query-box">
SELECT
    table_name AS "Vertical Crime Table",
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"
FROM information_schema.tables
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'vpart_crime';
<br><br>
SELECT
    table_name AS "Vertical Premise Table",
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"
FROM information_schema.tables
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'vpart_premis_table';
<br><br>
SELECT
    table_name AS "Vertical Weap Table",
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"
FROM information_schema.tables
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'vpart_weap_table';
<br><br>
SELECT
    table_name AS "Vertical Crime Cd Table",
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"
FROM information_schema.tables
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'vpart_crimecd_table';
<br><br>
SELECT
    table_name AS "Vertical Mocodes Table",
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"
FROM information_schema.tables
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'vpart_mocodes_table';
                    </div>
                </div>
            </div>

            <!-- Query 2 Rows Image -->
            <div class="image-row">
                <h3>Vertical Partitioned 2 Table Sizes</h3>
                <!-- Query 2 SQL Code -->
                <div class="query-container">
                    <div class="query-box">
SELECT  
    table_name AS "New Vertical Crime Table",  
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'new_vpart_crime'; 
<br><br> 
SELECT  
    table_name AS "New Vertical Status Table",  
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'new_vstatus_table'; 
<br><br> 
SELECT  
    table_name AS "New Vertical Area Table",  
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'new_varea_table'; 
<br><br> 
SELECT  
    table_name AS "New Vertical Weap Table",  
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'new_vweap_table'; 
<br><br> 
SELECT  
    table_name AS "New Vertical Crime Cd Table",  
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'new_vcrimecd_table'; 
<br><br> 
SELECT  
    table_name AS "New Vertical Premis Table",  
    ROUND(data_length / 1024 / 1024, 2) AS "Data Size (MB)",  
    ROUND(index_length / 1024 / 1024, 2) AS "Index Size (MB)",  
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS "Total Size (MB)"  
FROM information_schema.tables  
WHERE table_schema = 'cmps4420_s25_group6'  AND table_name = 'new_vpremis_table'; 
                    </div>
                </div>
            </div>
        <section>
            <h2>Table Size Comparisons</h2>
            <table>
                <thead>
                    <tr>
                        <th>Partition Set</th>
                        <th>Table Size</th>
                        <th>Size compared to original</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Original w/ Indexes</td>
                        <td>104.16MB</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Partitioned 1 Tables Combined</td>
                        <td>174.98MB</td>
                        <td>Size difference: 68% increase</td>
                    </tr>
                    <tr>
                        <td>Partitioned 2 Tables Combined</td>
                        <td>49.73MB</td>
                        <td>Size difference: 52% decrease</td>
                    </tr>
                </tbody>
            </table>
        </section>

        </section>
        <!-- Query Performance Comparison Table -->
        <section>
            <h2>Table Queries w/ Times</h2>
            <img src = "graph1.png">
            <table>
                <thead>
                    <tr>
                        <th>Query</th>
                        <th>Verticial Partition 1</th>
                        <th>Query</th>
                        <th>Original Table</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>SELECT vpart_crime.id, dr_no, date_reported, year_reported, date_occ, time_occ, area, area_name, rpt_dist, part_1_2, vpart_crimecd_table.crim_cd, vpart_crimecd_table.crim_desc, vpart_mocodes_table.mocodes, vict_age, vict_sex, vict_desc, vpart_premis_table.premis_cd, vpart_premis_table.premis_desc, vpart_weap_table.weap_cd, vpart_weap_table.weap_desc, status, status_desc FROM vpart_crime LEFT JOIN vpart_crimecd_table ON vpart_crime.id = vpart_crimecd_table.id LEFT JOIN vpart_premis_table ON vpart_crimecd_table.id = vpart_premis_table.id LEFT JOIN vpart_mocodes_table ON vpart_premis_table.id = vpart_mocodes_table.id LEFT JOIN vpart_weap_table ON vpart_mocodes_table.id = vpart_weap_table.id;</td>
                        <td>2.684s</td>
                        <td>select id, weap_cd, weap_desc from vpart_weap_table where weap_cd = 102.0;</td>
                        <td>0.029s</td>
                    </tr>
                    <tr>
                        <td>select id, weap_cd, weap_desc from vpart_weap_table where weap_cd = 102.0;</td>
                        <td>0.029s</td>
                        <td>select id, weap_cd, weap_desc from crime where weap_cd = 102.0;</td>
                        <td>0.069s</td>
                    </tr>
                    <tr>
                        <td>select id, premis_cd, premis_desc from vpart_premis_table where premis_cd = 101.0;</td>
                        <td>0.102s</td>
                        <td>select id, premis_cd, premis_desc from crime where premis_cd = 101.0;</td>
                        <td>0.093s</td>
                    </tr>
                    <tr>
                        <td>Average Query Time</td>
                        <td>0.938s</td>
                        <td>Average Query Time</td>
                        <td>0.064s</td>
                    </tr>
                </tbody>
            </table>
            <img src = "graph2.png">
            <table>
                <thead>
                    <tr>
                        <th>Query</th>
                        <th>Verticial Partition 2</th>
                        <th>Query</th>
                        <th>Original Table</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
select crime.id, new_vweap_table.weap_cd, new_vweap_table.weap_desc, crime.vict_sex, crime.vict_age from crime left join new_vweap_table on crime.weap_cd = new_vweap_table.weap_cd where new_vweap_table.weap_cd = 500.0;
</td>
                        <td>0.024s</td>
                        <td>select id, weap_cd, weap_desc, vict_sex, vict_age from crime where weap_cd = 500.0;</td>
                        <td>0.080s</td>
                    </tr>
                    <tr>
                        <td>select crime.id, new_vcrimecd_table.crim_cd, new_vcrimecd_table.crim_desc, crime.vict_sex, crime.vict_age from crime left join new_vcrimecd_table on crime.crim_cd = new_vcrimecd_table.crim_cd where new_vcrimecd_table.crim_cd = 110.0;</td>
                        <td>0.004s</td>
                        <td>select id, crim_cd, crim_desc, vict_sex, vict_age from crime where crim_cd = 110.0;</td>
                        <td>0.090s</td>
                    </tr>
                    <tr>
                        <td>select crime.id, new_vpremis_table.premis_cd, new_vpremis_table.premis_desc, crime.vict_sex, crime.vict_age from crime left join new_vpremis_table on crime.premis_cd = new_vpremis_table.premis_cd where new_vpremis_table.premis_cd = 101.0;</td>
                        <td>0.124s</td>
                        <td>select id, premis_cd, premis_desc, vict_sex, vict_age from crime where premis_cd = 101.0;</td>
                        <td>0.107s</td>
                    </tr>
                    <tr>
                        <td>select crime.id, new_varea_table.area, new_varea_table.area_name, crime.vict_sex, crime.vict_age from crime left join new_varea_table on crime.area = new_varea_table.area where new_varea_table.area = 20;</td>
                        <td>0.093s</td>
                        <td>select id, area, area_name,vict_sex, vict_age from crime where area = 20;</td>
                        <td>0.096s</td>
                    </tr>
                    <tr>
                        <td>select crime.id, new_vstatus_table.status, new_vstatus_table.status_desc, crime.vict_sex, crime.vict_age from crime left join new_vstatus_table on crime.status = new_vstatus_table.status where new_vstatus_table.status = "IC";</td>
                        <td>0.136s</td>
                        <td>select id, status, status_desc,vict_sex, vict_age from crime where status = IC;</td>
                        <td>0.126s</td>
                    </tr>
                    <tr>
                        <td>select crime.id, new_varea_table.area, new_varea_table.area_name, crime.vict_sex, crime.vict_age from crime left join new_varea_table on crime.area = new_varea_table.area where new_varea_table.area = 20;</td>
                        <td>0.093s</td>
                        <td>select id, area, area_name,vict_sex, vict_age from crime where area = 20;</td>
                        <td>0.096s</td>
                    </tr>
                    <tr>
                        <td>SELECT crime.id, new_vweap_table.weap_cd, new_vweap_table.weap_desc, new_vcrimecd_table.crim_cd, new_vcrimecd_table.crim_desc, new_vpremis_table.premis_cd, new_vpremis_table.premis_desc, new_varea_table.area, new_varea_table.area_name, new_vstatus_table.status, new_vstatus_table.status_desc, crime.vict_sex, crime.vict_age FROM crime LEFT JOIN new_vweap_table ON crime.weap_cd = new_vweap_table.weap_cd LEFT JOIN new_vcrimecd_table ON crime.crim_cd = new_vcrimecd_table.crim_cd LEFT JOIN new_vpremis_table ON crime.premis_cd = new_vpremis_table.premis_cd LEFT JOIN new_varea_table ON crime.area = new_varea_table.area LEFT JOIN new_vstatus_table ON crime.status = new_vstatus_table.status WHERE new_vweap_table.weap_cd = 104.0;</td>
                        <td>0.001s</td>
                        <td>select id, weap_cd, weap_desc, crim_cd,crim_desc, premis_cd, premis_desc, area, area_name, status, status_desc, vict_sex, vict_age from crime where weap_cd = 104.0;</td>
                        <td>0.130s</td>
                    </tr>
                    <tr>
                        <td>Average Query Time</td>
                        <td>0.068s</td>
                        <td>Average Query Time</td>
                        <td>0.104s</td>
                    </tr>
                </tbody>
            </table>
        </section>
<section>
    <h1>Week 15 Plans</h1>
<p>
For week 15 we plan to look into memory mapping for more potential speed ups. To be continued.
</p>
</section>


    </div>
</body>
</html>
