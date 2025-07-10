<?php
session_start();



if (isset($_SESSION['username']) ) {
    $pageTitle= "contact";
   
    include "init.php";  
 
     include $tpl . "sidebar.php"; 
     include $tpl . "navbar.php"; 
?>


<main class="main-container" id="contact_employees">
    <div class="main-title">
        <h2>Contact Employees</h2>
    </div> 
    
    <div class="SendEmail">
     
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
    <form class="formEmail"  >


        <div class="form-group">
            <label for="empEml">Employee Email</label>
            <input type="text" class="form-control" id="empEml" name="email">
        </div>

        <div class="form-group">
            <label for="dept">Departments</label>
            <?php
                $sqlDep = "SELECT DepID, depName FROM T_Departments";
                $stmtDep = oci_parse($conn, $sqlDep);
                oci_execute($stmtDep);
            ?>
            <select id="dept" name="dept" class="form-control dept">
                <option value="">Select Department</option>
                <?php
                while ($row = oci_fetch_assoc($stmtDep)) {
                    echo '<option value="' . htmlspecialchars($row['DEPID']) . '">' . htmlspecialchars($row['DEPNAME']) . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" class="form-control" id="subject" name="subject" required>
        </div>

        <div class="form-group">
            <label for="message">Message</label>
            <textarea class="form-control" id="message" name="message" required></textarea>
        </div>

        <div class="form-group">
            <label>Attach File</label>
            <input type="file" class="form-control" id="file"name="attachment">
        </div>
        <progress id="progressBar" value="0" max="100" style="width: 100%; display: none;"></progress>

        <button type="submit" id="send" class="submit-btn">Send Email</button>
    </form>
    </div>
</main>
<?php
  include $tpl . "footer.php"; 
    
  }else{
      header('location: index.php');
  }


?>