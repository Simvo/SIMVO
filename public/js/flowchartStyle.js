$(document).ready(function(){

  $(".card_content").mousedown(function(){
    $(this).addClass("grabbing-icon");
  });

  $(".card_content").mouseup(function(){
    $(this).removeClass("grabbing-icon");
  });
});
