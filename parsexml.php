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

$file_path = ['breakingmash.xml'];

class XMLParse
{
   const LOG_LAST_ITEM_FILE = 'channel.txt';

   protected $xml = [];
   protected $key_words = [];
   protected $stop_words = [];
   protected $arItemLink = [];
   
   function __construct(array $xml_path, array $words)
   {
      try{
         foreach ($xml_path as $key => $value) {
            $this->xml[$key] = simplexml_load_file($value);

            if ($this->xml[$key] === false) {
               throw new Exception('File' . $value .  'not found');
               unset($this->xml[$key]);
            }
         }

      }
      catch(Exception $e){
         echo $e->getMessage();
      }
      

      $this->parseWords($words);
   }
   
   protected function parseWords($words)
   {
      foreach ($words as $key => &$value) 
      {
         if (strpos($value, '-') === 0) 
         {
            $this->stop_words[] = substr($value, 1);
            unset($words[$key]);
         }
      }

      $this->key_words = $words;
   }
   
   public function parseXml()
   {
      // $idLastItem = $this->getIdLastItem();
      $arItems = $this->getItems();
      dd($arItems);
      
      foreach ($arItems as $item) {
         if ($this->keyWordExists($item->description)) {
            if (!$this->stopWordExists($item->description)) {
               
               $this->arItemLink[] = (string)$item->link;
            }
         }
      }
      
      return $this->arItemLink;
   }

   protected function getItems()
   {
      $arItems = [];

      foreach ($this->xml as $key => $value) {
         $arItems[] = $value->channel->item;
         dnd($arItems);
      }

      return $arItems;
   }
   
   protected function keyWordExists(string $description)
   {

      foreach ($this->key_words as $value) {
         
         if(mb_stripos($description, $value) !== false)
         {
            return true; 
         }
      }
      
      return false;
   }
   
   protected function stopWordExists(string $description)
   {
      foreach ($this->stop_words as $value) {
         if(mb_stripos($description, $value) !== false)
         {
            return true; 
         }
      }

      return false;
   }

   protected function getIdLastItem()
   {
      if(file_exists(self::LOG_LAST_ITEM_FILE))
      {
         $file = file_get_contents(self::LOG_LAST_ITEM_FILE);

         $arrItems = explode(';', $file);    
         dd($arrItems);     
      }
   }
}

$a = new XMLParse($file_path, [ "Форбс", 'форбс','приёмная семья','-семья', "Сочи", '-5-летний']);

$b = $a->parseXml();
dnd($b);







