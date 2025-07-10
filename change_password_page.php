<?php
  session_start(); 

$pageTitle = "change password";

include "init.php";
 
?>


 

  <div class="changePass" id="changePass">
    <div class="form-container">
        <p class="msg"> </p>
        <h2>Find your account  </h2>
        <p> Please enter your email  to search for your account.</p>
        <form action="api/api_password_change.php" method="POST" >
                <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <button type="submit" class=" btn btn-primary "id="search">search</button>
                <div class="form-footer">
                <p><a href="index.php">Back to login</a></p>
                </div>
        </form>
    </div>
  </div>

  <!-- <div class="modal   boxSendNumber"  tabindex="-1" role="dialog">
          
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">delete employee</h5>
            </div>

            <div class="modal-body">
                <p>Are you sure you want to delete user number  <span class="text-danger idLvs"></span> ?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-delete-lvs">DELETE</button>
            </div>
        </div>
    </div>
</div> -->

  
<?php
  include $tpl . "footer.php"; 
    
 


?>