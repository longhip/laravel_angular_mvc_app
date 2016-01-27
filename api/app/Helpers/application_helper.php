<?php
function safestrtotime ($s) {
       $basetime = 0;
       if (preg_match ("/19(\d\d)/", $s, $m) && ($m[1] < 70)) {
               $s = preg_replace ("/19\d\d/", 1900 + $m[1]+68, $s);
               $basetime = 0x80000000 + 1570448;
       }
       return $basetime + strtotime ($s);
}

function stringToDate($var) {
	if (!empty($var)) {
		return implode("-", array_reverse(explode("/", $var)));
	} else {
		return $var;
	}

}

function dateToString($date = '') {
	if ($date != '' AND $date != '0000-00-00') {
		return date("d/m/Y", safestrtotime($date));
	} else {
		return $date;
	}
}

function dateToTime($var) {
	if (!empty($var)) {
		return safestrtotime(implode("-", array_reverse(explode("/", $var))));
	} else {
		return $var;
	}
}

function dateTimeToTime($var) {
	if (!empty($var)) {
  		$dateTime = explode('-', str_replace(' ', '', $var));
  		return safestrtotime(implode("-", array_reverse(explode("/", $dateTime[0]))) . ' ' . $dateTime[1]);
 	} else {
  		return $var;
 	}
}

function timeToDate($date = '') {
	if ($date != '') {
		return date("d/m/Y", $date);
	} else {
		return $date;
	}
}

function timeToDateTime($date = '') {
	if ($date != '') {
		return date("d/m/Y - H:i", $date);
	} else {
		return $date;
	}
}
function exportWord($text,$font,$size,$bold){
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $section = $phpWord->addSection();
//
//    $section->addText($text);
//
//    $section->addText('Hello world! I am formatted.',
//        array('name'=>'Tahoma', 'size'=>16, 'bold'=>true));


//    $phpWord->addFontStyle('myOwnStyle',
//        array('name'=>'Verdana', 'size'=>14, 'color'=>'1B2232'));
//    $section->addText('Hello world! I am formatted by a user defined style',
//        'myOwnStyle');

    $fontStyle = new \PhpOffice\PhpWord\Style\Font();
    $fontStyle->setBold($bold);
    $fontStyle->setName($font);
    $fontStyle->setSize($size);
    $myTextElement = $section->addText($text);
    $myTextElement->setFontStyle($fontStyle);

    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save('helloWorld.docx');
}