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
               unset($this->xml[$key]);
               throw new Exception("File $value not found");
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
      
      list($arItems, $arChannel) = $this->getParams();
      
      foreach ($arItems as $key => $items) {

         $channel = substr($arChannel[$key], strrpos($arChannel[$key], ':') + 1);

         $channel = trim($channel);
         
         $idLastItem = $this->getIdLastItem($channel);

         $counter = 0;

         foreach ($items as $item) {

            $counter++;

            $itemid = substr($item->link, strrpos($item->link, '/') + 1);

            if ($counter === 1) {
               $this->setLastItemId($channel, $itemid);
            }
            
            if ($itemid === $idLastItem) {
               break;
            }

            if ($this->keyWordExists($item->description)) {
               if (!$this->stopWordExists($item->description)) {
                  
                  $this->arItemLink[] = (string)$item->link;
               }
            }
         }
      }
      
      return $this->arItemLink;
   }

   protected function getParams()
   {
      $arItems = [];
      $arChannel = [];

      foreach ($this->xml as $key => $value) {

         $arItems[$key] = $value->channel->item;
         $arChannel[$key] = $value->channel->title;

      }

      return [$arItems, $arChannel];
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

   protected function getIdLastItem($nameChannel)
   {
      if(file_exists(self::LOG_LAST_ITEM_FILE))
      {
         $text = file_get_contents(self::LOG_LAST_ITEM_FILE);
         
         $arChannel= explode(';', $text);    
         
         foreach ($arChannel as $value) {
            
            $value = explode('_', $value);
            $chName  = array_shift($value);
            if ($chName === $nameChannel) {
               $lastId = array_shift($value);

               return $lastId;
            }
         }
      }

      return null;
   }

   protected function setLastItemId($channel, $itemId)
   {
      $item = $channel . '_' . $itemId;

      if (file_exists(self::LOG_LAST_ITEM_FILE)) {

         $text = file_get_contents(self::LOG_LAST_ITEM_FILE);
         
         $arChannel = explode(';', $text); 
         
         $channel_exists = false;
         
         foreach ($arChannel as $key => $value) {
            
            $value = explode('_', $value);
            $chName  = array_shift($value);

            if ($chName === $channel) {

               $arChannel[$key] = $item;

               $text = implode(';', $arChannel);

               file_put_contents(self::LOG_LAST_ITEM_FILE, $text);

               $channel_exists = true;

               break;
            }
         }
         
         if (!$channel_exists) {

            $text .= ';' . $item;

            file_put_contents(self::LOG_LAST_ITEM_FILE, $text);
         }
         
      }else{
         file_put_contents(self::LOG_LAST_ITEM_FILE, $item);
      }
   }
}

$a = new XMLParse($file_path, [ "Форбс",'приёмная семья','-семья','бизнесмен', "Сочи", '5-летний']);

$b = $a->parseXml();
dnd($b);







