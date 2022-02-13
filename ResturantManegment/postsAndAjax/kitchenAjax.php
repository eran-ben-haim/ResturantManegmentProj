<?php
require_once("../DB/DataBase.php");
// הזנת נתונים לפופ אפ של עדכון כמות מוצר
if(isset($_POST['prodid']))
{
    $prodid = $_POST['prodid'];
     $db= dbClass::GetInstance();
    $suppliersForProduct=$db->getAllSupplier($prodid);
    if($suppliersForProduct){
        $prodName=$db->getProdName($prodid);
        $buttons = '';
        for($i=0;$i<count($suppliersForProduct);$i++){
            if($buttons=='')
                $buttons = '<tr><td><label> '.(string)$suppliersForProduct[$i]['SupplierName'].'</label></td><td><button  type="button" prodName='.(string)$prodName.' class="orderApprove" name='.(string)$suppliersForProduct[$i]['SupplierName'].' value='.(string)$suppliersForProduct[$i]['ProductId'].'>הוסף להזמנה</button> </td></tr>';
            else
                $buttons = $buttons.'<tr><td ><label> '.(string)$suppliersForProduct[$i]['SupplierName'].'</label></td><td><button  type="button" prodName='.(string)$prodName.' class="orderApprove" name='.(string)$suppliersForProduct[$i]['SupplierName'].' value='.(string)$suppliersForProduct[$i]['ProductId'].'>הוסף להזמנה</button> </td></tr>';
            }
    $buttons= $buttons.'<tr><td>כמות לעדכון </td><td><input class="quantityToAdd" size= 1 type=number value=0 name="addToProduct"></input></td></tr>';
    echo $buttons;
    }
    else echo '<tr><td">לא הוזנו ספקים נא לגשת למנהלה</td><tr>';
    }
    // עדכון ידני של כמות מוצר 
if(isset($_POST['handleUpdate'])&& isset($_POST['handleUpdateQuantity']))
{
    $db= dbClass::GetInstance();
    $db->updateProductQuantity($_POST['handleUpdate'],$_POST['handleUpdateQuantity']);
    $replaceButtonData['quantity'] = $_POST['handleUpdateQuantity'];
    $replaceButtonData['id'] = $_POST['handleUpdate'];
    $replaceButtonData['name'] = $db->getProdName($_POST['handleUpdate']);
     echo json_encode($replaceButtonData);
}
// שליחת מייל לספק בקשר לעדכון מוצר 
if(isset($_POST['orders12'])){
    $db= dbClass::GetInstance();
    $newOrdersToSent = $_POST['orders12'];
    $newOrdersInArray = json_decode($newOrdersToSent);
    foreach($newOrdersInArray as $newOrder){
        $to =$db->getSupllierMail($newOrder->supllierName);
        $subject = 'new order for bothaim and taher resturant';
        $message = 'hello '.$newOrder->supllierName.' this is a request for 
        a new order of '.$newOrder->productName.' we need '.$newOrder->quantityToUpdate.'kgs more
        thank you from the bothaimtaher resturant coorporation';
        $headers = 'From: eranbh123@gmail.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
        mail($to,$subject,$message,$headers);
        $price= $db->getProductPrice($newOrder->productId)*$newOrder->quantityToUpdate;
        $db->setNewOrder($newOrder,$price);
    }
    print_r($newOrdersToSent);
}
// עדכון כמות מוצר לאחר שהגיע המשלוח 
if(isset($_POST['orderThatCame']))
{
  $orderObj = json_decode($_POST['orderThatCame']);
  $db= dbClass::GetInstance();
  $db->ordergetToResturant($orderObj);
  $quantityToUpdate = $db->getProductQuantity($orderObj->prod)['ProductCapacity'] + $orderObj->quantity;
  $db->updateProductQuantity($db->getProductQuantity($orderObj->prod)['ProductId'],$quantityToUpdate);
  print_r(json_encode($db->getProductQuantity($orderObj->prod)));
}
// העברת נתונים לגבי כל המנות מאותו סוג
if(isset($_POST['getAllDishFromSameType']))
{
    $db= dbClass::GetInstance();
    echo json_encode($db->getAllDishesByType($_POST['getAllDishFromSameType']));
}
// עדכון מצב של מנה
if(isset($_POST['updateAv'])&&isset($_POST['dishName'])){
    $db= dbClass::GetInstance();
    $db->changeDishAv($_POST['updateAv'],$_POST['dishName']);
}
?>