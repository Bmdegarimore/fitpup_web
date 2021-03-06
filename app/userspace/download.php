<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
//require_once dirname(__FILE__) . '../Classes/PHPExcel.php';
require_once"assets/Classes/PHPExcel.php";
//require ($_SERVER['DOCUMENT_ROOT'].'/app/userspace/assets/Classes/PHPExcel.php');
require "dbcon.php";
//require ($_SERVER['DOCUMENT_ROOT'].'/app/userspace/dbcon.php');
require "model/downloadModel.php";
//require($_SERVER['DOCUMENT_ROOT'].'/app/userspace/model/downloadModel.php');
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();



// Set document properties
$objPHPExcel->getProperties()->setCreator("Woofster Tech Team")
							 ->setLastModifiedBy("Woofster Tech Team")
							 ->setTitle("Office 2007 XLSX Email List")
							 ->setSubject("Office 2007 XLSX Email List")
							 ->setDescription("Woofster Email List")
							 ->setKeywords("Woofster Emails Contacts Users")
							 ->setCategory("Email List");

$db = connect();
$dbmodel = new DownloadModel($db);
$users = $dbmodel->selectAllUsers();

                        

//Set Row Titles

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Users');

$rowCount = 2;

// Add some data
while($row = $users->fetch(PDO::FETCH_ASSOC)){
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $rowCount, $row['username']);
        
    $rowCount++;
}

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Email List')
							  ->getColumnDimension('A')->setWidth(30);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition:attachment;filename="EmailList.xlsx"');
header('Cache-Control:max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control:max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;