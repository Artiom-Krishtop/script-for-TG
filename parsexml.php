<?
require_once 'init.php';

class XMLParse
{
   public $file;
   protected $xml = [];
   protected $key_words = [];
   protected $stop_words = [];
   protected $arItemLink = [];
   
   function __construct(array $xml_path, array $key_words, array $stop_words)
   {
      try{
         $time = time();

         $counter = 0;

         foreach ($xml_path as $key => $value) {

            $this->xml[$key] = simplexml_load_file($value);

            $counter++;

            if ($counter === 15) {

               $currentTime = time();

               $sleepTime = 60 - ($currentTime - $time);

               sleep($sleepTime);

               $counter = 0;

               $time = time();
            }

            if ($this->xml[$key] === false) {
               unset($this->xml[$key]);
               throw new Exception("File $value not found");
            }
         }

      }
      catch(Exception $e){
         echo $e->getMessage();
      }

      $this->file = 'links' . time() . '.scv';
      $this->key_words = $key_words;
      $this->stop_words = $stop_words; 
   }
   
   public function parseXml()
   {
      
      list($arItems, $arChannel) = $this->getParams();

      $flag = false;
      
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

                  $flag = true; 

                  $links = (string)$item->link;
                  $links .= ';';
                  file_put_contents($this->file, $links, FILE_APPEND);
               }
            }
         }
      }

      if ($flag) {
         return true;
      }else {
         return false;
      }
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
      $file = $nameChannel . '.txt';

      if(file_exists($file))
      {
         $lastId = file_get_contents($file);
         
         return $lastId;
      }

      return null;
   }

   protected function setLastItemId($channel, $itemId)
   {
      $file = $channel . '.txt';

      file_put_contents($file, $itemId);
   }
}







