<?php
require_once("DB\DataBase.php");
?>
<!DOCTYPE html>
<html lang="hb" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/kitchenManegerActivity.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-trxYGD5BY4TyBTvU5H23FalSCYwpLA0vWEvXXGm5eytyztxb+97WzzY+IWDOSbav" crossorigin="anonymous">
        <script defer src="js/KitchenManeger.js"></script>
    <title>Document</title>
  <script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
    </script>
</head>

<body>
    <header>
        <div>
            <h1>פעולות מנהל מטבח</h1>
        </div>
    </header>
    <main>
        <h2>מוצרים</h2>
        <div>
            <!-- הצגת כל המוצרים מסוג בשרי -->
            <h3>בשרי</h3>
                <?php
            $db= dbClass::GetInstance();
            $showingMeatProduct=$db->showProduct('meat');
            echo $showingMeatProduct;
            ?>
         </div>
        <div>
            <!-- הצגת כל המוצרים מסוג חלבי -->
            <h3>חלבי</h3>
                   <?php
            $db= dbClass::GetInstance();
            $showingDairyProduct=$db->showProduct('dairy');
            echo $showingDairyProduct;
            ?>
        </div>
        <div>
            <!-- הצגת כל המוצרים מסוג פרווה -->
            <h3>פרווה</h3>
                   <?php
            $db= dbClass::GetInstance();
            $showingParveProduct=$db->showProduct('carbs');
            echo $showingParveProduct;
            ?>
        </div>
        <div>
            <!-- הצגת כל המוצרים מסוג ירקה -->
            <h3>ירקות</h3>
                <?php
            $db= dbClass::GetInstance();
            $showingvegetableProduct=$db->showProduct('vegetable');
            echo $showingvegetableProduct;
            ?>
            <!-- <button class="btn btn-secondary">בשר</button>
            <button class="btn btn-secondary">fgfd</button> -->
        </div>
        <div>
            <!-- הצגת כל המוצרים מסוג דגים -->
            <h3>דגים</h3>
                   <?php
            $db= dbClass::GetInstance();
            $showingfishProduct=$db->showProduct('fish');
            echo $showingfishProduct;
            ?>
        </div>
        <div>
            <!-- הצגת כל המוצרים מסוג משקה -->
            <h3>משקאות</h3>
                   <?php
            $db= dbClass::GetInstance();
            $showingbeverageProduct=$db->showProduct('beverage');
            echo $showingbeverageProduct;
            ?>
        </div>
    </main>
                     <!-- רשימת ההזמנות מוצרים -->
    <div class="overlayListOrders">
        <div class="listOrdersPopUp">
            <button class='closeListOrdersForm'>&KHcy;</button>
                 <div class="listOrders">
                     <div class=setOverflow>
                    <table class="ordersTable" >
                        <thead>
                        <th>שם המוצר</th>
                        <th>ספק</th>
                        <th>כמות</th>
                        <th></th>
                        </thead>
                        <tbody class="orders" > </tbody>
                    </table>
                    </div>
                <button class="sentOrders">אישור</button>  
            </div>
        </div>      
    </div>
    <!-- אישור הגעה מוצרים מהספק -->
    <div class="overlay" >
        <div class='popUpForApproveOrders'>
                <button class='closeApproveOrderForm'>&KHcy;</button>
            <div class='over'>
                <table class='tableOfordersForApprove'>
                    <th>שם ספק</th>
                    <th>תאריך</th>
                    <th>מוצר</th>
                    <th>כמות </th>
                    <th>אישור</th>
                    <?php
                                $var= $db->getAllOrdersToApprove();
                        if($var!= 1){
                        foreach($var as $order){
                            echo "<tr><td>"
                            .$order['SupllierName']."</td><td>"
                            .$order['DateOfOrder']."</td><td>"
                            .$order['ProductName']."</td><td><input type= number value ="
                            .$order['shipment']."></td><td><button class='approveOr'>אישור</button></td></tr>";
                        }}
                        else   
                            echo "<tr><td colspan=5 class='noOrders'>אין הזמנות שלא הגיעו מהספק</td></tr>"
                    ?> 
                </table> 
            </div>      
        </div>
    </div>
    <!-- פופ אפ כניסה לפעולות במוצר  -->
    <div class=layoutUP>
<div class="popUpProductToUpdata">
      <div class="updataProduct" >  
    <button class="closeForm">Х</button>
      <h5>עדכון מוצר ידני</h5>
    <form method="post" class="formUpdataProduct">
       <table class="tableProductToUpdata">
        <th>כמות עדכנית</th>
        <th>כמות לעדכון</th>
        <tr>
        <td><input id="inputcapacity12" name="inputcapacity" type="number" placeholder="1.0" step="1" min="0" readonly>
        <td><input type="number" placeholder="1.0" step="1" min="0" id="quanToUpDate"> </td>         
        </tr> 
        <tr>
            <td colspan="2"><button id="verifyiedProd" type="submit"  >אישור</button></td>
        </tr>  
    </table>
    <h5>הזמנה מספק </h5>
     <table class="tableForAddOrder">
        <figcaption>רשימת ספקים למשלוח</figcaption>
        <tr  class='listOfSuplliers'>
</tr>
    </table>
    </form>
    </div>
</div>
</div>
<!-- פופ אפ של ניהול מנות  -->
<div class=overlayDish>
    <div class=popUpDish> 
        <button class='closeDishForm'>&KHcy;</button> 
        <h5>סוגי מנות</h5> 
        <div class=containerForDishType1>
            <button class=dishSection value=first>ראשונות</button>
            <button class=dishSection value=salad>סלטים</button>
            <button class=dishSection value=main>עיקריות</button>
            </div> 
            <div class=containerForDishType2>
            <button class=dishSection value=desert>קינוחים</button>
            <button class=dishSection value=drink>משקאות</button>  
            </div> 
            <div class=showDishesContainer>
                <div class=showDishes></div>
            </div>    
            <button class = backToType>חזור</button>             
    </div>
</div>
    <footer>
        <button  id="approveOrders">אישור הגעה מספק</button>
        <button  id="getOrderList">רשימת הזמנות</button>
        <button id="showPopUpDish">ניהול מנות</button>
        <a href="cashier.php">יציאה</a>       
    </footer>
    <!-- קריאה לספריות בוטסטראפ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>