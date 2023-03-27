<?php
require "yapi.php";
require_once($_SERVER[DOCUMENT_ROOT]."/commun/Classes/PHPExcel.php");
require_once($_SERVER[DOCUMENT_ROOT]."/commun/Classes/PHPExcel/IOFactory.php");

$file_test="/home/www/stat/saisie/commande_four_v2/boncde/Matrice_LESIEUR.xls";
//$file_test="/home/www/stat/saisie/commande_four_v2/boncde/Matrice_LESIEUR.xlsx";

$excelReader = PHPExcel_IOFactory::load($file_test); //charge le fichier excel
// $NUM=$_REQUEST[NUM];
$cdes_num=$_REQUEST[cdes_num];



$excelReader->setActiveSheetIndex(1);

// Pour creation onglet DETAIL  ===============================
$qw="SELECT 
NUM, 
REFOU, 
REF, 
LIBEL, 
QTE/QCART AS QTE, 
QTE_FACTURE/QCART AS QTE_FACTURE 

FROM achat_web.CDE 
WHERE gf_societe='".$_SESSION[gf_societe]."' 
AND cdes_num='$cdes_num' 
";

$e[A]="NUM";
$e[B]="REFOU";
$e[C]="REF";
$e[D]="LIBEL";
$e[E]="QTE";
$e[F]="QTE_FACTURE";



$i=1;

/* foreach($e AS $k =>$v){
	$excelReader->getActiveSheet()->SetCellValue($k.$i, $v); 
}  */
$result=$ses->mquery($qw,"non","non") ;	//echo $qw;
$array_cde= array();
while($row=mysql_fetch_array($result)){  // $row[]
	$NUM=$row[NUM];
	$i++;
	foreach($e AS $k =>$v){
		$excelReader->getActiveSheet()->SetCellValue($k.$i, $row[$v]); 
	}
	$array_cde[$row[REFOU]] = $row[QTE];
}
$NUM=str_replace('/','_',$NUM);
$NUM=str_replace(' ','_',$NUM);
$excelReader->setActiveSheetIndex(0); 

//$i=6;
$i=8;
$fin = "non";

while($i<147){
	$ean = $excelReader->getActiveSheet()->getCell("D" . $i)->getValue(); 
	//$refou = intval($excelReader->getActiveSheet()->getCell("C" . $i)->getValue()); 
	$refou = intval($excelReader->getActiveSheet()->getCell("B" . $i)->getValue()); 
	// echo $refou ."<=<br>";
	 
	if(strlen($refou)>3 && array_key_exists($refou,$array_cde)) {//Mise à jour de la zone
		//echo $array_cde[$refou] ."------<br>";
	
		//$excelReader->getActiveSheet()->SetCellValue("H".$i, $array_cde[$refou]); 	
		$excelReader->getActiveSheet()->SetCellValue("M".$i, $array_cde[$refou]); 	
		
	} /* */
	$i++;	
}



$fichier="CDE_".$_SESSION[gf_societe]."_".$NUM.".xls";
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment;filename=$fichier");
header("Cache-Control: max-age=0");

$writer = PHPExcel_IOFactory::createWriter($excelReader, 'Excel2007'); 
$writer->save($_SERVER[DOCUMENT_ROOT]."/commun/document_pdf/BDC/$fichier");
if($_REQUEST[save]!="oui")
$writer->save('php://output');/*  */
?>