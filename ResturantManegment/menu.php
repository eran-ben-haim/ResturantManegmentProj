<!DOCTYPE html>
<html lang="hb">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/menu.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-trxYGD5BY4TyBTvU5H23FalSCYwpLA0vWEvXXGm5eytyztxb+97WzzY+IWDOSbav" crossorigin="anonymous">
        <script defer src="js/menu.js"></script>
    <title>Document</title>
      <script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
    </script>
</head>
<body>
    <header><h1>תפריט</h1></header>
    <main>
        <div class = "dishSection" name="first">
            <h2>מנות ראשונות</h2>
        </div>
        <div class = "dishSection" name="main">
            <h2>מנות עיקריות</h2>
        </div>
        <div class = "dishSection" name="desert">
            <h2>קינוחים</h2>
        </div>
        <div class = "dishSection" name="salad">
            <h2>סלטים</h2>
        </div>
        <div class = "dishSection" name="drink">
            <h2>משקאות</h2>
        </div>
        <div class = popupForEditDishWrap>
            <button class='closeEditDishForm'>&KHcy;</button>
            <div class= popupForEditDish>
            <h3>מתן אתה חציל</h3>
                <table>
                    <th>שם המוצר</th>
                    <th>כמות במנה</th>
                    <th>אפשרויות</th>
                </table>
                <span></span>
            </div>
        <div class=approveForDeleteDish>
            <p>האם אתה בטוח שאתה רוצה להסיר את המנה</p>
            <button class=yesDeleteDish>כן</button>
            <button class=noDeleteDish>לא</button>
        </div>
        </div>
        <div class=popupForNewDish dir=rtl>
            <button class='closeAddForm'>&KHcy;</button>
            <h3>מנה חדשה</h3>
            <div class=addName>
                <h4>שם המנה</h4>
                <input class=dishName type="text" placeholder="שם המנה">
                   <p>מנות:
        <select class="dishType" name="typeOfM">
            <option value=""></option>
            <option label="ראשונות" value="first"></option>
            <option label="עיקריות" value="main"></option>
            <option label="קינוחים" value="desert"></option>
            <option label="סלטים" value="salad"></option>
            <option label="משקאות" value="drink"></option>
        </select>
      </p>    
                <input class="price" type="number" min =0 placeholder="מחיר">
                <button class=confirmNameForNewDish>אישור</button>
            </div>
            <div class="addprods">
                <h4>מוצרים</h4>
                <div class=flexB>
                    <div class =sectionForProd name="meat"><h5>בשר</h5></div>
                    <div class =sectionForProd name="dairy"><h5>חלב</h5></div>
                    <div class =sectionForProd name="carbs"><h5>פחמימה</h5></div>
                    <div class =sectionForProd name="vegetable"><h5>ירק</h5></div>
                    <div class =sectionForProd name="fish"><h5>דג</h5></div>
                    <div class =sectionForProd name="beverage"><h5>משקאות</h5></div>
                </div>
                <div class=dishContainWrap>
                    <h4>מוצרים שמכילה המנה</h4>
                    <div class=dishContain></div>
                    <button class=confirmProds>אישור</button>
                </div>
            </div>
               <div class=prodQuantityUpdateWrap>
                    <h4>כמות כל מוצר שהמנה מכילה</h4>
                    <div class=prodQuantityUpdate></div>
                    <button class= addNewDish>אישור</button>
                </div>
    </div>
    <div class=addNewProdPopupWrap>
        <button class='closeProdAddForm'>&KHcy;</button>
        <h4>הוספת מוצר חדש</h4>
        <div class= addNewProduct>
            <input class="addProdName" type="text" placeholder="שם המוצר">
                               <p>סוגי מוצר:
        <select class="prodType" name="typeOfM">
            <option value=""></option>
            <option label="בשרי" value="meat"></option>
            <option label="חלבי" value="dairy"></option>
            <option label="פחמימה" value="carbs"></option>
            <option label="ירק" value="vegetable"></option>
            <option label="דגים" value="fish"></option>
            <option label="משקאות" value="beverage"></option>
        </select>
      </p>  
      <input class="addProdPrice" type="number" step=0.01  placeholder="מחיר מוצר לקילו">
      <input class="addProdQuantity" type="number" step=0.01 placeholder="כמות המוצר שישנה כעת במסעדה">
      <input class="addProdAlert" type="number" step=0.01 placeholder="כמות להתרעה על חוסר במוצר">
      <button class=approveAddNewProd>אישור</button>
        </div>
        <div class=confirmAddProd>
            <p>??האם אתה בטוח</p>
            <button class = "yes">כן</button>
            <button class = "no">לא</button>
        </div>
    </div>
    </main>
    <footer>
        <button class="btn btn-secondary btn-sm"><a href="cashier.php">יציאה</a></button>
        <button class ="addDish">הוסף מנה</button>
        <button class ="addNewProd">הוסף מוצר</button>
    </footer>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>
</html>