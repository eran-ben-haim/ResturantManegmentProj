<?php
session_start();
if(!isset( $_SESSION['garden']))
{
  $_SESSION['garden']=0;
}
if(!isset( $_SESSION['bar']))
{
  $_SESSION['bar']=19;
}
if(!isset( $_SESSION['mainhall']))
{
  $_SESSION['mainhall']=29;
}
if(!isset( $_SESSION['patio']))
{
  $_SESSION['patio']=9;
}
class dbClass
{
  private static $host;
  private static $db;
  private static $charset;
  private static $user;
  private static $pass;
  private static $opt = array(
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
  private static $connection;
  private static $obj;
  private function __construct(string $host= "localhost", string $db = "resturantmanegmentproject",
  string $charset = "utf8", string $user = "root",string $pass = "")
  {
    self::$host = $host;
    self::$db = $db;
    self::$charset = $charset;
    self::$user = $user;
    self::$pass = $pass;
  }
  public function GetInstance():dbClass
  {
  if (self::$obj == null)
    self::$obj=new dbClass ();
  return self::$obj ;
  }
  private function connect()
  {
    $dns = "mysql:host=".self::$host.";dbname=".self::$db.";charset=".self::$charset;
    self::$connection = new PDO($dns, self::$user, self::$pass, self::$opt);
  }
  public function disconnect()
  {
    self::$connection = null;
  }
  // כניסה לקופה ע"י אחמש או מנהל מטבח או מנהל מסעדה
  public function login($first, $password ):bool
  {
    self::connect();
    $workers = array();
    $flag = false;
    $result = self::$connection->query("SELECT * FROM employee");
    while($row = $result->fetch(PDO::FETCH_ASSOC)) 
      {
        $workers[] = $row;
      }
  for($i=0;$i<count($workers);$i++ )
  {
    $hashed_password=$workers[$i]['Passwd'];
    if($workers[$i]['Duty']>='3' && password_verify($password,$hashed_password) && $workers[$i]['Employ_name']==$first)
      $flag=true;
  }
  self::disconnect();
  return $flag;
  }
  //כניסה לאזור של מנהל מטבח
  public function logintoKitchenPage($password ):bool
  {
  self::connect();
  $workers = array();
  $flag = false;
  $result = self::$connection->query("SELECT * FROM employee");
  while($row = $result->fetch(PDO::FETCH_ASSOC)) 
  {
    $workers[] = $row;
  }
  for($i=0;$i<count($workers);$i++ )
  {
    $hashed_password=$workers[$i]['Passwd'];
    if($workers[$i]['Duty']>='3' && password_verify($password,$hashed_password))
      $flag=true;
  }
  self::disconnect();
  return $flag;
  }
  //בדיקה האם משתמש קיים במערכת לפי סיסמא
  public function passwrdCheck($pass):bool
  {
  self::connect();
  $flag = false;
  $result = self::$connection->query("SELECT * FROM employee");
  while($row = $result->fetch(PDO::FETCH_ASSOC)) 
    {$workers[] = $row;}
  for($i=0;$i<count($workers);$i++ )
  {
    $hashed_password=$workers[$i]['Passwd'];
    if(password_verify($pass,$hashed_password))
      $flag=true;
  }
  self::disconnect();
  return $flag;
  }
  //פונקציית הכנסת עובד חדש לבסיס הנתונים
  public function insertWorker($firstName,$id,$password,$numberPhone,$employment):bool {
  $flag=self::passwrdCheck($password);
  self::connect();
  if($flag==0)
  {
  $sql ="INSERT INTO employee(Employ_name,I_D,Passwd,Duty,Phone_Number )  VALUES(:Employ_name,:I_D,:Password1,:Duty,:Phone_Number)";
  $query = self::$connection->prepare($sql);
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  $query -> execute([':Employ_name' => $firstName ,':I_D'=> $id,':Password1'=>$hashed_password,':Phone_Number' => $numberPhone ,':Duty' =>$employment ]);
  }
  self::disconnect();
  //פלאג מחזיר שקר במקרה של הוספת עובד
  return $flag;
  }
  public function passwrdCheckformaneger($pass):bool
  {
  self::connect();
  $flag = false;
  $result = self::$connection->query("SELECT * FROM employee");
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {$workers[] = $row;}
  for($i=0;$i<count($workers);$i++ )
  {
    $hashed_password=$workers[$i]['Passwd'];
    if(password_verify($pass,$hashed_password)&&$workers[$i]['Duty']>=3)
    $flag=true;
  }
  self::disconnect();
  return $flag;
  }
  //מציאת תעודת זהות לפי סיסמא
  public function getIdByPass($password):String{
    $workerId='b';
    self::connect();
    $result = self::$connection->query("SELECT Passwd,I_D FROM employee");
  while($row = $result->fetch(PDO::FETCH_ASSOC)) 
  {
    $workers[] = $row;
  }
  for($i=0;$i<count($workers);$i++ )
  {
    $hashed_password=$workers[$i]['Passwd'];
    if(password_verify($password,$hashed_password))
    {
      $workerId=$workers[$i]['I_D'];
    }  
  }
  self::disconnect();
  return $workerId;
  }
  //הוצאת מספר השולחנות באותו אזור
  public function amountOfTableInArea($location):int{
    self::connect();
    $sql = 'SELECT MAX(tableNum) FROM `customer_table` WHERE Area = :Area';
    $query = self::$connection->prepare($sql);
    $query -> execute([':Area' => $location ]);
    while($row = $query->fetch(PDO::FETCH_ASSOC))
    {
      $numOfMaxTable[]= $row;
    }
    self::disconnect();
    return $numOfMaxTable[0]['MAX(tableNum)'];
  }
  //בודק האם יש מקום בתחום הקיים בשביל שולחן
  public function checkLocationForTable($location,$numOfTabels):int
  {
    if($location=='garden'&& $numOfTabels<10)
    {
      return $numOfTabels + 1;
    }
      elseif($location=='patio'&& $numOfTabels<20)
      {
        return $numOfTabels + 1;
      }
        elseif($location=='bar'&& $numOfTabels<30)
        {
          return $numOfTabels + 1;
        }
          elseif($location=='mainhall'&& $numOfTabels<40)
          {
            return $numOfTabels + 1;
          }
            else 
            {
              return 99;
            }
  }
  //הוספת שולחן חדש לפי לוקיישן שנבחר
  public function addTable($location,$TableNum){
    self::connect();
    $sql ="INSERT INTO customer_table(tableNum,Area,Available)  VALUES(:tableNum,:Area,1)";
    $query = self::$connection->prepare($sql);
    $query -> execute([':tableNum' => $TableNum ,':Area' => $location ]);
    self::disconnect();
  }
  //פונקציה לקבלת שם של מי מחזיק בשולחן
  
  //פונקצית להצגת השולחנות בקופה
  public function showTable($location):String
  {
  self::connect();
  $sql = 'SELECT * FROM customer_table WHERE Area = :Area';
  $query = self::$connection->prepare($sql);
  $query->execute([':Area' => $location]);
  while($row = $query->fetch(PDO::FETCH_ASSOC))
    {
      $tabelsfromarea[]= $row;
    }
  $buttons='';
  for($i=0;$i<count($tabelsfromarea);$i++)
  {
    if($buttons!='')
    {
      if($tabelsfromarea[$i]['Available'])
      {
        $buttons=$buttons.'<span class=getTableNum><button type="button" value= '.$tabelsfromarea[$i]['tableNum'].' class="btn btn-secondary" style="color:black">'.self::setIconForTabels($location).' '.$tabelsfromarea[$i]['tableNum'].' שולחן</button></span> ';
      }
      else
      {
         $buttons=$buttons.'<span class=getTableNum><button type="button" value= '.$tabelsfromarea[$i]['tableNum'].' class="btn btn-secondary" style="color:black">'.self::getEmpNameByPass($tabelsfromarea[$i]['passToEnter']).' '.self::setIconForTabels($location).' '.$tabelsfromarea[$i]['tableNum'].' שולחן</button></span> ';
      }
    }
      else 
      {
        if($tabelsfromarea[$i]['Available'])
        {
          $buttons='<span class=getTableNum><button type="button" value='.$tabelsfromarea[$i]['tableNum'].' class="btn btn-secondary" style="color:black">'.self::setIconForTabels($location).' '.$tabelsfromarea[$i]['tableNum'].' שולחן</button></span> ';
        }
        else
        {
          $buttons='<span class=getTableNum><button type="button" value= '.$tabelsfromarea[$i]['tableNum'].' class="btn btn-secondary" style="color:black">'.self::getEmpNameByPass($tabelsfromarea[$i]['passToEnter']).' '.self::setIconForTabels($location).' '.$tabelsfromarea[$i]['tableNum'].' שולחן</button></span> ';
        }
      }
  }
  return $buttons;
  }
  // אם במשמרת מחזיר שקר בדיקה האם העובד כבר נמצא במשמרת
  public function onShift($passwd):bool
  {
    $flag=true;
    $shifts=array();
    $workerinshift=array();
    self::connect();
    $result =self::$connection->query("SELECT * FROM punchtime WHERE punchin = punchout");
    while($row = $result->fetch(PDO::FETCH_ASSOC))
    {
      $shifts[]= $row;
    }
    $result1=self::$connection->query("SELECT * FROM punchtimeforworker");
    while($row1 = $result1->fetch(PDO::FETCH_ASSOC))
    {
      $workerinshift[]= $row1;
    }
    for($i=0;$i<count($shifts);$i++)
    {
      for($j=0;$j<count($workerinshift);$j++)
      {
        $worker_id=self::getIdByPass($passwd);
        if($shifts[$i]['Shift_id']==$workerinshift[$j]['Shift_id']&& $worker_id==$workerinshift[$j]['worker_id'])
          $flag=false;
      }
    }
    return $flag;
    }
  // מציאת תז של המשמרת עי השעה
  public function getShiftId($time,$date):int
  {
      self::connect();
      $sql2 = " SELECT Shift_id 
                FROM `punchtime` 
                WHERE punchin = :timein and punchdate = :punchdate";
      $query = self::$connection->prepare($sql2);
      $query->execute([':timein'=>$time,':punchdate'=>$date]);
      while($row1 = $query->fetch(PDO::FETCH_ASSOC))
      {
        $shifyId[]= $row1;
      }
      // var_dump($shifyId);
      self::disconnect();
      return $shifyId[0]['Shift_id'];
  } 
  //שמירת השעה מתי נכנס עובד למשמרת
  public function punchIn($result):bool
  {
    $flag = false;
    $date=date('d-m-y');
    $time=date('h:i:s');
    if(self::passwrdCheck($result)&&self::onShift($result))
  {
      self::connect();
      $sql2 = "INSERT INTO punchtime(punchin,punchout,punchdate) VALUES(:timein, :timeo,:punchdate)";
      $query = self::$connection->prepare($sql2);
      $query->execute([':timein'=>$time,':timeo'=>$time ,':punchdate'=>$date]);
      $worker_id=self::getIdbypass($result);
      $shift_id =self::getShiftId($time,$date);
      self::connect();
      $sql1= "INSERT INTO punchtimeforworker(worker_id,Shift_id) VALUES(:workerid,:Shift_id)";
      $query1 = self::$connection->prepare($sql1);
      $query1->execute([':workerid'=>$worker_id,':Shift_id'=>$shift_id]);
      $flag=true;
      self::disconnect();
  }
  return $flag;
  }
  //שמירת השעה מתי עובד יוצא מהמשמרת
  public function punchOut($result):bool
  {
    $flag=false;
    if(self::passwrdCheck($result)&&!self::onShift($result))
    {
      self::connect();
      $sql1 = self::$connection->query("SELECT Shift_id FROM punchtime WHERE punchin = punchout");
      while($row = $sql1->fetch(PDO::FETCH_ASSOC)) 
      {
        $shifts[] = $row;
      }
      $sql1 = self::$connection->query("SELECT * FROM punchtimeforworker ");
      while($row = $sql1->fetch(PDO::FETCH_ASSOC)) 
      {
        $punchtimeforworker[] = $row;
      }
      $time=date('h:i:s');
      for($i=0;$i<count($shifts);$i++)
      {
        for($j=0;$j<count($punchtimeforworker);$j++ )
        {
          $worker_id=self::getIdByPass($result);
          self::connect();
          if($shifts[$i]['Shift_id']==$punchtimeforworker[$j]['Shift_id']&& $worker_id==$punchtimeforworker[$j]['worker_id'])
          {
            $shiftID=$punchtimeforworker[$j]['Shift_id'];
            $sql =self::$connection->prepare("UPDATE punchtime SET punchOut = :time1 WHERE Shift_id = :shiftId ");
            $sql-> execute([':time1'=>$time,':shiftId'=>$shiftID]);  
            $flag=true;
          }
        }
      }
    }
    self::disconnect();
    return $flag;
  }
  // האייקון של אותו סוג מוצר
  public function getIconForProd($type):string{
    if($type == 'dairy'){
      return '<img src="img/milk (1).png" alt="food icon">';
    }
      elseif($type == 'meat'){
        return '<img src="img/meat.png" alt="food icon">'; 
      }
        elseif ($type == 'carbs') {
          return '<img src="img/bread.png" alt="food icon">';
        }
          elseif ($type == 'vegetable') {
            return '<img src="img/vegetable (2).png" alt="food icon">';
          }
            elseif ($type == 'fish') {
              return '<img src="img/fish.png" alt="food icon">';
            }
              else {
                return '<img src="img/water-bottle.png" alt="food icon">';
              }
  }
  //פונקציה שמחזירה מערך עם כל המוצרים מאותו סוג מוצר 
  public function getProductByType($type):array
  {
  self::connect();
  $sql = 'SELECT ProductName,ProductId,ProductCapacity,ProductMinCapacity FROM Products WHERE ProductType = :prodtype';
  $query = self::$connection->prepare($sql);
  $query->execute([':prodtype' => $type]);
  while($row = $query->fetch(PDO::FETCH_ASSOC))
  {$productsFromType[]= $row;}
  self::disconnect();
  return $productsFromType;
  }
  // בדיקה האם אותו מוצר יש כלפיו הזמנה פתוחה מספק 
  public function isThereOrderForThisProd($prodId){
    self::connect();
    $sql = 'SELECT SupllierName FROM `suplliersorders` WHERE ProductId = :ProductId and deliverd = 0';
    $query = self::$connection->prepare($sql);
    $query->execute([':ProductId' => $prodId]);
    while($row = $query->fetch(PDO::FETCH_ASSOC))
      {$aprrove[]= $row;}
       self::disconnect();
       if (isset($aprrove)){
         return false;
       }
       else{
         return true;
       }
  }
  //פונקציה שמחזירה כפתורים של מוצרים מאותו סוג 
  public function showProduct($type):String{
    $iconForButtons=self::getIconForProd($type);
    $arrayOfPruduct=self::getProductByType($type);
    $buttons='';
    for($i=0;$i<count($arrayOfPruduct);$i++)
    {
      if($arrayOfPruduct[$i]['ProductCapacity']<$arrayOfPruduct[$i]['ProductMinCapacity']&& self::isThereOrderForThisProd($arrayOfPruduct[$i]['ProductId'])){
        $alert = 'dosentHave';
      }
      else{
        $alert = 'haveInStorge';
      }
      if($buttons!='' && $type != 'beverage')
      {
        $buttons=$buttons.'<button type="button" capacity='
        .(float)$arrayOfPruduct[$i]['ProductCapacity']  
        .' class="popupForProducts '
        .$alert
        .'" name="'
        .(string)$arrayOfPruduct[$i]['ProductId'].'" value="'
        .(int)$arrayOfPruduct[$i]['ProductId'].'">'
        .(string)$iconForButtons.'<span> '
        .(String)$arrayOfPruduct[$i]['ProductCapacity'].' ק"ג '
        .(String)$arrayOfPruduct[$i]['ProductName'].'</span></button> ';
      }
      else if($type != 'beverage')
      {
        $buttons='<button  type="button" capacity='
        .(float)$arrayOfPruduct[$i]['ProductCapacity']
        .' class="popupForProducts '
        .$alert
        .'" name='
        .(string)$arrayOfPruduct[$i]['ProductId']
        .' value="'.(int)$arrayOfPruduct[$i]['ProductId']
        .'">'.$iconForButtons
        .'<span> '.(String)$arrayOfPruduct[$i]['ProductCapacity'].' ק"ג '
        .(String)$arrayOfPruduct[$i]['ProductName'].'</span></button>';
      }
      elseif($buttons!=''){
        $buttons=$buttons.'<button type="button" capacity='
        .(float)$arrayOfPruduct[$i]['ProductCapacity']
        .'class="popupForProducts '
        .$alert
        .'" name="'
        .(string)$arrayOfPruduct[$i]['ProductId'].'" value="'
        .(int)$arrayOfPruduct[$i]['ProductId'].'">'
        .(string)$iconForButtons.'<span> '
        .(String)$arrayOfPruduct[$i]['ProductCapacity'].' ארגזי '
        .(String)$arrayOfPruduct[$i]['ProductName'].'</span></button> ';
      }
      else{
        $buttons='<button  type="button" capacity='
        .(float)$arrayOfPruduct[$i]['ProductCapacity']
        .' class="popupForProducts '
        .$alert
        .'" name='
        .(string)$arrayOfPruduct[$i]['ProductId']
        .' value="'.(int)$arrayOfPruduct[$i]['ProductId']
        .'">'.$iconForButtons
        .'<span> '.(String)$arrayOfPruduct[$i]['ProductCapacity'].' ארגזי '
        .(String)$arrayOfPruduct[$i]['ProductName'].'</span></button>';
      }
    }
    return $buttons;
    }
  //שם של מוצר לפי תז
  public function getProdName($id){
    self::connect();
    $sql = 'SELECT ProductName FROM products where Productid = :prodid';
    $query = self::$connection->prepare($sql);
    $query->execute([':prodid' => $id]);
    while($row = $query->fetch(PDO::FETCH_ASSOC)) 
    {
      $prodname[] = $row;
    }
    self::disconnect();
    return $prodname[0]['ProductName'];
  } 
  //מחזיר את כמות המוצרים בממסד הנתונים
  public function getproductlength():int
  {
    self::connect();
    $query=self::$connection->query('SELECT ProductId FROM Products');
    while($row = $query->fetch(PDO::FETCH_ASSOC))
    {
      $productsIds[] = $row;
    }
    $count=count($productsIds);
    self::disconnect();
    return $count;
  }
  //עדכון מוצר בבסיס הנתונים
  public function updateProductQuantity($prodid,$capacity):float
  {
    self::connect();
    $sql='UPDATE Products SET ProductCapacity =:capacity WHERE ProductId = :prodid';
    $query = self::$connection->prepare($sql);
    $query->execute([':prodid' => $prodid,':capacity' => $capacity]);
    self::disconnect();
    return $capacity;
  }
  //קליטת כל נתוני הספקים
  public function getAllSupplier($prodid)
  {
    self::connect();
    $sql = 'SELECT * FROM suppliersbringproud where Productid = :prodid';
    $query = self::$connection->prepare($sql);
    $query->execute([':prodid' => $prodid]);
    while($row = $query->fetch(PDO::FETCH_ASSOC)) 
    {
      $suppliers[] = $row;
    }
    self::disconnect();
       if(isset($suppliers))
       {
         return $suppliers; 
      }
      else{
      return false;
      } 
  }
  // ------------------
  public function getSupllierName():array
  {
    self::connect();
    $query=self::$connection->query('SELECT SupplierName from suppliers');
    while($row = $query->fetch(PDO::FETCH_ASSOC)) 
    {
      $suppliers[] = $row;
    }
    self::disconnect();
    return $suppliers;
  }
  //משיכת מייל של הספק לפי שם
  function getSupllierMail($supllierName):string
  {
    self::connect();
    $sql = 'SELECT Email FROM suppliers where SupplierName = :supllierName';
    $query = self::$connection->prepare($sql);
    $query->execute([':supllierName' => $supllierName]);
    while($row = $query->fetch(PDO::FETCH_ASSOC)) 
    {
      $supllierMail[] = $row;
    }
    self::disconnect();
    return $supllierMail[0]['Email'];
  }
  function getProductPrice($id):float
  {
    self::connect();
    $sql = 'SELECT ProductPrice FROM Products where Productid = :prodid';
    $query = self::$connection->prepare($sql);
    $query->execute([':prodid' => $id]);
    while($row = $query->fetch(PDO::FETCH_ASSOC))
    {
      $product[] = $row;
    }
    self::disconnect();
     return $product[0]['ProductPrice'];  
  }
  function setNewOrder($order,$price)
  {
    self::connect();
    $sql1= "INSERT INTO suplliersorders(SupllierName,ProductId,ProductName,DateOfOrder,shipment,price,deliverd) VALUES(:SupllierName,:ProductId,:ProductName,:DateOfOrder,:shipment,:price,:deliverd)";
    $query = self::$connection->prepare($sql1);
    $query->execute([':SupllierName'=>$order->supllierName,':ProductId'=>$order->productId,':ProductName'=>$order->productName,':DateOfOrder'=>$order->timeOfOrder,':shipment'=>$order->quantityToUpdate,':price'=>$price,':deliverd'=>false]);
    self::disconnect();
  }
  function getAllOrdersToApprove(){
    self::connect();
    $result = self::$connection->query("SELECT * FROM suplliersorders WHERE deliverd = 0");
    while($row = $result->fetch(PDO::FETCH_ASSOC)) 
    {
      $orders[] = $row;
    }
    if(!empty($orders))
      return $orders;
      else
        return 1;
  }
  function ordergetToResturant($order)
  {
    self::connect();
    $sql =self::$connection->prepare("UPDATE suplliersorders SET deliverd = 1 WHERE ProductName = :ProductName and  DateOfOrder = :DateOfOrder and SupllierName = :SupllierName");
    $sql-> execute([':ProductName'=>$order->prod,':DateOfOrder'=>$order->date,':SupllierName'=>$order->supllierName]);  
    self::disconnect();
  }
  function getProductQuantity($name):array
  {
    self::connect();
    $sql = 'SELECT * FROM Products where ProductName = :ProductName';
    $query = self::$connection->prepare($sql);
    $query->execute([':ProductName' => $name]);
    while($row = $query->fetch(PDO::FETCH_ASSOC)) 
    {
      $product[] = $row;
    }
    self::disconnect();
    return $product[0];
  }
  function getAllDishesByType($name):array
  {
    self::connect();
    $sql = 'SELECT * FROM dishes where typeOfMeal = :typeOfMeal';
    $query = self::$connection->prepare($sql);
    $query->execute([':typeOfMeal' => $name]);
    while($row = $query->fetch(PDO::FETCH_ASSOC)) 
    {
      $dishes[] = $row;
    }
    self::disconnect();
    return $dishes;
  }
  function getAllProdByType($type){
  self::connect();
  $sql = 'SELECT * FROM Products where ProductType = :ProductType';
  $query = self::$connection->prepare($sql);
  $query->execute([':ProductType' => $type]);
  while($row = $query->fetch(PDO::FETCH_ASSOC)) {$product[] = $row;}
  self::disconnect();
  return $product;
  }
  function insertNewDish($dish){
    self::connect();
    $sql = 'INSERT INTO `dishes`(`dish`, `typeOfMeal`, `price`) VALUES (:dish,:typeOfMeal,:price)';
    $query = self::$connection->prepare($sql);
    $query->execute([':dish' => $dish->DishName,':typeOfMeal' => $dish->DishType,':price' => $dish->DishPrice]);
    self::disconnect();
  }
  function insertProdsAttachedToDish($prod,$DishName){
    self::connect();
    $sql = 'INSERT INTO `dishcontainprod`(`dish`, `productId`, `quantityOfProd`) VALUES (:dish,:productId,:quantityOfProd)';
    $query = self::$connection->prepare($sql);
    $query->execute([':dish' => $DishName,':productId' => $prod->prodId,':quantityOfProd' => $prod->Quantity]);
    self::disconnect();
  }
  function addNewProd($prodData){
    self::connect();
    $sql = "INSERT INTO `products`(`ProductName`, `ProductPrice`, `ProductCapacity`, `ProductMinCapacity`, `ProductType`) VALUES (:ProductName,:ProductPrice,:ProductCapacity,:ProductMinCapacity,:ProductType)";
    $query = self::$connection->prepare($sql);
    $query->execute([':ProductName' => $prodData->name,':ProductPrice' => $prodData->price,':ProductCapacity' => $prodData->quantity,':ProductMinCapacity' => $prodData->alert,':ProductType' => $prodData->type]);
    self::disconnect();
  }
  function getAllProdContainDish($dishName){
    self::connect();
    $sql = 'SELECT productId,quantityOfProd FROM dishcontainprod where dish = :dish';
    $query = self::$connection->prepare($sql);
    $query->execute([':dish' => $dishName]);
    while($row = $query->fetch(PDO::FETCH_ASSOC)) {$product[] = $row;}
    self::disconnect();
    return $product;  
  }
  function removeProdFromContainDish($prodId,$dishName){
    self::connect();
    $sql = 'DELETE FROM `dishcontainprod` WHERE dish = :dish AND productId = :productId';
    $query = self::$connection->prepare($sql);
    $query->execute([':dish' => $dishName,':productId' => $prodId]);
    self::disconnect();
  }
  function updateQuantityforProdInDish($dishName,$prodId,$quantityToUpdate){
    self::connect();
    $sql='UPDATE dishcontainprod SET quantityOfProd =:quantityOfProd WHERE productId = :productId AND dish = :dish';
    $query = self::$connection->prepare($sql);
    $query->execute([':dish' => $dishName,':productId' => $prodId,':quantityOfProd'=>$quantityToUpdate]);
    self::disconnect();
  }
  function updatePriceForDish($dish,$price){
    self::connect();
    $sql='UPDATE dishes SET price =:price WHERE dish = :dish';
    $query = self::$connection->prepare($sql);
    $query->execute([':price' => $price,':dish' => $dish]);
    self::disconnect();
  }
  function deleteDish($name){
    self::connect();
    $sql = 'DELETE FROM `dishes` WHERE dish = :dish';
    $query = self::$connection->prepare($sql);
    $query->execute([':dish' => $name]);
    self::disconnect();
  }
  function checkIfTableAvailable($tableNum){
      self::connect();
      $sql = 'SELECT Available FROM `customer_table` WHERE tableNum = :tableNum';
      $query = self::$connection->prepare($sql);
      $query->execute([':tableNum' => $tableNum]);
      while($row = $query->fetch(PDO::FETCH_ASSOC)) {$Available[] = $row;}
      self::disconnect();
      return $Available[0]['Available'];
  }
  function setTableForWorker($pass,$tableNum){
      self::connect();
      $sql='UPDATE customer_table SET passToEnter =:passToEnter , Available = false WHERE tableNum = :tableNum';
      $query = self::$connection->prepare($sql);
      $query->execute([':passToEnter'=>$pass,':tableNum' => $tableNum]);
      self::disconnect();
  }
  function checkIfTheTablePass($TableNum,$pass){
      $flag = false;
      $Available = array();
      self::connect();
      $sql = 'SELECT passToEnter FROM `customer_table` WHERE tableNum = :tableNum';
      $query = self::$connection->prepare($sql);
      $query->execute([':tableNum'=>$TableNum]);
      while($row = $query->fetch(PDO::FETCH_ASSOC)) {$Available[] = $row;}
      self::disconnect();
      for($i=0;$i<count($Available);$i++){
        if(password_verify($pass,$Available[$i]['passToEnter'])){
          $flag = true;
        }
      }
      return $Available;
  }
  public function passwrdCheckForWaiterrs($pass):bool
  {
  self::connect();
  $flag = false;
  $result = self::$connection->query("SELECT * FROM employee");
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {$workers[] = $row;}
  for($i=0;$i<count($workers);$i++ ){
    $hashed_password=$workers[$i]['Passwd'];
    if(password_verify($pass,$hashed_password)&&$workers[$i]['Duty']>=2)
    $flag=true;
  }
  self::disconnect();
  return $flag;
  }
  public function getEmpNameByPass($pass):string
  {
    self::connect();
    $result = self::$connection->query("SELECT * FROM employee");
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {$workers[] = $row;}
    for($i=0;$i<count($workers);$i++ )
    {
      if(password_verify($pass,$workers[$i]['Passwd']))
      {
        $name = $workers[$i]['Employ_name'];
      }
    }
    self::disconnect();
    return $name;
  }
  public function setIconForTabels($area){
    if($area == 'patio')
    {
      return '<i class="fas fa-couch"></i>';
    }
      elseif($area == 'mainhall')
      {
        return '<i class="fas fa-coffee"></i>';
      }
        elseif($area == 'bar')
        {
          return '<i class="fas fa-cocktail"></i>';
        }
          else
          {
            return '<i class="fas fa-umbrella-beach"></i>';
          }
  }
  //החלפה בין שולחנות
  public function switchTable($table1,$table2)
  {
    self::connect();
    //לקיחת הנתונים מהשולחן שאותו רוצים להעביר
     $sql = 'SELECT * FROM `customer_table` WHERE tableNum = :table1';
      $query = self::$connection->prepare($sql);
      $query->execute([':table1'=>$table1]);
      while($row = $query->fetch(PDO::FETCH_ASSOC)) {$Table1V[] = $row;}
      //לקיחת נתונים מהשולחן אליו רוצים להעביר
      $sql = 'SELECT Available FROM `customer_table` WHERE tableNum = :table2';
      $query = self::$connection->prepare($sql);
      $query->execute([':table2'=>$table2]);
      while($row = $query->fetch(PDO::FETCH_ASSOC)) {$Table3V[] = $row;}
      if($Table1V[0]['Available'] == 0 && $Table3V[0]['Available'] == 1)
      {
        $sql = 'UPDATE `customer_table` SET `Available`= 0 ,`passToEnter`=:passToEnter WHERE tableNum =:table1';
        $query = self::$connection->prepare($sql);
        $query->execute([':table1'=>$table2,':passToEnter'=>$Table1V[0]['passToEnter']]);
        //הורדת הנתונים בשולחן
        $sql = 'UPDATE `customer_table` SET `Available`=1,`passToEnter`=null WHERE tableNum =:table2';
        $query = self::$connection->prepare($sql);
        $query->execute([':table2'=>$table1]);
        self::disconnect();
        $Table1V[0]['tableToSwitch'] = $table2;
        $Table1V[0]['empName'] = self::getEmpNameByPass($Table1V[0]['passToEnter']);
        $Table1V[0]['iconForTabelNum'] =  self::setIconForTabels($Table1V[0]['Area']);
        if($Table1V[0]['tableToSwitch']<10)
        {
          $Table1V[0]['iconForSwitchTable'] = self::setIconForTabels('garden');
        }
          elseif($Table1V[0]['tableToSwitch']>=10 && $Table1V[0]['tableToSwitch']<20)
          {
            $Table1V[0]['iconForSwitchTable'] = self::setIconForTabels('pattio');
          }
            elseif($Table1V[0]['tableToSwitch']>=20 && $Table1V[0]['tableToSwitch']<30)
            {
              $Table1V[0]['iconForSwitchTable'] = self::setIconForTabels('bar');
            }
              else
              {
                $Table1V[0]['iconForSwitchTable'] = self::setIconForTabels('mainhall');
              }
          return $Table1V[0];
          }
          else
          {
            self::disconnect();
            return -1;
          }
  }
  // שינוי זמינות של מנה במערכת 
  public function changeDishAv($available,$dishName){
    self::connect();
     $sql = 'UPDATE `dishes` SET `available`=:available WHERE dish = :dish';
      $query = self::$connection->prepare($sql);
      $query->execute([':available'=>$available,':dish'=>$dishName]);
       self::disconnect();
  }
  // החזרה מצב מוצר לגבי זמינותו 
  public function returnProdAlert($prodid){
    self::connect();
    $sql = 'SELECT `ProductCapacity`,ProductMinCapacity FROM `products`WHERE ProductId =:ProductId';
    $query = self::$connection->prepare($sql);
    $query->execute([':ProductId'=>$prodid]);
     while($row = $query->fetch(PDO::FETCH_ASSOC)) {$Prod[] = $row;}
     self::disconnect();
     if($Prod[0]['ProductCapacity'] > $Prod[0]['ProductMinCapacity'] && self::isThereOrderForThisProd($prodid)){
      return 1;
     }
     else{
       return 2;
     }
  }
}
if(isset($_POST['orderThatCame']))
{
  $orderObj = json_decode($_POST['orderThatCame']);
  $db= dbClass::GetInstance();
  $db->ordergetToResturant($orderObj);
  $quantityToUpdate = $db->getProductQuantity($orderObj->prod)['ProductCapacity'] + $orderObj->quantity;
  $db->updateProductQuantity($db->getProductQuantity($orderObj->prod)['ProductId'],$quantityToUpdate);
}
if(isset($_POST['handleUpdate'])&& isset($_POST['handleUpdateQuantity']))
{
  $db= dbClass::GetInstance();
  $db->updateProductQuantity($_POST['handleUpdate'],$_POST['handleUpdateQuantity']);
}
if(isset($_POST['getDishesButton']))
{
    $db= dbClass::GetInstance();
    echo json_encode($db->getAllDishesByType($_POST['getDishesButton']));
}
if(isset($_POST['getProdByName']))
{
  $db= dbClass::GetInstance();
  echo json_encode($db->getAllProdByType($_POST['getProdByName']));
}
if(isset($_POST['newDish']))
{
  $db= dbClass::GetInstance();
  $newDishArrObj = json_decode($_POST['newDish']);
  $db->insertNewDish($newDishArrObj[0]);
  for($i=1;$i<count($newDishArrObj);$i++)
  {
    $db->insertProdsAttachedToDish($newDishArrObj[$i],$newDishArrObj[0]->DishName);
  }
  echo(json_encode($newDishArrObj[0]));
}
if(isset($_POST['addNewProd']))
{
  $db= dbClass::GetInstance();
  $newProdArrObj = json_decode($_POST['addNewProd']);
  $db->addNewProd($newProdArrObj);
}
if(isset($_POST['getDataForDish']))
{
  $db= dbClass::GetInstance();
  $prodsData = $db->getAllProdContainDish($_POST['getDataForDish']);
  foreach($prodsData as &$prod)
  {
    $prod['prodName']=$db->getProdName($prod['productId']);
  }
  echo(json_encode($prodsData));
}
if(isset($_POST['removeProdFromDish'])&&$_POST['disnNameToRemove'])
{
  $db= dbClass::GetInstance(); 
  $db->removeProdFromContainDish($_POST['removeProdFromDish'],$_POST['disnNameToRemove']);
}
if(isset($_POST['updateQuanForDish']))
{
  $db= dbClass::GetInstance(); 
 $db->updateQuantityforProdInDish($_POST['updateQuanForDish']['dishName'],$_POST['updateQuanForDish']['prodId'],$_POST['updateQuanForDish']['quantity']);
}
if(isset($_POST['updateDishPrice']))
{
  $db= dbClass::GetInstance(); 
  $db->updatePriceForDish($_POST['updateDishPrice']['dish'],$_POST['updateDishPrice']['price']);
}
if(isset($_POST['dishToDelete']))
{
  $db= dbClass::GetInstance(); 
  $db->deleteDish($_POST['dishToDelete']);
}
?>