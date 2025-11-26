<?php 
require_once __DIR__ . '../config/config_ho.php'; // Ensure your path is correct

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{  
	$selectedId = $_POST['review_period_id'];
				$branchCode = "KH0010001";
				$filteredBranch = null;

				$branch = getData("tbl_branch", "");
				$filteredBranch = array_filter($branch, fn($value): bool => $value->branch_code === $branchCode)[0] ?? null;

				if (!$filteredBranch) {
					exit(); // Branch with the specified code not found
				}

				$division = getData("division INNER JOIN `departement` ON division.division_id = departement.division_id", "");

				$dept = [];
				foreach ($division as $valss) {
					$dept[$valss->division_name][] = $valss->gp_app;
				}

                
				foreach ($dept as $divisionName => $gpAppList) {
					$deptList = implode("','", $gpAppList);
					$cond = "group_app IN ('" . $deptList . "') AND co_code='" . $filteredBranch->branch_code . "' AND Periodid = '".$selectedId."'";
					$employee = getData("tbl_importtest INNER JOIN departement ON tbl_importtest.group_app = departement.gp_app", $cond);
				
					// Sort the employee data by `group_app` (A-Z)
					usort($employee, function ($a, $b) {
						return strcmp($a->group_app, $b->group_app); // Compare group_app strings
					});
				
					$x = 0;
					$dt = array_map(function ($val) use (&$x) {
						return [
							++$x,
							$val->staff_id,
							$val->user_id,
							$val->user_name,
							str_replace("@", "[", $val->group_app) . '] ' . $val->dept_name,
							$val->start_date,
							$val->end_date,
							$val->start_time,
							$val->end_time,
							$val->email,
							" "
						];
					}, $employee);
				
					getpdf($filteredBranch->branch_code, $divisionName, $dt, $selectedId);
				}
				
					header("Location: ho.php");
}
				function getpdf($branch,$dept,$dt, $selectedId){
					// Specify the directory path where you want to save the file
					$directory_path = 'pdf/';

						// Instantiate a PDF object
						$pdf = new PDF();
						
						$pdf->isFinished = false;
						$pdf->AliasNbPages(); 
						// Column titles given by the programmer
						
						
						$header = array('ID','STAFF ID','USER ID','USER NAME','APPLICATION TITTLE','START DATE','END DATE','START TIME','END TIME','EMAIL','REMARK');
					
						// Get data from the text files
						$data = $dt;
						$pdf->SetFont('Arial', 'B', 10);
						// Add a new page
						$pdf->SetAutoPageBreak(true,25);
						$pdf->AddPage();
						// Set the font as required
						$pdf->SetFont('Arial', 'B', 10);
						$pdf->Cell(280,8,'REVIEWS USER T24 LIST IN HEAD OFFICE ',0,0,'C'); 
						//$pdf->SetFont('Arial', '', 8);
						$pdf->Ln(5);
						$date=date("Y-m-d");
						$pdf->Cell(270,16,'Date : '.$date,0,0,'C'); 
						$pdf->Ln(10);
						$pdf->SetFont('Arial', 'B', 8);
						if($branch=="KH0010001"){
							$directory_path = 'pdf/Head Office/';
							$pdf->Cell(28, 7, "DEPT : ".$dept, 0);
							$pdf->Ln(3);
						}
						
						$file_path = $directory_path .$branch . '_' . $dept . '_' . $selectedId . '.pdf';
						$pdf->Ln(5);
						$pdf->SetAutoPageBreak(true,25);
       					 $pdf->SetMargins(10, 5, 10); 
					
						$pdf->getSimpleTable($header,$data);

						// Add a new page below logo and titles
        				$pdf->SetAutoPageBreak(true, 30);
						$pdf->Output($file_path, 'F');
				}
      
?>