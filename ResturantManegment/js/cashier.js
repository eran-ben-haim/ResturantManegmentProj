"use strict";
//העברת המספרים לאינפוט של המחשבון
function insertvalCal(val) {
  const claculatorPassword = document.getElementById("caluclatorPass");
  if (claculatorPassword.value !== "0" && claculatorPassword.value != "Error")
    claculatorPassword.value += val;
  else claculatorPassword.value = val;
}
//איפוס הקוד במחשבון
function setDisplayexitWorkerCal(val) {
  document.getElementById("caluclatorPass").value = val;
}
//העברת המספרים לאינפוט של המחשבון
function insertvalCalTable(val) {
  const claculatorPassword = document.getElementById("addTablePass");
  if (claculatorPassword.value !== "0" && claculatorPassword.value != "Error")
    claculatorPassword.value += val;
  else claculatorPassword.value = val;
}
//איפוס הקוד במחשבון
function setDisplayTableCal(val) {
  document.getElementById("addTablePass").value = val;
}
$(function(){
  // סלקטורים ראשיים
  //כל הקונטיינק התחתון שמכיל כפתורים בקופה
  var bottom = $(".bottom");
  // כל הכפתורים של השולחנות
  var tabelsButtons = $(".btn");
  var main = $('main');
  // כל הכפתורים שבצד של הקופה
  var sideNav = $(".side");
  //הפופ אפ של המחשבון
  var calculator = $(".calculator");
  var overlayCalculator = $(".overlay");
  // פופ אפ מחשבון הוספת שולחן
  var tableCalcOverlay = $(".overlayAddTable");
  var tableCalc = tableCalcOverlay.find(".calculatorTable");
  //אתחול כפתורים שלא נעשה בהם שימוש
  // function resetButtons() {
  //   if (tabelsButtons[0].hasAttribute("swapTe")) {
  //     tabelsButtons.removeAttr("swapTe");
  //     console.log(12);
  //   }
  // }

  // var resetBotoomNav = setInterval(resetButtons, 10000);
  // resetBotoomNav;
  // tabelsButtons.draggable({cancel:false,containment:'parent'});
  // הצגת הפופ אפ של המחשבון והעברת הנתונים לפוסט מסגירת קופה
  bottom.find("#zexit").on("click", function () {
    //הצגת המחשבון
    calculator.css({ display: "block" });
    overlayCalculator.css({ display: "block" });
    //הכנסת הערכים כדי שהדאטא תעבור לפוסט
    calculator.find(".calcClass").attr("name", "closeCashierPass");
    calculator.find(".calcApprove").attr("name", "closeCashierSubmit");
  });
  // הצגת הפופ אפ של המחשבון והעברת הנתונים לפוסט יציאת עובדה
    bottom.find("#exit").on("click", function () {
      //הצגת המחשבון
      overlayCalculator.css({ display: "block" });
      calculator.css({ display: "block" });
      //הכנסת הערכים כדי שהדאטא תעבור לפוסט
      calculator.find(".calcClass").attr("name", "exitresult");
    });
  //פעולות מנהל פופ אפ  
     sideNav.find("#manegerexit").on("click", function () {
       //הצגת המחשבון
       overlayCalculator.css({ display: "block" });
       calculator.css({ display: "block" });
       //הכנסת הערכים כדי שהדאטא תעבור לפוסט
       calculator.find(".calcClass").attr("name", "");
     });
  //כניסת עובד
      sideNav.find("#workerstartshift").on("click", function () {
        //הצגת המחשבון
        overlayCalculator.css({ display: "block" });
        calculator.css({ display: "block" });
        //הכנסת הערכים כדי שהדאטא תעבור לפוסט
        calculator.find(".calcClass").attr("name", "entranceresult");
      });
  //כפתור לכניסה למחשבון לדף התפריט    
      sideNav.find("#menubot").on("click", function () {
        //הצגת המחשבון
        overlayCalculator.css({ display: "block" });
        calculator.css({ display: "block" });
        //הכנסת הערכים כדי שהדאטא תעבור לפוסט
        calculator.find(".calcClass").attr("name", "goToMenu");
      });
  //כניסה למחשבון למנהל מטבח    
      sideNav.find("#kitchenbutton").on("click", function () {
        //הצגת המחשבון
        overlayCalculator.css({ display: "block" });
        calculator.css({ display: "block" });
        //הכנסת הערכים כדי שהדאטא תעבור לפוסט
        calculator.find(".calcClass").attr("name", "kitchenManegerPas");
        calculator.find(".calcApprove").attr("name", "toKitchenMangerPage");
      });


  //סגירת הפופ אפ של המחשבון
  calculator.find("#closeCalculator").on("click", function () {
    calculator.css({ display: "none" });
    overlayCalculator.css({ display: "none" });
    calculator.find("#caluclatorPass").val("");
    // איפוס נתונים
    calculator.find(".calcClass").removeAttr("name");
    calculator.find(".calcApprove").removeAttr("name");
  });

  //העברת מידע לכפתורים חשבון זמני,חשבון סופי,והחלפת שולחן
  $("body").delegate(".btn", "click", function () {
    //בודק האם כבר עבר מידע לכפתורים
    if (!$(this).attr("swapTe")) {
      bottom.find("#tempcheck").val($(this).val());
      bottom.find("#finalcheck").val($(this).val());
      bottom.find("#TableSwap").val($(this).val());
    } else {
      //במקרה וזאת החלפה נכנס לבסיס הנתונים
      var table = parseInt($(this).attr("swapTe"), 10);
      $.post("postsAndAjax/cashierAjax.php", {
        swapTable: table,
        actualTable: $(this).val(),
      }).done(function (data) {
        // במקרה ואפשרי להחליף יוצר במקום הכפתורים הישניפ כפתורים חדשים התואמים לבסיס הנתונים
        if (data !== -1) {
          var dataForSwitchTabels = jQuery.parseJSON(data);
          $(".btn")
            .filter("[value=" + dataForSwitchTabels.tableNum + "]")
            .replaceWith(
              "<button type='button' value = " +
                dataForSwitchTabels.tableNum +
                " class= 'btn btn-secondary' style='color:black'>" +
                " " +
                dataForSwitchTabels.iconForTabelNum +
                " שולחן " +
                dataForSwitchTabels.tableNum +
                "<button>"
            );
          $(".btn")
            .filter("[value=" + dataForSwitchTabels.tableToSwitch + "]")
            .replaceWith(
              "<button type='button' value = " +
                dataForSwitchTabels.tableToSwitch +
                " class= 'btn btn-secondary' style='color:black'>" +
                dataForSwitchTabels.empName +
                " " +
                dataForSwitchTabels.iconForSwitchTable +
                " " +
                dataForSwitchTabels.tableToSwitch +
                " שולחן<button>"
            );
          // מעדכן את הכפתורים החדשים בפונקציונאליות שלהם
          $(".getTableNum").delegate(".btn", "dblclick", function () {
            //הצגת המחשבון
            overlayCalculator.css({ display: "block" });
            calculator.css({ display: "block" });
            //הכנסת הערכים כדי שהדאטא תעבור לפוסט
            calculator.find(".calcClass").attr("name", "passForWMenu");
            calculator.find(".calcApprove").attr("name", "goToWaitresMenu");
            calculator.find(".calcApprove").val($(this).val());
          });
        }
      });
      // מנקה את הדאטא מהכפתורי שולחן 
      $(".btn").removeAttr("swapTe");
    }
  });
  //הצגת המחשבון לכניסה להזמנה לשולחנות
  tabelsButtons.dblclick(function () {
    //הצגת המחשבון
    overlayCalculator.css({ display: "block" });
    calculator.css({ display: "block" });
    //הכנסת הערכים כדי שהדאטא תעבור לפוסט
    calculator.find(".calcClass").attr("name", "passForWMenu");
    calculator.find(".calcApprove").attr("name", "goToWaitresMenu");
    calculator.find(".calcApprove").val($(this).val());
  });
  //העברת ערכים לכול הכפתורים של השולחנות להעברת שולחן
  bottom.find("#TableSwap").on("click", function () {
    $(".btn").attr("swapTe", $(this).val());
  });
  // הורדת הפופ אפ של ההודעה
  $("body").delegate(".backToTheApp", "click", function () {
  $(".containerwrongpassword").css({display:"none"});
  });
// הכפתור לפופ אפ להוספת שולחן
  sideNav.find("#addtablebutton").on("click",function(){
    tableCalc.css({display:"block"});
    tableCalcOverlay.css({display:"block"});
  });
  // כפתור לסגירת הפופאפ של הוספת שולחן
  tableCalc.find("#closeCalculatorTable").on("click",function(){
    tableCalc.find("#addTablePass").val("");
    tableCalc.css({ display: "none" });
    tableCalcOverlay.css({ display: "none" });
  });
  // כפתור לאישור הסיסמא להוספת שולחן
  tableCalc.find("#approvePassForTable").on("click",function(e){
    $.post("postsAndAjax/cashierAjax.php", {
      passForSwitchTable: tableCalc.find("#addTablePass").val(),
    }).done(function(data){
      if(data == 1){
        tableCalc.css({ display: "none" });
        tableCalcOverlay.find(".chooseAreaDiv").css({ display: "flex" });
      }
      else{
        alert("אין לך אישור להוסיף שולחן או סיסמה שגויה");
      }
    });
    e.preventDefault();
  });
  // כפתור האיקס של הפופ אפ הוספת שולחן אחרי שהכנסת את הקוד ועשית אישור
  tableCalcOverlay.find("#closeChooseTable").on("click",function(){
    tableCalcOverlay.css({display:"none"});
    tableCalc.find("#addTablePass").val("");
  });
  // הכפתור שבוחר היכן להוסיף את השולחן
  tableCalcOverlay.find(".addTable").on("click",function(){
    $.post("postsAndAjax/cashierAjax.php" , {addTable: $(this).val()}).
    done(function (data) {
      // בדיקה שהשולחן נוסף בהצלחה
      if(data !== 99)
      {
        var dataForTabels = jQuery.parseJSON(data);
        // מיון להיכן להוסיף את השולחן לפי המידע מהבקאנד וההוספה של השולחן
        if (dataForTabels[0] === 'garden')
        {
          main
            .find("#outside")
            .append(
              "<span class=getTableNum><button class='btn btn-secondary' value = " +
                dataForTabels[1] +
                " style = 'color:black'> "+
                dataForTabels[2]+            
                dataForTabels[1] +
                " שולחן</button></span>"
            );
        } 
          else if(dataForTabels[0] === 'bar'){
                main
                  .find("#bar")
                  .append(
                    "<span class=getTableNum><button class='btn btn-secondary' value = " +
                      dataForTabels[1] +
                      " style = 'color:black'> " +
                      dataForTabels[2] +
                      dataForTabels[1] +
                      " שולחן</button></span>"
                  );
          }
            else if (dataForTabels[0] === "patio") {
                  main
                    .find("#pattio")
                    .append(
                      "<span class=getTableNum><button class='btn btn-secondary' value = " +
                        dataForTabels[1] +
                        " style = 'color:black'> " +
                        dataForTabels[2] +
                        dataForTabels[1] +
                        " שולחן</button></span>"
                    );
            }
              else if (dataForTabels[0] === "mainhall") {
                        main
                          .find("#inside")
                          .append(
                            "<span class=getTableNum><button class='btn btn-secondary' value = " +
                              dataForTabels[1] +
                              " style = 'color:black'> " +
                              dataForTabels[2] +
                              dataForTabels[1] +
                              " שולחן</button></span>"
                          );
              }
              // הוספת הפונקציה לכפתור החדש שנוסף
           $(".getTableNum").delegate(".btn", "dblclick", function () {
             //הצגת המחשבון
             overlayCalculator.css({ display: "block" });
             calculator.css({ display: "block" });
             //הכנסת הערכים כדי שהדאטא תעבור לפוסט
             calculator.find(".calcClass").attr("name", "passForWMenu");
             calculator.find(".calcApprove").attr("name", "goToWaitresMenu");
             calculator.find(".calcApprove").val($(this).val());
           });      
      }
      else{
        alert("אין מספיק שולחנות באזור");
      }
    });
    tableCalcOverlay.css({ display: "none" });
    tableCalc.find("#addTablePass").val("");
  });
   bottom
     .find("#zexit")
     .css({ transition: "all 500ms ease-in-out" });
});

