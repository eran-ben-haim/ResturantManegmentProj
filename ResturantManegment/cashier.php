<!DOCTYPE html>
<html lang="hb">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
    </script>
              <!-- קריאה לספריות בוטסטראפ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/2b07677526.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/PopupnForm.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-trxYGD5BY4TyBTvU5H23FalSCYwpLA0vWEvXXGm5eytyztxb+97WzzY+IWDOSbav" crossorigin="anonymous">
        <script defer src="js/cashier.js"></script>
        <link rel="stylesheet" href="css/cashier.css" />
    <title>Document</title>
</head>
<body>
    <!-- רשימת כפתורים בצד המערכת -->
    <nav class="side">
            <div class="btn-group" role="group" aria-label="Basic example" dir="rtl">
                    <button id="kitchenbutton" type="button" class="nav-button">פעולות מנהל מטבח</button>
                    <button id="menubot" type="button" class="nav-button">תפריט</button>
                    <button id="addtablebutton"type="button" class="nav-button">הוספת שולחן</button>
                    <button id="manegerexit"type="button" class="nav-button">פעולות מנהל</button>
                    <button id="workerstartshift" type="button" class="nav-button">כניסת עובד</button>                  
            </div>
    </nav>
    <main>   
              <!-- ייצוג השולחנות ונראות המסעדה בפרונט -->
        <div id="outside">
            <h2>גינה</h2>
            <?php
            require_once("DB\DataBase.php");
            $db= dbClass::GetInstance();
            $showingTableofgarden=$db->showTable('garden');
            echo $showingTableofgarden;
            // כניסה לדף של פעולות מנהל מטבח
            if(isset($_POST["kitchenManegerPas"]))
            {
                $passwrdForIKitchen = $_POST["kitchenManegerPas"];
                $log=$db->logintoKitchenPage($passwrdForIKitchen);
                if($log)
                {
                    header("Location:kitchenManegerActivity.php");
                }
                else
                {
                    return '<div class="containerwrongpassword"><p>סיסמא שגויה</p><button class = backToTheApp>אישור</button></div>';
                }
            }
            //כניסה לדף של התפריט
            if(isset($_POST["goToMenu"]))
            {
                $passwrdForIMenu = $_POST["goToMenu"];
                $log=$db->logintoKitchenPage($passwrdForIMenu);
                if($log)
                {
                    header("Location:menu.php");
                }
                    else
                    {
                        return '<div class="containerwrongpassword"><p>סיסמא שגויה</p><button class = backToTheApp>אישור</button></div>';
                    }
            }
            //כניסה לדף של לקיחת הזמנות מלקוח
            if(isset($_POST['passForWMenu']) && isset($_POST['goToWaitresMenu']))
            {
                //בדיקה האם הסיסמא תואמת למלצר
                if($db->passwrdCheckForWaiterrs($_POST['passForWMenu']))
                {
                    //בדיקה האם המלצר אחראי על אותו שולחן
                    if($db->checkIfTableAvailable($_POST['goToWaitresMenu']))
                    {
                        $db->setTableForWorker($_POST['passForWMenu'],$_POST['goToWaitresMenu']);
                        header("Location:waitressmenu.php?tableNum=".$_POST['goToWaitresMenu']);
                    }
                    // בדיקה האם השולחן ריק ועדכון שהשולחן איננו ריק
                        elseif($db->checkIfTheTablePass($_POST['goToWaitresMenu'],$_POST['passForWMenu']))
                        {
                            header("Location:waitressmenu.php?tableNum=".$_POST['goToWaitresMenu']."");
                        }
                        // השולחן תפוס על ידי מלצר אחר
                        else
                        {
                            return '<div class="containerwrongpassword"><p> השולחן תפוס</p><button class = backToTheApp>אישור</button></div>';
                        }
                }
                // סיסמט שגויה 
                else
                {
                    return '<div class="containerwrongpassword"><p>סיסמא שגויה</p><button class = backToTheApp>אישור</button></div>';
                }
            }
            ?>
        </div>
        <div id="pattio">
            <h2>פאטיו</h2>
             <?php
            //  יצירת כל השולחנות שישנם בפאטיו
            $db= dbClass::GetInstance();
            $showingTableofpatio=$db->showTable('patio');
            echo $showingTableofpatio;
            ?>
        </div>
        <div id="bar">
            <h2>בר</h2> 
             <?php
             //  יצירת כל השולחנות שישנם בבר
            $db= dbClass::GetInstance();
            $showingTableofbar=$db->showTable('bar');
            echo $showingTableofbar;
            ?>
            </div>
        <div id="inside">
            <h2>מסעדה</h2>
             <?php
            //  יצירת כל השולחנות שישנם מסעדה
            $db= dbClass::GetInstance();
            $showingTableofmainhall=$db->showTable('mainhall');
            echo $showingTableofmainhall;
            // פונקציה להוספת שולחן
            if(isset($_POST['garden'])&&isset($_POST['passwdforaddtable']))
            {
                $db= dbClass::GetInstance();
                $password = $_POST['passwdforaddtable'];
                if($db->passwrdCheckformaneger($password))
                {
                    $addtabeltogarden=$db->addTable('garden'); 
                    echo $addtabeltogarden;
                }
            }
            if(isset($_POST['patio'])&&isset($_POST['passwdforaddtable']))
            {
                $db= dbClass::GetInstance();
                $password = $_POST['passwdforaddtable'];
                if($db->passwrdCheckformaneger($password))
                    $addtabeltogarden=$db->addTable('patio'); 
                echo $addtabeltogarden;
            }
                if(isset($_POST['mainhall'])&&isset($_POST['passwdforaddtable']))
                {
                    $db= dbClass::GetInstance();
                    $password = $_POST['passwdforaddtable'];
                    if($db->passwrdCheckformaneger($password))
                        $addtabeltogarden=$db->addTable('mainhall'); 
                    echo $addtabeltogarden;
                }
            if(isset($_POST['bar'])&&isset($_POST['passwdforaddtable']))
            {
                $db= dbClass::GetInstance();
                $password = $_POST['passwdforaddtable'];
                if($db->passwrdCheckformaneger($password))
                    $addtabeltogarden=$db->addTable('bar'); 
                echo $addtabeltogarden;
            }
            // הסוףשל הוספת שולחן
            //יציאה מהמשמרת
            $db= dbClass::GetInstance();
            if(isset($_POST['exitresult']))
            {
                $result = $_POST['exitresult'];
                //בדיקה שהסיסמט קיימת והעובד כרגע במשמרת
                if( $db->punchout($result))
                {
                    echo "<div class=containerwrongpassword><p>יום נעים</p><button class = backToTheApp>אישור</button></div>";
                }
                    else
                    {
                        echo "<div class=containerwrongpassword><p>סיסמא שגויה</p><button class = backToTheApp>אישור</button></div>";
                    }
            }
            // כניסה למשמרת
            $db= dbClass::GetInstance();
            if(isset($_POST['entranceresult']))
            {
                // בדיקה האם העובד קיים ואם איננו במשמרת
                $result = $_POST['entranceresult'];
                if( $db->punchIn($result))
                {
                    echo "<div class=containerwrongpassword><p>משמרת נעימה</p><button class = backToTheApp>אישור</button></div>";
                }
                else
                {
                    echo "<div class=containerwrongpassword><p>סיסמא שגויה</p><button class = backToTheApp>אישור</button></div>";
                }
            }
?>
    </main>
    <!-- רשימת כפתורים בתחתית הדף --> 
    <nav class="bottom">
        <div class="btn-group" role="group" aria-label="Basic example" dir="rtl">
            <button id="exit" type="button" class="nav-button">יציאת עובד</buttons>
                <button type="button" id="tempcheck" class="nav-button">חשבון זמני</button>
                <button type="submit" id="finalcheck" class="nav-button">חשבון סופי</button>
                <button type="button" id="TableSwap" class="nav-button">החלפת שולחן</button>
                <button id="zexit" type="button" class="nav-button">סגירת קופה</button>
        </div>
    </nav>
    <!-- פופ אם מחשבון -->
    
    <div class=overlay>               
        <div class="calculator" dir="rtl">
            <button id="closeCalculator">&KHcy;</button>
            <form method="post" class="calcForm">
                <div class="inputpass">
                    <p>הכנס סיסמא</p>
                    <input type="password" readonly class=calcClass id="caluclatorPass" />
                </div>
                <input type="button" value="7" onclick="insertvalCal(value)" />
                <input type="button" value="8" onclick="insertvalCal(value)" />
                <input type="button" value="9" onclick="insertvalCal(value)" />
                <input type="button" value="4" onclick="insertvalCal(value)" />
                <input type="button" value="5" onclick="insertvalCal(value)" />
                <input type="button" value="6" onclick="insertvalCal(value)" />
                <input type="button" value="1" onclick="insertvalCal(value)" />
                <input type="button" value="2" onclick="insertvalCal(value)" />
                <input type="button" value="3" onclick="insertvalCal(value)" />
                <input type="button" value="0" onclick="insertvalCal(value)" />
                <input type="button" value="del" onclick="setDisplayexitWorkerCal('')" />
                <button type="submit" class=calcApprove id=approvePass >אישור</button>
            </form>
        </div>           
    </div>
            <!-- פופ אפ הוספת שולחן -->
            <div class=overlayAddTable>
                <div class=chooseAreaDiv>
                    <div class=containExitB>
                    <button id="closeChooseTable">&KHcy;</button>
                    </div>
                    <h4>? היכן תרצה להוסיף שולחן</h4>
                    <div>
                        <button class="choosePatio addTable" value="patio">פאטיו</button>
                        <button class="chooseGarden addTable" value="garden">גינה</button>
                    </div>
                    <div>
                        <button class="chooseBar addTable" value="bar">בר</button>
                        <button class="chooseresturant addTable" value="mainhall">מסעדה</button>
                    </div>
                </div>
                <div class="calculatorTable" dir="rtl">
                    <button id="closeCalculatorTable">&KHcy;</button>
                        <form method="post" class="calcForm">
                            <div class="inputpass">
                                <p>הכנס סיסמא</p>
                                <input type="password" readonly class=calcClass id="addTablePass" autocomplete="new-password"/>
                            </div>
                            <input type="button" value="7" onclick="insertvalCalTable(value)" />
                            <input type="button" value="8" onclick="insertvalCalTable(value)" />
                            <input type="button" value="9" onclick="insertvalCalTable(value)" />
                            <input type="button" value="4" onclick="insertvalCalTable(value)" />
                            <input type="button" value="5" onclick="insertvalCalTable(value)" />
                            <input type="button" value="6" onclick="insertvalCalTable(value)" />
                            <input type="button" value="1" onclick="insertvalCalTable(value)" />
                            <input type="button" value="2" onclick="insertvalCalTable(value)" />
                            <input type="button" value="3" onclick="insertvalCalTable(value)" />
                            <input type="button" value="0" onclick="insertvalCalTable(value)" />
                            <input type="button" value="del" onclick="setDisplayTableCal('')" />
                            <button type="submit" class=calcApprove id=approvePassForTable>אישור</button>
                        </form>
                </div>
             </div>
</body>
</html>
<!-- </div>
              <div class="addtablepassword" dir="rtl">
                  <button id="closeaddtablepasswordForm">&KHcy;</button>

                  <form method="post" class="exitworkers">

                      <div class="inputpass">
                          <p>הכנס סיסמא</p>
                          <input type="password" readonly id="addTableCal" name="passwdforaddtable" />
                      </div>
                      <input type="button" value="7" onclick="insertvaladdTableCal(value)" />
                      <input type="button" value="8" onclick="insertvaladdTableCal(value)" />
                      <input type="button" value="9" onclick="insertvaladdTableCal(value)" />
                      <input type="button" value="4" onclick="insertvaladdTableCal(value)" />
                      <input type="button" value="5" onclick="insertvaladdTableCal(value)" />
                      <input type="button" value="6" onclick="insertvaladdTableCal(value)" />
                      <input type="button" value="1" onclick="insertvaladdTableCal(value)" />
                      <input type="button" value="2" onclick="insertvaladdTableCal(value)" />
                      <input type="button" value="3" onclick="insertvaladdTableCal(value)" />
                      <input type="button" value="0" onclick="insertvaladdTableCal(value)" />
                      <input type="button" value="del" onclick="setDisplayaddTableCal('')" />
                      <button type="submit" name="patio">פאטיו</button>
                      <button type="submit" name="bar">בר</button>
                      <button type="submit" name='garden'>גינה</button>
                      <button type="submit" name="mainhall">מסעדה</button>       
                  </form> -->
                  
                         <!-- <button id="close" style="width:15%;">&KHcy;</button>
        <form method="post" >
            <input id="name" type="text" name="Employ_name" pattern="[a-zA-Z]{1,20}"required  placeholder="שם פרטי "/> 
            <input id="idfor" type="text" name="I_D"  pattern="[0-9]{9}"required placeholder=" ת.ז " />
            <input id="passwordfor" type="text" name="Password"  pattern="[0-9]{4}"required placeholder=" סיסמא "/>
            <input id="numberpho" type="text" name="Phone_Number"  pattern="[0-9]{10}"required placeholder=" מספר טלפון "/>
            <input id="employ" type="number" name="Duty" pattern="[1-6]{1}"required placeholder=" סטאטוס עובד "/>
          <input type="submit" value="submit" style="text-align-last: center; width:50%; margin-right:40px;" />
        </form>
      </div> -->
     
<!-- //       require_once("DataBase.php");
//       $db= dbClass::GetInstance();
//       if(isset($_POST["Employ_name"]) && isset($_POST["I_D"]) && isset($_POST["Password"]) && isset($_POST["Phone_Number"]) && isset($_POST["Duty"]) ){
// $firstName=$_POST['Employ_name'];
// $id=$_POST['I_D'];
// $password=$_POST['Password'];
// $numberPhone=$_POST['Phone_Number'];
// $employment=$_POST['Duty'];
// if($db->insertWorker($firstName,$id,$password,$numberPhone,$employment)==0){
// echo "<p class='message'>עובד חדש במערכת דרך צלחה </p>";
//  }
//  else{
//    echo "<p class='message'>משתמש קיים במערכת </p>";
//  }
//   }
  -->