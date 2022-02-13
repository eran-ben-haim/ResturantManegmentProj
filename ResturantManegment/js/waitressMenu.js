'use strict';
$(function(){
    var categories = $(".categories");
    var showDish = $(".showDish");
    var dishesWithSameType;
    var choosenDish;
    var dishProdsF;
    categories.children().on("click", function () {
        showDish.css({display:"block"});
        $.post("postsForAddOrders.php", { getDishes: $(this).attr("name") }).done(function(data){
          dishesWithSameType = jQuery.parseJSON(data);
          showDish.children().children("button").remove();
          dishesWithSameType.forEach((dish) => {
            showDish
              .children()
              .append("<button class = getDishData>" + dish.dish + "</button>");
          });
        });      
    });
    showDish.delegate(
      ".getDishData",
      "click",
      function () {
        dishesWithSameType.forEach((dish) => {
          if (dish.dish === $(this).html()) {
            choosenDish = dish;
          }
        });
           $.post("postsForAddOrders.php", {
             getDataForDish123: choosenDish.dish,
           }).done(function (data) {
               console.log(jQuery.parseJSON(data));
           });
      });
    });
