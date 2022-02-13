<!DOCTYPE html>
<html lang="hb" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/popupnForm.css" />
    <link rel="stylesheet" href="css/entrance.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.rtl.min.css"
      integrity="sha384-trxYGD5BY4TyBTvU5H23FalSCYwpLA0vWEvXXGm5eytyztxb+97WzzY+IWDOSbav" crossorigin="anonymous">
      <script defer src="js/entrance.js"></script>
    <title>entrance to the app</title>
  </head>
  <body>
    <header>
      <h2> מערכת לניהול מסעדה</h2>
    </header>
    <main>
      <!-- טופס לכניסה למערכת -->
      <form method="post" action="">
        <p>הזן סיסמא</p>
        <p><input  type="text" name="name" placeholder=" שם משתמש "   /></p>
        <p><input type="password" name="pasword" placeholder="  סיסמא" pattern="[0-9]{4}"required/></p>
        <button id="entranceCahier" type="submit" name="entrancePassword"class=" bg-secondary btn btn-primary">כניסה</button>
      </form>
      <!-- כניסה רק על ידי אחמ"ש ומנהל או מנהל מטבח -->
            <?php
            require_once("DB/DataBase.php");
            $db= dbClass::GetInstance();
            if(isset($_POST["pasword"])&&isset($_POST['name'])){
              $passwrd = $_POST["pasword"];
              $name=$_POST['name'];
              $log=$db->login($name ,$passwrd);
            if($log){
              header("Location:cashier.php");
            }
            else{
               echo '<div class="containerwrongpassword" style=background: whitesmoke;><p>סיסמא שגויה</p><button class="btn btn-primary" id="goBackToInsertPassword">אישור</button></div>';
            }
}
?>

          </main>
    <footer></footer>
    <!-- קריאה לספריות בוטסטראפ -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
      integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
      integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
      integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>

  </body>
</html>