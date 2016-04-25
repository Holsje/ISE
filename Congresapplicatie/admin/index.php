<?php

/**
 * Created by PhpStorm.
 * User: erike
 * Date: 19-4-2016
 * Time: 10:16
 */

    session_start();
    require('../query_congres.php');
    require('../connectDatabase.php');

    $congres = new Congres($server,
        $database,
        $uid,
        $password);


    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if (isset($_POST['login'])){
            if ($congres->checkLogin($_POST['input-username'], $_POST['input-password'])){
                //setcookie('user', $_POST['input-username'], time() + (14*24*60*60));
                $_SESSION['user'] = $_POST['input-username'];
                echo "Ingevulde inloggegevens zijn juist!";
                header('Location: index.php');
            }
            else{
                echo "Ingevulde inloggegevens zijn onjuist!";
            }
        }
        else if (isset($_POST['logout'])){
            unset($_SESSION['user']);
            if(isset($_COOKIE['user'])) {
                setcookie('user', '', 1);
            }
        }
    }


require_once('../pageConfig.php');

topLayoutManagement('Index','','');
?>

<div class="row">
    <div class="container col-sm-12 col-md-12 col-xs-12">
        <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
             <div class="row">
                 <?php
                    if (!isset($_SESSION['user'])) {
                 ?>
                        <div
                            class="popupWindow col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-3 col-xs-6">
                            <h1>Inloggen</h1>
                            <form
                                class="form-horizontal col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-xs-8 col-sm-10 col-md-10"
                                method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <div class="form-group">
                                    <label class="control-label col-xs-8 col-sm-4 col-md-4" for="input-username">Gebruikersnaam</label>
                                    <div class="col-xs-12 col-sm-8 col-md-8">
                                        <input type="text" id="input-username" name="input-username"
                                               placeholder="Gebruikersnaam" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-xs-8 col-sm-4 col-md-4" for="input-password">Wachtwoord</label>
                                    <div class="col-xs-12 col-sm-8 col-md-8">
                                        <input type="password" id="input-password" name="input-password"
                                               placeholder="Wachtwoord" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4 col-sm-4 col-xs-4 pull-right ">
                                        <button type="submit" name="login" class="btn btn-default form-control">
                                            Inloggen
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                 <?php
                    }
                    else {
                 ?>
                        <h1>Welkom <?php echo $_SESSION['user']; ?> in de beheerapplicatie!</h1>
                        <form method="POST" action = "<?php echo $_SERVER['PHP_SELF']; ?>">
                            <input type="submit" name="logout" value="Uitloggen">
                        </form>
                 <?php
                    }
                 ?>
             </div>

        </div>

    </div>
</div>
<?php
bottomLayout();
?>