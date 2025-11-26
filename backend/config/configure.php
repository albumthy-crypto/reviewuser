<?php
/* Database credentials. Assuming you are running MySQL server with default setting (user 'root' with no password) */
date_default_timezone_set("Asia/Phnom_Penh");
define("DB_SERVER", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
define("DB_NAME", "t24importdata");
// Check connection
// $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
if ($conn === false) {
    die("ERROR: Could not connect : " . mysqli_connect_error());
   // exit();
}

$url_load =
    (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on"
        ? "https"
        : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$date = date("Y-m-d");
$date_time = date("Y-m-d h:i");
//======================================= Insert Data  ===================================
function insertData($tbname, $insData)
{
    // $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,DB_PORT);
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
    $conn->set_charset("utf8");
    //prepare data
    $columns = implode("`,`", array_keys($insData));
    $cval = implode("','", array_values($insData));
    // insert data
    $sql =
        "INSERT INTO " .
        $tbname .
        " (`" .
        $columns .
        "`) VALUES ('" .
        $cval .
        "')";
    $res = $conn->multi_query($sql);
    if ($res === true) {
        $res = "00|success";
    } else {
        $res = "01|un-success - " . $conn->error . "=>" . $sql;
    }
    return $res;
    //$conn->close();
}
function insert($tbname, $data, $fd)
{
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
    $conn->set_charset("utf8");
    $sql = "INSERT INTO " . $tbname . " (" . $fd . ") VALUES (" . $data . ")";
    $res = $conn->multi_query($sql);
    if ($res === true) {
        $res = "00|success";
    } else {
        $res = "01|un-success - " . $conn->error;
    }
    return $res;
    //$conn->close();
}

function updateDepartment($dept_id, $dept_name, $group_app, $division_id)
{
    $host = "localhost";
    $dbname = "t24importdata";
    $username = "root";
    $password = "";

    try {
        // Establish a database connection
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Perform the database update
        $sql =
            "UPDATE departement SET dept_name = :dept_name, gp_app = :group_app, division_id = :division_id WHERE dept_id = :dept_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":dept_name", $dept_name);
        $stmt->bindParam(":group_app", $group_app);
        $stmt->bindParam(":division_id", $division_id);
        $stmt->bindParam(":dept_id", $dept_id);
        $stmt->execute();

        // Check if the update was successful
        if ($stmt->rowCount() > 0) {
            // Update successful
            return true;
        } else {
            // Update failed
            return false;
        }
    } catch (PDOException $e) {
        // Handle database connection error
        echo "Database connection failed: " . $e->getMessage();
    }
}

// Example usage
if (isset($_POST["btnUpdate"])) {
    $dept_id = $_POST["dept_id"];
    $dept_name = $_POST["dept_name"];
    $group_app = $_POST["gp_app"];
    $division_id = $_POST["division_id"];

    if (updateDepartment($dept_id, $dept_name, $group_app, $division_id)) {
        // Update successful
        header("Location: modify-dept.php");
    } else {
        // Update failed
        echo "Failed to update department.";
    }
}

function update($tbname, $data, $id)
{
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
    $conn->set_charset("utf8");
    $sql = "UPDATE " . $tbname . " SET " . $data . " WHERE " . $id . ";";
    $res = $conn->multi_query($sql);
    if ($res === true) {
        $res = "00|success";
    } else {
        $res = "01|un-success - " . $conn->error;
    }
    return $sql;
   // $conn->close();
}
//======================================= Update Data  ===================================
function updateData($tbname, $insData, $id)
{
    // $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,DB_PORT);
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
    //prepare data
    $conn->set_charset("utf8");
    $updata = "";
    foreach ($insData as $key => $val) {
        $updata = $updata . $key . "='" . $val . "',";
    }
    $updatas = substr($updata, 0, -1);
    // update data
    $sql = "UPDATE " . $tbname . " SET " . $updatas . " WHERE " . $id . ";";
    $res = $conn->multi_query($sql);
    if ($res === true) {
        $res = "00|success";
    } else {
        $res = "01|un-success - " . $conn->error . "=>" . $sql;
    }

    return $res;
  //  $conn->close();
}
//============================function get Data========================================
function delete($tbname, $cond)
{
    // $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,DB_PORT);
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
    $sql = "DELETE FROM " . $tbname . " WHERE " . $cond;
    $res = $conn->multi_query($sql);
    if ($res === true) {
        $res = "00|sucess";
    } else {
        $res = "01|un-sucess - " . $conn->error;
    }
    return $res;
   // $conn->close();
}
//============================function get Data with Array========================================
function getData($tbname, $cond)
{
    // $con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,DB_PORT);
    $con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
    if ($cond != "") {
        $sql = "SELECT * FROM " . $tbname . " WHERE " . $cond;
    } else {
        $sql = "SELECT * FROM " . $tbname;
    }
    $result = mysqli_query($con, $sql);
    $data = [];
    while ($row = mysqli_fetch_row($result)) {
        array_push($data, $row);
    }
    return json_encode($data);
    //return $sql;
   // $con->close();
}
function getDATASS($cond)
{
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
    if ($cond == "") {
        $sql =
            "SELECT p.*, pp.pms_menu_name AS pms_parent_name FROM `tbl_menu` p LEFT JOIN `tbl_menu` pp ON p.pms_parent_id = pp.pmsid";
    } else {
        $sql =
            "SELECT p.*, pp.pms_menu_name AS pms_parent_name FROM `tbl_menu` p LEFT JOIN `tbl_menu` pp ON p.pms_parent_id = pp.pmsid WHERE " .
            $cond;
    }
    $result = mysqli_query($conn, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($data, $row);
    }
    return json_encode($data);
   // $conn->close();
}
//============================function get Data With Assoc========================================
function getDataAssoc($tbname, $cond)
{
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
    if ($cond != "") {
        $sql = "SELECT * FROM " . $tbname . " WHERE " . $cond;
    } else {
        $sql = "SELECT * FROM " . $tbname;
    }
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Query execution failed: " . mysqli_error($conn));
    }
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($data, $row);
    }
    return json_encode($data);
  //  $conn->close();
}


function getReviewPeriods($conn) {
    $sql = "SELECT id, period_name, start_date, end_date, is_active 
            FROM review_periods 
            ORDER BY start_date DESC";
    $result = $conn->query($sql);

    $periods = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $periods[] = $row;
        }
    }
    return $periods;
}



function getDeptID()
{
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
    $result = mysqli_query(
        $conn,
        "SELECT (dept_id + 1) as deptid FROM departement order by dept_id desc LIMIT 1"
    );
    if (!$result) {
        die("Query execution failed: " . mysqli_error($conn));
    }
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($data, $row);
    }
    return json_encode($data);
    //$conn->close();
}

function getBranchID()
{
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
    $result = mysqli_query(
        $conn,
        "SELECT (id + 1) as brnid FROM tbl_branch order by id desc LIMIT 1"
    );
    if (!$result) {
        die("Query execution failed: " . mysqli_error($conn));
    }
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($data, $row);
    }
    return json_encode($data);
    
}

function getDivisionID()
{
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
    $result = mysqli_query(
        $conn,
        "SELECT (division_id + 1) as divisionid FROM division order by division_id desc LIMIT 1"
    );
    if (!$result) {
        die("Query execution failed: " . mysqli_error($conn));
    }
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($data, $row);
    }
    return json_encode($data);
}

function getPeriodID()
{
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
    $result = mysqli_query(
        $conn,
        "SELECT (Periodid + 1) as myPeriodid FROM tbl_review_period order by Periodid desc LIMIT 1"
    );
    if (!$result) {
        die("Query execution failed: " . mysqli_error($conn));
    }
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($data, $row);
    }
    return json_encode($data);
    
}
// get user id from database table

function getUSERIaaaD()
{
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
    $result = mysqli_query(
        $conn,
        "SELECT (id + 1) as id FROM login_user order by id desc LIMIT 1"
    );
    if (!$result) {
        die("Query execution failed: " . mysqli_error($conn));
    }
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($data, $row);
    }
    return json_encode($data);
    
}

function getUSERID()
{
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
    $result = mysqli_query(
        $conn,
        "SELECT MAX(id) AS max_id FROM login_user"
    );
    if (!$result) {
        die("Query execution failed: " . mysqli_error($conn));
    }
    $row = mysqli_fetch_assoc($result);
    $maxId = $row['max_id'];
    $nextAutoIncrement = $maxId !== null ? ($maxId + 1) : 1;
    return $nextAutoIncrement;
}


function getDataAssocbranch($tbname, $cond)
{
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
    if ($cond != "") {
        $sql = "SELECT * FROM " . $tbname . " WHERE " . $cond;
    } else {
        $sql = "SELECT * FROM " . $tbname;
    }

    // Modify the condition to add "co_code != 'kh0010001'" if the condition is not empty
    if ($cond != "") {
        $sql .= " AND co_code != 'kh0010001'";
    } else {
        $sql .= " WHERE co_code != 'kh0010001'";
    }

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Query execution failed: " . mysqli_error($conn));
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($data, $row);
    }

    $conn->close();

    return json_encode($data);
}
function getDisplayBranch($tbname, $cond)
{
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
    if ($cond != "") {
        $sql = "SELECT * FROM " . $tbname . " WHERE " . $cond;
    } else {
        $sql = "SELECT * FROM " . $tbname;
    }

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Query execution failed: " . mysqli_error($conn));
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($data, $row);
    }

    $conn->close();

    return json_encode($data);
}

// Select Table for HO......
function getDataAssocho($tbname, $cond)
{
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
    if ($cond != "") {
        $sql = "SELECT * FROM " . $tbname . " WHERE " . $cond;
    } else {
        $sql = "SELECT * FROM " . $tbname;
    }
    if ($cond != "") {
        $sql .= " AND co_code = 'KH0010001'";
    } else {
        $sql .= " WHERE co_code = 'KH0010001'";
    }
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Query execution failed: " . mysqli_error($conn));
    }
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($data, $row);
    }
    return json_encode($data);
   // $conn->close();
}

function getData_query($sql)
{
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Query execution failed: " . mysqli_error($conn));
    }
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($data, $row);
    }
    return json_encode($data);
  //  $conn->close();
}
function updateData_query($sql)
{
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Query execution failed: " . mysqli_error($conn));
    }
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($data, $row);
    }
    return json_encode($data);
   // $conn->close();
}
//===================Function Remove Special Characters======================
function re_special($data)
{
    $data = preg_replace("!\s+!", " ", $data);
    $data = preg_replace("/[^a-zA-Z0-9 ]/m", "", $data);
    return $data;
}
function remove_special_chars($arr)
{
    $result = [];
    foreach ($arr as $item) {
        $result[] = preg_replace("/[^a-zA-Z0-9\s]/", "", $item);
    }
    return $result;
}
//===================Function Remove only Space======================
function re_space($data)
{
    $data = preg_replace("!\s+!", " ", $data);
    return $data;
}
//===================function respone message======================
function responseSMS($data)
{
    // ***********************message fail*************************
    $err_message = '<div class="modal fade" id="err_msm" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content card" >
            <div class="card-body " style="padding: 50px"> 
                <div id="l_sms_body" class="text-center">
                    <i class="fas fa-times-circle text-danger" style=" text-align:center; font-size:50px !important; margin-bottom:10px;"></i>
                    <div id="message_detail"></div>
                    <button type="button" class="btn btn-danger mt-3" data-dismiss="modal">Failed</button>
                </div>
            </div>
        </div>        
    </div>
    </div>';
    // ***********************message Success*************************
    $succ_message = '<div class="modal fade" id="succ_msm" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content card" >
            <div class="card-body " style="padding: 50px"> 
                <div id="l_sms_body" class="text-center">
                    <i class="fas fa-check-circle text-success" style=" text-align:center; font-size:50px !important; margin-bottom:10px;"></i>
                    <div id="message_detail"></div>
                    <button type="button" class="btn btn-default mt-3" data-dismiss="modal">Done</button>
                </div>
            </div>
        </div>        
    </div>
    </div>';
    if ($data["resCode"] == "00") {
        $sms =
            '<script> $(document).ready(function(){$("#succ_msm").modal("show");$("#message_detail").html("' .
            $data["description"] .
            '");}); </script>' .
            $succ_message;
    } elseif ($data["resCode"] == "06") {
        $sms =
            '<script> $(document).ready(function(){$("#err_msm").modal("show");$("#message_detail").html("' .
            $data["description"] .
            '");}); </script>' .
            $err_message;
    } else {
        $sms =
            '<script> $(document).ready(function(){$("#err_msm").modal("show");$("#message_detail").html("' .
            $data["description"] .
            '");}); </script>' .
            $err_message;
    }
    return $sms;
}

function res_Message($type, $sta_code, $text)
{
    if ($text == "") {
        if ($type == "insert") {
            if ($sta_code == "00") {
                $type = "Insert successful";
            } else {
                $type = "Insert unsuccessful";
            }
        } elseif ($type == "update") {
            if ($sta_code == "00") {
                $type = "Update successful";
            } else {
                $type = "Update unsuccessful";
            }
        } elseif ($type == "delete") {
            if ($sta_code == "00") {
                $type = "Delete successful";
            } else {
                $type = "Delete unsuccessful";
            }
        }
    } else {
        $type = $text;
    }

    $body =
        '
       
        <div class="modal fade message_action">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="card-header">
                <h5 class="card-title"><i class="far fa-folder"></i>&nbsp;&nbsp;Messages</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p><i class="fas fa-info-circle"></i> ' .
        $type .
        ' </p>
            </div>
        </div>
        <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
        </div>
        ';
    $sms =
        '<script> $(document).ready(function(){$(".message_action").modal("show");}); </script>' .
        $body;
    return $sms;
}
?>