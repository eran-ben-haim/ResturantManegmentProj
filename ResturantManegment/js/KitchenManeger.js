    'use strict';
     $(document).ready(function () {
       var orders = [];
       var ordersThatCame = [];
       var popUpDish = $(".popUpDish");
       function getAllOrdersInPopUp() {
         $(".orders").empty();
         if (orders.length === 0) $(".orders").append("<tr><td colspan =4>לא בוצעו הזמנות</td></tr>");
         else
           orders.forEach((order) => {
             $(".orders").append(
               "<tr><td>" +
                 order.productName +
                 "</td><td> " +
                 order.supllierName +
                 "</td><td><input  size=1 class=valueToUpdateQuantity type='number' value=" +
                 order.quantityToUpdate +
                 " /></td><td><button class='removeOrderFromList' value=" +
                 order.productId +
                 ">הסר</button><button class='updateOrdersList' value=" +
                 order.productId +
                 ">עדכן</button></td></tr>"
             );
           });
       }
       //גפתור האיקס של הפופאפ סוגר את החלון
       $(".closeForm").on("click", function () {
         $(".layoutUP").css({ display: "none" });
         $(".popUpProductToUpdata").css({ display: "none" });
       });
       // כפתור פותח את הפופ אפ של עדכון או הזמנה של מוצר
       $(".popupForProducts").on("click", function () {
         $(".layoutUP").css({ display: "block" });
         $(".popUpProductToUpdata").css({ display: "block" });
         var prodId = $(this).attr("value");
         var oldCapacity = $(this).attr("capacity");
         $("#verifyiedProd").attr("value", prodId);
         $("#inputcapacity12").attr("value", oldCapacity);
         $("#quanToUpDate").val("");
         $.post("postsAndAjax/kitchenAjax.php", { prodid: prodId }).done(
           function (data) {
              $(".listOfSuplliers").html(data);
             }
         );
       });
       //הוספת הזמנה למערך ההזמנות
       $("body").delegate(".orderApprove", "click", function () {
         var timeOfOrder = new Date();
         var stringTimeToOrder = timeOfOrder.toDateString();
         var order = {
           supllierName: $(this).attr("name"),
           quantityToUpdate: $(".quantityToAdd").val(),
           productId: $(this).attr("value"),
           timeOfOrder: stringTimeToOrder,
           productName: $(this).attr("prodName"),
         };
         orders.push(order);
         $(".popUpProductToUpdata").css({ display: "none" });
         $(".layoutUP").css({ display: "none" });
       });
       //כפתור הפותח את רשימת ההזמנות
       $("#getOrderList").on("click", function () {
         $(".listOrdersPopUp").css({ display: "block" });
         $(".overlayListOrders").css({ display: "block" });
         getAllOrdersInPopUp();
       });
       //כפתור שסוגר את הפופ אפ של רשימת ההזמנות
       $(".closeListOrdersForm").on("click", function () {
         $(".listOrdersPopUp").css({ display: "none" });
         $(".overlayListOrders").css({ display: "none" });
       });
       //כפתור לעדכון כמות ההזמנה
       $("body").delegate(".updateOrdersList", "click", function (e) {
         orders.forEach((order) => {
           if (order.productId === $(this).val())
             order.quantityToUpdate = $(".valueToUpdateQuantity").val();
         });
         e.preventDefault();
       });
       //כפתור להסרת ההזמנה לפני שהיא נשלחת
       $("body").delegate(".removeOrderFromList", "click", function (e) {
         var orderToRemove = 0;
         orders.forEach((order) => {
           if (order.productId === $(this).val()) {
             orders.splice(orderToRemove, 1);
           }
           orderToRemove++;
         });
         getAllOrdersInPopUp();
         e.preventDefault();
       });
       //שליחת ההזמנות החדשות לבקאנד
       $(".sentOrders").on("click", function (e) {
         var ordersString = JSON.stringify(orders);
         $(".noOrders").remove();
         $.post("postsAndAjax/kitchenAjax.php", {
           orders12: ordersString,
         }).done(function (data) {
           var dataForOrderButton = JSON.parse(data);
           dataForOrderButton.forEach((order)=>{
             $(".tableOfordersForApprove").append(
               "<tr><td>" +
                 order.supllierName +
                 "</td><td>" +
                 order.timeOfOrder +
                 "</td><td>" +
                 order.productName +
                 "</td><td><input size-1 type= number value =" +
                 order.quantityToUpdate +
                 "></td><td><button class='approveOr'>אישור</button></td></tr>"
             );
           });
         });
         orders = [];
         $(".listOrdersPopUp").css({ display: "none" });
         $(".overlayListOrders").css({ display: "none" });
       });
       // פתיחת פופ אפ של הזמנות שכבר נשלחו לספק ומחכות להגיע
       $("#approveOrders").on("click", function () {
         $(".popUpForApproveOrders").css({ display: "block" });
         $(".overlay").css({ display: "block" });
       });
       $(".closeApproveOrderForm").on("click", function () {
         $(".popUpForApproveOrders").css({ display: "none" });
         $(".overlay").css({ display: "none" });
       });
       //אישור הגעה של משלוחים
       $("body").delegate(".approveOr","click", function () {
         var prod = $(this).closest("td").prev().prev().html();
         var supllierName = $(this)
           .closest("td")
           .prev()
           .prev()
           .prev()
           .prev()
           .html();
         var date = $(this).closest("td").prev().prev().prev().html();
         var quantity = $(this).closest("td").prev().children().val();
         var orderToPush = {
           prod: prod,
           supllierName: supllierName,
           date: date,
           quantity: quantity,
         };
         var orderToPushString = JSON.stringify(orderToPush);
         ordersThatCame.push(orderToPush);
         $.post("postsAndAjax/kitchenAjax.php", {
           orderThatCame: orderToPushString,
         }).done(function(data){
          var dataForButton = jQuery.parseJSON(data);
             $(".popupForProducts")
               .filter('[name = "' + dataForButton.ProductId + '"]')
               .find("span")
               .html(
                 dataForButton.ProductCapacity +
                   ' ק"ג ' +
                   dataForButton.ProductName
               );
         });
         $(this).closest("tr").remove();
       });
      //  כפתור המאשר הוספת כמות לעדכון ידני
       $("#verifyiedProd").on("click", function (e) {
         e.preventDefault();
         var prodId = $(this).val();
         var quantityToUpdate = document.getElementById("quanToUpDate").value;
         console.log(quantityToUpdate);
         if (quantityToUpdate != 0) {
           $.post("postsAndAjax/kitchenAjax.php", {
             handleUpdate: prodId,
             handleUpdateQuantity: quantityToUpdate,
           }).done(function (data) {
             var dataForButton = jQuery.parseJSON(data);
             $(".popupForProducts")
               .filter('[name = "' + dataForButton.id + '"]')
               .find("span")
               .html(dataForButton.quantity + ' ק"ג ' + dataForButton.name);
           });
                 $(".layoutUP").css({ display: "none" });
                 $(".popUpProductToUpdata").css({ display: "none" });
         } else {
           alert("לא הוספת כמות לעדכון");
         }
       });
      //  כפתור הפותח את הפופ אפ של הניהול מנות
      $("#showPopUpDish").on("click",function(){
        $(".overlayDish").css({display:"block"});
         $(".popUpDish").css({ display: "flex" });
      });
      // כפתור הסוגר פופ אפ ניהול הזמנות 
      $(".closeDishForm").on("click",function(){
        $(".overlayDish").css({ display: "none" });
        $(".popUpDish").css({ display: "none" });
        popUpDish.find(".containerForDishType1").css({ display: "flex" });
        popUpDish.find(".containerForDishType2").css({ display: "flex" });
        popUpDish.find(".showDishesContainer").css({ display: "none" });
        popUpDish.find(".backToType").css({ display: "none" });
      });
      // הכפתור שמביא את המנות מאותו סוג
      $(".dishSection").on("click",function(){
        var iconLink;
        if ($(this).val() == "first") {
          popUpDish.find("h5").html("ראשונות");
          iconLink = "img/breakfast.png";
        } else if ($(this).val() == 'salad') {
          iconLink = "img/salad.png";
          popUpDish.find("h5").html("סלטים");
        } else if ($(this).val() == "main") {
          iconLink = "img/meal.png";
          popUpDish.find("h5").html("עקריות");
        } else if ($(this).val() == "desert") {
          iconLink = "img/ice-cream.png";
          popUpDish.find("h5").html("קינוחים");
        } else {
          iconLink = "img/drink.png";
          popUpDish.find("h5").html("משקאות");
        }
        popUpDish.find(".containerForDishType1").css({display:"none"});
        popUpDish.find(".containerForDishType2").css({ display: "none" });
        popUpDish.find(".showDishesContainer").css({ display: "block" });
        popUpDish.find(".backToType").css({display:"block"});
        $.post("postsAndAjax/kitchenAjax.php",{getAllDishFromSameType:$(this).val()}).done(function(data){
          var dishesData = jQuery.parseJSON(data);
          popUpDish.find(".showDishes").html("");
          dishesData.forEach((dish)=>{
            popUpDish
              .find(".showDishes")
              .append(
                "<button value='" +
                  dish.dish +
                  "' class=changeAv available=" +
                  dish.available +
                  "> <img  src=" +
                  iconLink +
                  " alt='תמונה של קערת סלט'>" +
                  dish.dish +
                  "</button>"
              );
          })
        });
      });
      // כפתור חזרה לסוגי מנות
      popUpDish.find(".backToType").on("click",function(){
        popUpDish.find("h5").html("סוגי מנות");
        popUpDish.find(".containerForDishType1").css({ display: "flex" });
        popUpDish.find(".containerForDishType2").css({ display: "flex" });
         popUpDish.find(".showDishesContainer").css({ display: "none" });
         popUpDish.find(".backToType").css({ display: "none" });
      });
      // כפתור לשינוי סטטוס של מנה
      popUpDish.delegate(".changeAv","click",function(){
        if ($(this).attr("available")=="1"){
          $(this).attr("available","2");
        }
        else{
          $(this).attr("available", "1");
        }
        $.post("postsAndAjax/kitchenAjax.php", {
          updateAv: $(this).attr("available"),
          dishName: $(this).val(),
        });
      });
     });
