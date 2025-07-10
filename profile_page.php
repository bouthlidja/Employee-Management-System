<?php
session_start();

if (isset($_SESSION['username']) ) {
    $pageTitle= "profile";
    include "init.php";  
 
    if ($_SESSION['role'] === 'admin'){
        include $tpl . "sidebar.php";
    }else{
        include $tpl . "sidebar_user.php"; 
    }
     include $tpl . "navbar.php"; 
     $usrID = isset($_GET['id']) ? $_GET['id'] : '';
     $sql = "SELECT  * FROM T_users WHERE usrID = :paramId ";

     $stmt = oci_parse($conn, $sql);

     oci_bind_by_name($stmt, ':paramId', $usrID); 

     oci_execute($stmt);

     $rows = [];

     $num_rows = oci_fetch_all($stmt, $rows, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
     
?>

<main class="main-container" id="profile">
    <div class="main-title">
        <h2>UPDATE PROFILE</h2>
    </div>
    <div class="alert" role="alert">
                <div class="text">
                    <!-- This is a success alert—check it out! -->
                </div>
                <div class="btn-close-alert">
                <button type="button" class="close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
            </div>
            <div class="form-container">

        <div class="form-group">
            <label>ID</label>
            <input type="text" class="form-control id" value="<?php echo $rows[0]['USRID']; ?>" required readonly />
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control usrNm" value="<?php echo $rows[0]['USERNAME']; ?>" placeholder="Enter Your Username" required />
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control eml" value="<?php echo $rows[0]['USREML']; ?>" placeholder="Enter Your Email" required />
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>New Password</label>
                <input type="password" class="form-control pass" name="new_password" placeholder="Enter Your Password" />
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" class="form-control phn" value="<?php echo $rows[0]['USRPHN']; ?>" placeholder="Enter Your Phone Number" required />
            </div>
        </div>

        <div class="showPassword">
            <input type="checkbox" id="showPassword"> 
            <label class="showPassword"for="showPassword">Show Password</label>
        </div>

        <button type="button" class="btn btn-primary btn-save">Save</button>
    </div>
</main>


<?php
  include $tpl . "footer.php"; 
    
  }else{
      header('location: index.php');
  }


?>