<?php
require_once("../DB/DataBase.php");
// בדיקה האם הסיסמא מתאימה לעובד שהינו אחמ"ש ומעלה 
if(isset($_POST['passForSwitchTable'])){
    $db= dbClass::GetInstance();
    if($db->passwrdCheckformaneger($_POST['passForSwitchTable'])){
        echo 1;
    }
    else{
        echo -1;
    }
}
// הוספת שולחן לדף של הקופה
if(isset($_POST['addTable'])){
    $db= dbClass::GetInstance();
    // שליפת כמות השולחנות באותו אזור
    $highestTableNumOfThisArea = $db->amountOfTableInArea($_POST['addTable']);
    // בדיקה האם יש מקום להוסיף עוד שולחן
    $isThereRoomForAnotherTable = $db->checkLocationForTable($_POST['addTable'],$highestTableNumOfThisArea);
    if($isThereRoomForAnotherTable != 99){
        // הוספת שולחן
        $db->addTable($_POST['addTable'],$isThereRoomForAnotherTable);
        // שליפת האייקון המתאים לאותו שולחן 
        $iconForTable = $db->setIconForTabels($_POST['addTable']);
        // שמירת כל הנותנים ושליחתם חזרה לפרונט
        $DataForNewTable = [$_POST['addTable'],$isThereRoomForAnotherTable,$iconForTable];
        echo json_encode($DataForNewTable);
    }
        else{
            return 99;
        }
}
// דאטא להחלפת שולחן
if(isset($_POST['swapTable']))
{
  $db= dbClass::GetInstance();
  $result = $db->switchTable($_POST['swapTable'],$_POST['actualTable']);
  echo json_encode($result);
}
?>