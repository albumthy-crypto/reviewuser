<?php
require_once __DIR__ . '../config/config_br.php'; // Ensure your path is correct

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected ID from the form submission
    $selectedId = $_POST['review_period_id'];

    $branches = getData("tbl_branch", "");

    foreach ($branches as $branch) {
        if ($branch->branch_code !== "KH0010001") {
            $dt = [];
            $brid = $branch->branch_code;

            $employees = getData("tbl_importtest INNER JOIN departement ON tbl_importtest.group_app = departement.gp_app", "co_code = '".$brid."' AND Periodid = '".$selectedId."'");

            foreach ($employees as $index => $employee) {
                $gp_app = str_replace("@", "[ ", $employee->group_app) . ' ] ' . $employee->dept_name;
                $dt[] = [
                    $index + 1,
                    $employee->staff_id,
                    $employee->user_id,
                    $employee->user_name,
                    $gp_app,
                    $employee->start_date,
                    $employee->end_date,
                    $employee->start_time,
                    $employee->end_time,
                    $employee->email,
                    " "
                ];
            }

            getpdf($brid.'|'.$branch->branch_name, '', $dt, $selectedId);
            header("Location: section_Users.php");
        }
    }
}

    function getpdf($branch,$dept,$dt, $selectedId){
// Instantiate a PDF object

		$pdf = new PDF();

		$branch_name=explode('|',$branch);
		$pdf->isFinished = false;
		$pdf->AliasNbPages(); 
		// Column titles given by the programmer
		
		$header = array('ID','STAFF ID','USER ID','USER NAME','APPLICATION TITTLE','START DATE','END DATE','START TIME','END TIME','EMAIL','REMARK');
	
		// Get data from the text files
		$data = $dt;
		// Set the font as required
		$pdf->SetFont('Arial', 'B', 10);
		// Add a new page
        $pdf->SetAutoPageBreak(true, 25);
		$pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(280,10,'REVIEWS USER T24 LIST IN YOUR BRANCH ',0,0,'C'); 
		//$pdf->SetFont('Arial', '', 8);
		$pdf->Ln(5);
		$date=date("Y-m-d");
		$pdf->Cell(270,16,'Date : '.$date,0,0,'C'); 
		$pdf->Ln(10);

        
		$pdf->SetFont('Arial', 'B', 8);
		if($branch_name[0]!="KH0010001"){

			$directory_path = 'pdf/Branch/';
			$pdf->Cell(30, 7, "BRANCH : ".$branch_name[0], 0);
			$pdf->Ln(5);
			$pdf->Cell(30, 7, "BRANCH NAME : ".$branch_name[1], 0);
			$pdf->Ln(3);
		}
		$sanitized_branch_name = str_replace(' ', '_', $branch_name[1]); // Replace spaces with underscores
        $file_path = $directory_path . $branch_name[0] . '_' . $dept . '' . $selectedId . '_' . $sanitized_branch_name . '.pdf';
		
		$pdf->Ln(5);
		$pdf->SetAutoPageBreak(true,25);
        $pdf->SetMargins(10, 5, 10); 
		$pdf->getSimpleTable($header,$data);
        
        // Add a new page below logo and titles
        $pdf->SetAutoPageBreak(true, 30);
		//$pdf->AddPage();
		$pdf->Output($file_path, 'F');
		}

?>