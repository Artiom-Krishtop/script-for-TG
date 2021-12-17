<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
   <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
   <script src="script.js"></script>
   <title>Document</title>
</head>
<body>
   <div class="container">
      <div class="row align-items-center justify-content-center ">
         <div class="col-md-4">
            <form method="post" id="form">
               <div class="mb-3">
                  <label for="LINK" class="form-label" required>Введите ссылки для скачивания</label>
                  <textarea name="LINK" cols="40" placeholder="Введите ссылки для скачивания через запятую. Пример: http://example"></textarea>
               </div>
               <div class="mb-3">
                  <label for="KEY_WORDS" class="form-label" >Введите ключевые слова</label>
                  <textarea name="KEY_WORDS" cols="40" placeholder="Введите ключевые слова через запятую"></textarea>
               </div>
               <div class="mb-3">
                  <label for="STOP_WORDS" class="form-label" >Введите стоп-слова</label>
                  <textarea name="STOP_WORDS" cols="40" placeholder="Введите стоп-слова через запятую"></textarea>
              </div>
              <button type="button" id="button" class="btn btn-primary">Отправить</button>
            </form>
            <div class="alert alert-info mt-3" role="alert" id="message" style="display: none;"></div>
         </div>
      </div>
   </div>
</body>
</html>