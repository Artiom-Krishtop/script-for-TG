<?

require_once 'parsexml.php';
require_once 'init.php';

$error =[];

if (isset($_REQUEST['KEY_WORDS']) && !empty($_REQUEST['KEY_WORDS'])) {
   $key_words = explode(',', $_REQUEST['KEY_WORDS']);
}else {
   $key_words = [];
}

if (isset($_REQUEST['STOP_WORDS']) && !empty($_REQUEST['STOP_WORDS'])){
   $stop_words = explode(',', $_REQUEST['STOP_WORDS']);
}else {
   $stop_words = [];
}

if (empty($_REQUEST['LINK'])) {
   $error[] = 'Отсутствует ссылка';
}else {
   $links = explode(',', $_REQUEST['LINK']);
}

if (empty($error)) {

   $xml = new XMLParse($links, $key_words, $stop_words);
   
   if($xml->parseXml()){
      echo $xml->file;
   }else {
      echo 'Поиск не дал результатов';
   }
}else {
   foreach ($error as $value) {
      echo $value;
   }
}


