"use strict";
$(function () {
    var toggle = 0;
    var popUpForUpdateDish = $(".popupForEditDish");
    var allButtonsDivs = $(".dishSection");
    var getAllProd = $(".sectionForProd");
    var allDsihes = [];
    var newDish = [];
    allButtonsDivs.each(function () {
      $.post("DataBase.php", { getDishesButton: $(this).attr("name") }).done(
        function (data) {
          var dishesWithSameType = jQuery.parseJSON(data);
          dishesWithSameType.forEach((dish) => {
            allDsihes.push(dish);
            allButtonsDivs.filter('[name = "' +dish.typeOfMeal + '"]')
            .append("<button class =getDishData>"+dish.dish+"</button>");
          });
        }
      );
    });
    function closeNewDishPopup() {
          $(".popupForNewDish").css({ display: "none" });
          $(".addprods").css({ display: "none" });
          $(".prodQuantityUpdateWrap").css({ display: "none" });
          getAllProd.find("button").remove();
          $(".dishContain").find("button").remove();
          $(".prodQuantityUpdate").find("label").remove();
          $(".dishName").val("");
          $(".dishType").val("");
          $(".price").val("");
          newDish = [];
    };
    function closeNewProdPopup(){
       $(".addNewProduct").css({ display: "block" });
       $(".addNewProdPopupWrap").css({ display: "none" });
       $(".confirmAddProd").css({ display: "none" });
       $(".addProdName").val("");
       $(".prodType").val("");
       $(".addProdPrice").val("");
       $(".addProdQuantity").val("");
       $(".addProdAlert").val("");
    }
    $("h2").on("click",function () {
        $(this)
          .nextAll()
          .toggle(toggle++ % 2 === 0);
      });
      $(".closeEditDishForm").on("click",function(){
        $(".popupForEditDishWrap").css({ display: "none" });
        $(".approveForDeleteDish").css({ display: "none" });
        popUpForUpdateDish.css({ display: "none" });
        popUpForUpdateDish.find(".showP").remove();
        popUpForUpdateDish.find("h6").remove();
        popUpForUpdateDish.find("span").children().remove();
      });
      $("body").delegate(".getDishData","click", function () {
        popUpForUpdateDish.css({display:"block"});
        $(".popupForEditDishWrap").css({ display: "block" });
        var dishName = $(this).html();
        var dishProds = [];
        allDsihes.forEach((dish)=>{
          if (dish.dish === dishName) {
            dishName = dish;
          }
        });
        $.post("DataBase.php", { getDataForDish: dishName.dish}).done(function(data){
          popUpForUpdateDish.find("table").before("<h6>" + dishName.dish + "<h6/>");
          var dishProdsF = jQuery.parseJSON(data);
          dishProdsF.forEach(function(prod){
            dishProds.push(prod);
            popUpForUpdateDish.find("table").append(
              "<tr class=showP><td>" +
                prod.prodName +
                "</td><td class=showP><input type= number step=0.01 value =" +
                prod.quantityOfProd +
                " /></td><td class=showP><button class=removeProdFromDish value=" +
                prod.productId +
                ">הסר</button></td><td class=showP><button class=updateQuantityValForDish value=" +
                prod.productId +
                ">עדכן</button><td/></tr>"
            );
        });
        popUpForUpdateDish
          .find("span")
          .append('<div><p>' + dishName.price + " מחיר המנה <p/><div><input type=number min='0' /><button class=updateDishPrice>עדכן מחיר חדש<button/><button class=deleteDish >הסר מנה<button/><div/><div/>"); 
          });
      });
      $(".addDish").on("click",function () {
          $(".popupForNewDish").css({display:"block"});
          $(".addName").css({ display: "block" });
      });
      $(".closeAddForm").on("click", function () {
          closeNewDishPopup();
      });
      $(".confirmNameForNewDish").on("click",function () {
          if ($(".dishType").val() !== "" && $(".dishName").val()!==''&&$(".price").val()!=='') {
            var newNameForDish = {
              DishName: $(".dishName").val(),
              DishType: $(".dishType").val(),
              DishPrice: $(".price").val(),
            };
            newDish.push(newNameForDish);
            $(this).prev().val("");
            $(".addName").css({ display: "none" });
            $(".addprods").css({ display: "block" });
            $(".flexB").css({ display: "flex" });
            getAllProd.each(function () {
              $.post("DataBase.php", {
                getProdByName: $(this).attr("name"),
              }).done(function (data) {
                var prods = jQuery.parseJSON(data);
                prods.forEach((prod) => {
                  $(".sectionForProd")
                    .filter('[name = "' + prod.ProductType + '"]')
                    .append(
                      "<button class = putProdInDiv prodId=" +
                        prod.ProductId +
                        ">" +
                        prod.ProductName +
                        "</button>"
                    );
                });
              });
            });
          } else {
            alert("לא רשמת את נתוני מנה ");
          }
      });
      $("body").delegate(".putProdInDiv","click",function () {
          var prodAlreadyInside = true;
          var newProd = {
            prodName: $(this).html(),
            prodId: $(this).attr("prodId"),
          };
          for (var i = 1; i < newDish.length; i++) {
            if (newProd.prodId === newDish[i].prodId) {
              prodAlreadyInside = false;
              return;
            }
          };
          if (prodAlreadyInside){
                     $(".dishContain").append(
                       "<button class=outProdFromDive>" +
                         $(this).html() +
                         "</button>"
                     );
              newDish.push(newProd);
          }
      });
      $("body").delegate(".outProdFromDive","click",function () {
          var index;
          newDish.forEach((prod,indx)=>{
              if(prod.prodName === $(this).html()){
                $(this).remove();
                index = indx;
              }
          })
          newDish.splice(index, 1);
      })
      $("body").delegate(".confirmProds","click",function () {
          if(newDish.length == 1){
              alert("לא הכנסת מוצרים");
          }
          else{
              $(".prodQuantityUpdateWrap").css({display:"block"});
              $(".addprods").css({ display: "none" });
              for(var i = 1;i<newDish.length;i++){
                  $(".prodQuantityUpdate").append(
                    "<label>" +
                      newDish[i].prodName +
                      ": <input min=0 name=" +
                      newDish[i].prodName +
                      ' class=prodQuanUpdate type=number step=0.01  /> ק"ג</label>'
                  );
              }
          }
      });
      $("body").delegate(".addNewDish","click",function () {
          var notUpdateQuantity = true;
          $(".prodQuanUpdate").each(function () {
              for(var i =0;i<newDish.length;i++){
                  if(newDish[i].prodName === $(this).attr("name")){
                      if($(this).val() === ''){
                          notUpdateQuantity = false;
                          alert("לא הזנת כמות במוצר " + $(this).attr("name"));
                      }
                      else{
                          Object.assign(newDish[i], {
                            Quantity: $(this).val(),
                          });
                      }
                  }
              }          
          });
          if (notUpdateQuantity){ 
              var strNewDish = JSON.stringify(newDish);
              $.post("DataBase.php", { newDish: strNewDish }).done(function (Data) {
                    var theNewDish = jQuery.parseJSON(Data);
                    console.log(theNewDish.DishName);
                    allButtonsDivs
                      .filter('[name = "' + theNewDish.DishType + '"]')
                      .append(
                        "<button class =getDishData>" +
                          theNewDish.DishName +
                          "</button>"
                      );
                      closeNewDishPopup();
              });
          }
      });
      $(".addNewProd").on("click",function () {
          $(".addNewProdPopupWrap").css({display:"block"});
      });
          $(".closeProdAddForm").on("click", function () {
            closeNewProdPopup();
          });
          $(".approveAddNewProd").on("click",function(){
              if($(".addProdName").val() !== "" && $(".prodType").val() !== "" 
              && $(".addProdPrice").val() !== "" && $(".addProdQuantity").val() !== ""
              && $(".addProdAlert").val() !== ""){
                  $(".addNewProduct").css({display:"none"});
                  $(".confirmAddProd").css({display:"block"});
              }
              else{
                  alert("לא הזנת את כל הנתונים להוספת מוצר חדש");
              }
          });
          $(".no").on("click",function () {
               $(".addNewProduct").css({ display: "block" });
               $(".confirmAddProd").css({ display: "none" });
          });
          $(".yes").on("click",function(){
              var newProd = {
                name: $(".addProdName").val(),
                type: $(".prodType").val(),
                price: $(".addProdPrice").val(),
                quantity: $(".addProdQuantity").val(),
                alert: $(".addProdAlert").val(),
              };
              var newProdStr = JSON.stringify(newProd);
              $.post("DataBase.php", { addNewProd: newProdStr }).done(function(){
                closeNewProdPopup();
              });
          }) 
          //הסרת מוצר ממנה 
          $("body").delegate(".removeProdFromDish", "click", function () {
            var prodName = $(this).val();
            var dishName = $(this)
              .parent()
              .parent()
              .parent()
              .parent()
              .parent()
              .find("h6")
              .html();
            $(this).closest("tr").remove();
            $.post("DataBase.php", {
              removeProdFromDish: prodName,
              disnNameToRemove: dishName,
            });
          });
          $("body").delegate(".updateQuantityValForDish","click",function(){
            var quantityToUpdate = {
              quantity: $(this).parent().parent().find("input").val(),
              prodId: $(this).val(),
              dishName: $(this)
                .parent()
                .parent()
                .parent()
                .parent()
                .parent()
                .find("h6")
                .html(),
            };
            $.post("DataBase.php", {
              updateQuanForDish: quantityToUpdate,
            }).done(function () {
              alert("העדכון הצליח");
            });
          });
          $("body").delegate(".updateDishPrice","click",function(){
            var updateDishPrice = {
              price: $(this).parent().parent().find("input").val(),
              dish: $(this)
                .parent()
                .parent()
                .parent()
                .parent()
                .parent()
                .find("h6")
                .html(),
            };
            allDsihes.forEach((dish) => {
              if (dish.dish === updateDishPrice.dish) {
                dish.price = updateDishPrice.price;
              }
            });
            $.post("DataBase.php", { updateDishPrice: updateDishPrice }).done(
              function () {
                alert("העדכון הצליח");
              }
            );
            popUpForUpdateDish
              .find("span")
              .find("p").remove();
              popUpForUpdateDish
                .find("span")
                .prepend("<p>" + updateDishPrice.price + " מחיר המנה <p/>");
          });
          $("body").delegate(".deleteDish", "click", function () {
            $(".approveForDeleteDish").css({ display: "block" });
            popUpForUpdateDish.css({ display: "none" });
          });
          $("body").delegate(".noDeleteDish", "click", function () {
            $(".approveForDeleteDish").css({ display: "none" });
            popUpForUpdateDish.css({ display: "block" });
          });
          $("body").delegate(".yesDeleteDish", "click", function (){
            $.post("DataBase.php", {
              dishToDelete: popUpForUpdateDish.find("h6").html(),
            }).done(function(){
              $(".dishSection").find("button").filter(function(){
                if($(this).html() == popUpForUpdateDish.find("h6").html()){
                  $(this).remove();
                }
              });
              alert("המנה הנמחקה");
            });
          })
});