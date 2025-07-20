<!DOCTYPE html>
<html>
<head>
<title>PHP CODE USED</title>
</head>

<body>
<pre>
<p>
try {
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
</p>
</pre>
</body>

</html>
