<?
require_once 'init.php';

class XMLParse
{
   protected $file;
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

      $this->file = 'resultD' .date('m_d_y') . 'T' . date('H_i_s') . '.scv';
      $this->key_words = $key_words;
      $this->stop_words = $stop_words; 
   }

   public function getFileName()
   {
      return $this->file;
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

            if (empty($this->key_words)) {
               $flag = true; 

               $this->setResults($item);
            }

            if ($this->keyWordExists($item->description)) {
               if (!$this->stopWordExists($item->description)) {

                  $flag = true; 

                  $this->setResults($item);
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

   protected function setResults($item)
   {
      $links = (string)$item->link;
      $links .= ';';
      file_put_contents('result/' . $this->file, $links, FILE_APPEND);
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

         $description = strip_tags($description);

         $value = str_replace(['\\','.', '/'], ['\\\\','\.','\/',], $value);

         $pattern = '/\b' . $value .'\b/ui';
         
         if(preg_match($pattern, $description))
         {
            return true; 
         }
      }
      
      return false;
   }
   
   protected function stopWordExists(string $description)
   {
      foreach ($this->stop_words as $value) {

         $description = strip_tags($description);

         $value = str_replace(['\\','.', '/'], ['\\\\','\.','\/',], $value);

         $pattern = '/\b' . $value .'\b/ui';
         
         if(preg_match($pattern, $description))
         {
            return true; 
         }
      }

      return false;
   }

   protected function getIdLastItem($nameChannel)
   {
      $file = 'cache/' . $nameChannel . '.txt';

      if(file_exists($file))
      {
         $lastId = file_get_contents($file);
         
         return $lastId;
      }

      return null;
   }

   protected function setLastItemId($channel, $itemId)
   {
      $file = 'cache/' . $channel . '.txt';

      file_put_contents($file, $itemId);
   }
}







