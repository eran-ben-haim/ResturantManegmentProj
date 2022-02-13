<?php
require_once("DataBase.php");
if(isset($_POST['getDishes']))
{
    $db= dbClass::GetInstance();
    echo json_encode($db->getAllDishesByType($_POST['getDishes']));
}
if(isset($_POST['getDataForDish123']))
{
  $db= dbClass::GetInstance();
  $prodsData = $db->getAllProdContainDish($_POST['getDataForDish123']);
  foreach($prodsData as &$prod)
  {
    $prod['prodName']=$db->getProdName($prod['productId']);
  }
  echo json_encode($prodsData);
}
?>