jQuery(document).ready(function() {
   $("#button").on("click", function(){
      $.ajax({
         url: '/form.php',
         method: 'post',
         data: $('#form').serialize(),
         success: function(data){
            $('#message').css('display','block').html(data);
         }
      });
   });
   $('#message').on('click', function(){
      $(this).css('display','none').html();
   });
});