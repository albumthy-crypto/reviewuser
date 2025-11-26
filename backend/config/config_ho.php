<?php
/* Database credentials. Assuming you are running MySQL
 server with default setting (user 'root' with no password) */
//date_default_timezone_set('Asia/Phnom_Penh');
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 't24importdata');
/* Attempt to connect to MySQL database */

$en = 0;
$kh = 0;

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
// Check connection
if ($conn === false)
{
    die("ERROR: Could not connect. " . mysqli_connect_error());

}

//============================function get Data========================================
function getData($tbname, $cond)
{
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
    $conn->set_charset("utf8");
    if ($cond != '')
    {
        $sql = "SELECT * FROM " . $tbname . " WHERE " . $cond;

    }
    else
    {
        $sql = "SELECT * FROM " . $tbname;
    }

    $result = mysqli_query($conn, $sql);
    $data = array();
    while ($row = mysqli_fetch_object($result))
    {
        array_push($data, $row);
    }
    return $data;
    //return $sql;
    //$conn->close();
    
}

require ('fpdf/fpdf.php');
// Extend the FPDF class to create a custom class with landscape orientation
class PDF extends FPDF
{
    public $isFinished; // Define the $isFinished property
    public $nextPageTopMargin; // Add this property
    function __construct($orientation = 'L', $unit = 'mm', $size = 'A4')
    {
        parent::__construct($orientation, $unit, $size);

    }

      // Page header 
     function Header() { 
                  // Display the logo only on the first page
                    if ($this->PageNo() == 1) 
                        {
                        // Add logo to the first page
                         $this->Image('fpdf/Cana.png', 10, 8, 45); 
                         }
                        // (Optional) You can still add header text if needed
                        $this->SetFont('Arial','B',9); 
                        $this->Ln(10); // Add some space after header
                         
                    } 
    
         // Page footer 
       function Footer() { 
                        // If this is the final page, add your signatures section
                        if($this->isFinished) {
                            $this->Ln(5); // move a bit up
                            $this->SetFont('Arial','B',8);
                            
                            $this->Cell(0,0,'Acknowledged by:',0,0,'L');
                            $this->SetX(-206);
                            $this->Cell(0,0,'Checked and Review by (IT):',0,0,'C');
                            $this->SetX($this->GetX() - 65);
                            $this->Cell(0, 0, 'IT Dept Signature:', 0, 0, 'C');

                            $this->Ln(5);
                            $this->SetFont('Arial','',8); 
                            $this->Cell(0,0,'Manager Signature',0,0,'L');
                            $this->SetX(-410);

                            $this->Ln(20);
                            $this->SetFont('Arial','',7);
                            $this->Cell(0,0,'Date   :......................',0,0,'L');
                            $this->SetX($this->GetX() - 65);
                            $this->Cell(0,0,'Date   :......................',0,0,'C');
                           
                            $this->SetX(-210);
                            $this->Cell(0,0,'Date   :.....................',0,0,'C');
                            
                            $this->Ln(8);
                            $this->Cell(0,0,'Name :......................',0,0,'L');
                            $this->SetX($this->GetX() - 65);
                            $this->Cell(0,0,'Name :......................',0,0,'C');
                            
                            $this->SetX(-210);
                            $this->Cell(0,0,'Name :......................',0,0,'C');
                        }
                            // Position 15 mm from bottom
                            $this->SetY(-10); 
                                                    
                             // Set font
                            $this->SetFont('Arial','I',6); 
                                                    
                            // Always show page number, centered
                            $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0, 'C'); 

                     }


    // Get data from the text file
    function getDataFrmFile($file)
    {
        // Read file lines
        $lines = file($file);
        // Get a array for returning output data
        $data = array();
        // Read each line and separate the semicolons
        foreach ($lines as $line) $data[] = explode(';', chop($line));
        return $data;
    }

   
    
    function getSimpleTable($header, $data) {
        $maxColumns = 17;
        $numColumns = min(count($header), $maxColumns);
        
        // Dimensions of A4 landscape in mm
        $pageWidth = 297; // A4 width in landscape
        $pageMargin = 10; // Margin on both sides
        $usableWidth = $pageWidth - 2 * $pageMargin;
        
        // Calculate the maximum width needed for each column
        $colWidth = array_fill(0, $numColumns, 0);
        for ($i = 0; $i < $numColumns; $i++) {
            // Measure header width
            $headerWidth = $this->GetStringWidth($header[$i]) + 2; // Adding padding
            $colWidth[$i] = max($colWidth[$i], $headerWidth);
            
            // Measure data width
            foreach ($data as $row) {
                if (isset($row[$i])) {
                    $cellWidth = $this->GetStringWidth($row[$i]) + 2; // Adding padding
                    $colWidth[$i] = max($colWidth[$i], $cellWidth);
                }
            }
        }
        
        // Check if total width exceeds usable width and adjust proportionally
        $totalWidth = array_sum($colWidth);
        if ($totalWidth > $usableWidth) {
            $scaleFactor = $usableWidth / $totalWidth;
            foreach ($colWidth as &$width) {
                $width *= $scaleFactor;
            }
        }
        
        // Set the starting position to the left margin
        $this->SetX($pageMargin);
    
        $this->SetFont('Arial', 'B', 6);
        // Display the header
        for ($i = 0; $i < $numColumns; $i++) {
            $this->Cell($colWidth[$i], 7, $header[$i], 1, 0, 'C');
        }
        $this->Ln();
        
        $this->SetFont('Arial', '', 6);
        // Display the data
        foreach ($data as $row) {
            $this->SetX($pageMargin); // Set X position to left margin for each row
            for ($i = 0; $i < count($colWidth); $i++) {
                // Determine alignment for specific columns
                $alignment = in_array($i, [0, 1, 5, 6, 7, 8, 10]) ? 'C' : 'L';
                $this->Cell($colWidth[$i], 6, isset($row[$i]) ? $row[$i] : '', 1, 0, $alignment);
            }
            $this->isFinished = false; // Indicate row processing
            $this->Ln(); // Move to next line
        }
        $this->isFinished = true;
    }
     
    // Get styled table
    function getStyledTable($header, $data)
    {

        // Colors, line width and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');

        // Header
        $colWidth = array(
            30,
            35,
            44,
            40,
            30,
            40,
            35,
            40,
            30,
            30
        );
        for ($i = 0;$i < count($header);$i++) $this->Cell($colWidth[$i], 6, $header[$i], 1, 0, 'C', 1);
        $this->Ln();
        // Setting text color and color fill
        // for the background
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 0;
            foreach ($data as $row) {
                for ($i = 0; $i < count($row); $i++) {
                    $this->Cell($colWidth[$i], 6, $row[$i], 'LR', 0, 'C', $fill);
                }
                $this->Ln();
                $fill = !$fill;
            }
            $this->Cell(array_sum($colWidth), 0, '', 'T');
                    
                }
            }

            
    ?>