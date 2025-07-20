<!DOCTYPE html>
<html>
<head>
<title>Week 13 Report</title>
</head>

<body>
<br>
<div style="display:block; top:2%; position:absolute; right: 0; width:30%; border:3px solid black;" class="solaire">
    <h2 style="text-align:center;"> ARTEMIS </h2>
    <img src="solaire-php-upload.png" style="display:block; margin-bottom:10px;">
    <img src="solaire-date-2023.png" style="display:block;">
    <br>
    <img src="solaire-date-2024.png" style="display:block;">
    <br>
    <img src="solaire-date-2025.png" style="display:block;">
    <br>
    <img src="solaire-date-graph.png" style="display:block;">
</div>
<div style="display:block; top:2%; position:absolute; left: 2px; width:30%; border:3px solid black;" class="solaire">
    <h2 style="text-align:center;"> SOLAIRE </h2>
    <img src="artemis-php-upload.png" style="display:block; margin-bottom:10px;">
    <img src="artemis-php-upload-transaction.png">
    <br>
    <img src="artemis-date-2023.png" style="display:block; width:80%;">
    <br>
    <img src="artemis-date-2024.png" style="display:block; width:80%;">
    <br>
    <img src="artemis-date-2025.png" style="display:block; width:80%;">
    <br>
    <img src="artemis-date-graph.png" style="display:block; width:80%;">
</div>

<div style="display:block; position:absolute; right:38vw; width:20%;">
<a href="mysql_code.php" style="font-size:20px;">SQL Code Used</a>
<a href="php_code.php" style="font-size:20px; display:flex;">PHP Code Used</a>
    <h2 style=""> ENGINE CONNECT </h2>
    <img src="connect-to-solaire.png" style="display:block; width:80%;">
<p style="position:absolute; font-size:0.8vw;";>Research:  

Horizontal partitioning helps by breaking a big table into smaller chunks based on rows. This makes it faster to find and work with data, and easier to manage as the database grows. Some of our research links are included below. 

</p>
<p style="position:absolute; top:160%; font-size:0.8vw;";>
Accomplishments so far: 

We implemented some horizontal partitioning for the LA crime data recieved from one of our classes.  We then tested the time it took to run certain queries, then graphed them as shown above. Our create table queries and inserts are also included above. 

We made a horizontal partitioning for ID in the hpart_crime table and a horizontal partition in the hpart_date_crime table. We noticed that as the rows pulled increases the benefits of the horizontal partitioning decreases. The graph itself in a time vs rows acquired resembles a square root graph. The total data size of the rows was gotten through a query and we do plan to do veritcal partitioning off of these rows. 
<br>
We also connected from artemis to solaire which had horrible run time for a select all statement.
</p>
<p style="position:absolute; top:310%; font-size:0.8vw;";> 
Week 14 Goals: 

We hope to complete some vertical partitioning as well as work with some memory mapping. If we can, we will try to play around with the Connect engine that is already setup but did not have enough time to look into.</p>

    <img src="data_size.png" style="display:block; width:120%; position:absolute; top:370%;">
    
    <h3 style="position:absolute; top:450%;"> REFERENCE LINKS </h3>
<a style="position:absolute; top:475%; right:-5vw;" href="https://dba.stackexchange.com/questions/242833/mariadb-connect-storage-engine">https://dba.stackexchange.com/questions/242833/mariadb-connect-storage-engine</a>
<a style="position:absolute; top:500%; right: 6vw;" href="https://www.pingcap.com/article/sql-partition-demystified-from-concept-to-implementation/">https://www.pingcap.com/article/sql-partition-demystified-from-concept-to-implementation/</a>
<a style="position:absolute; top:540%; right:4vw;" href="https://www.geeksforgeeks.org/database-sharding-a-system-design-concept/">https://www.geeksforgeeks.org/database-sharding-a-system-design-concept/</a>
<a style="position:absolute; top:570%; right:-5vw;" href="https://www.w3schools.com/sql/func_mysql_substring.asp">https://www.w3schools.com/sql/func_mysql_substring.asp</a>
<a style="position:absolute; top:590%; right: -0.5vw;" href="https://dev.mysql.com/doc/refman/8.4/en/storage-requirements.html">https://dev.mysql.com/doc/refman/8.4/en/storage-requirements.html</a>
<a style="position:absolute; top:620%; right: -4.5vw;" href="https://www.php.net/manual/en/pdo.begintransaction.php">https://www.php.net/manual/en/pdo.begintransaction.php</a>
</div>

</body>

</html>
