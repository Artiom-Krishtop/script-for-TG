<?
function dd($text)
{
   echo '<pre>' . print_r($text, 1) . '</pre>';
   die();
}

function dnd($text)
{
   echo '<pre>' . print_r($text, 1) . '</pre><br>';
   echo '----------------------------------------------<br>';
}

$file_path = ['breakingmash.xml', 'mash.xml'];