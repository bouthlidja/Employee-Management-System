<?php
session_start();



if (isset($_SESSION['username']) ) {
    $pageTitle= "process";
   
    include "init.php";  
 
     include $tpl . "sidebar.php"; 
     include $tpl . "navbar.php"; 
?>


<main class="main-container" id='request'>
    <input type="hidden" id="usrIDSelect"value="<?php echo $_SESSION['id'];?>" required>
    <div class="main-title">
        <h2>PROCESSING REQUEST</h2>
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
    <div class="main">
        <div class="box1">
            <form class="form-searsh">
                <input type="text" class="form-control searsh me-2" id="searchInput" placeholder="Enter value searsh" >
            </form>
        </div>
        <div class="filteBox">
            <div class="itemFilter typ">
                <label>status request</label>
                <select id="reqSttFilter" >
                    <option value=""> Select the status</option>
                    <option value="Pending">Pending</option>
                    <option value="accepted">accepted</option>
                    <option value="rejected">rejected</option>
                </select>
            </div>                 
        </div>
    </div>

    <table class="table table-req table-bordered table-striped border-primary">
        <thead class="table-dark">
            <tr> 
            <th scope="col">reqID</th>
            <th scope="col">employee ID</th>
            <th scope="col">name employee</th>
            
            <th scope="col">tyle request</th>
            <th scope="col">reason</th>
            <th scope="col">note</th>
            <th scope="col">status request</th>
            <th scope="col">Submission date</th>
            <th scope="col">processing</th>
            <!-- <th scope="col">Delete</th> -->
            
            </tr>
        </thead>
        <tbody>
            <!-- <tr>
                <td>1</td>
                <td>Mark</td>
                <td>Otto</td>
                <td>@mdo</td>
                <td>@mdo</td>
                <td>@mdo</td>
            </tr> -->
        </tbody>
    </table>
</main>
<!-- process request -->
<div class="modal model-process" id="model-process" tabindex="-1" role="dialog">

  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="panel-heading">
            <h5 class="modal-title">processing request</h5>
            <button type="button" class="close"  data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
      </div>
 
        <div class="modal-body">
                <div class="form-group "  style="display: none;">
                    <label>   id  </label>
                    <input type="text" id="reqID" required>
                </div>
                <div class="form-group">
                    <label>status request</label>
                    <select id="reqStt" >
                        <option value=""> Select the status of request</option>
                        <option value="Pending">Pending</option>
                        <option value="accepted">accepted</option>
                        <option value="rejected">rejected</option>
                    </select>
                </div>
        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary " onclick="update()">process</button>
      </div>

    </div>
  </div>
</div>












<?php
  include $tpl . "footer.php"; 
    
  }else{
      header('location: index.php');
  }


?>