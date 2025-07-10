<?php
session_start();

if (isset($_SESSION['username']) ) {
    $pageTitle= "request";
    // $noNavbar =true;
   
    include "init.php";  
    if ($_SESSION['role'] === 'admin'){
        include $tpl . "sidebar.php";
        include $tpl . "navbar.php";
        

    }else{
        include $tpl . "sidebar_user.php"; 
        include $tpl . "navbar_uesr.php";
    }
    
    
  
?>
<main class="main-container" id='request'>
    <input type="hidden" id="usrIDSelect"value="<?php echo $_SESSION['id'];?>" required>
    <div class="main-title">
        <h2>REQUEST</h2>
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
                
            <button class="btn  btn-info btn-add-req float-right" ><i class="fa-solid fa-plus"></i>&nbsp;&nbsp; Add New Request</button>
        </div>
    </div>

    <table class="table table-req table-bordered table-striped border-primary">
        <thead class="table-dark">
            <tr> 
            <th scope="col">reqID</th>
            
            <th scope="col">tyle request</th>
            <th scope="col">reason</th>
            <th scope="col">note</th>
            <th scope="col">status request</th>
            <th scope="col">Submission date</th>
            <th scope="col">Update</th>
            <th scope="col">Delete</th>
            
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

<!-- add request -->
<div class="modal model-add-req" id="model-add" tabindex="-1" role="dialog">
          
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="panel-heading">
                <h5 class="modal-title">Add Request</h5>
              
                <button type="button" class="close" id="btn-close-model" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                 
                
                <div id="sendRequest">
                

                    
                                <div class="form-group">
                                    <label>   id User </label>
                                    <input type="text" id="usrID"value="<?php echo $_SESSION['id'];?>" required>
                                </div>
                                <div class="form-group">
                                    <label> ID Employee  </label>
                                    <input type="text" id="empID" value="<?php echo  $_SESSION['emp'];?>" required>
                                </div>
                                <div class="form-group">
                                    <label>    Request Type</label>
                                    <select id="typReq" required>
                                        <option value=""> Select the type of request </option>
                                        <option value="leave">leave</option>
                                        <option value="Work certificate">Work certificate</option>
                                        <option value="Transfers">Transfers</option>
                                        <option value="resignations"> resignations</option>
                                        <option value="retirements"> retirements</option>
                                        <option value="retirements"> retirements</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Reason for request</label>
                                    <input id="rsnReq" rows="3" required>
                                </div>
                                <div class="form-group">
                                    <label>Additional notes</label>
                                    <textarea id="notReq" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Attach File</label>
                                    <input type="file" class="form-control" id="file"name="attachment">
                                </div>
                                
                
                </div> 
                   
                
                <div class="modal-footer">
                      <!-- <button type="reset" class="btn btn-secondary" data-dismiss="modal">RESET</button> -->
                      <button type="button" class="btn btn-primary btn-save-req" onclick="add()">SAVE</button>
                  </div>
            </div>
        </div>
    </div>
</div>

<!-- update request -->
<div class="modal model-update-req" id="model-update" tabindex="-1" role="dialog">
          
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="panel-heading">
                <h5 class="modal-title">Update Request</h5>
              
                <button type="button" class="close" id="btn-close-model" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="updateRequest">
                <div class="form-group " style="display: none;">
                        <label>   id  </label>
                        <input type="text" id="reqIDUpd" required>
                     </div>
                    <div class="form-group  " style="display: none;">
                        <label>   id User </label>
                        <input type="text" id="usrIDUpd"value="<?php echo $_SESSION['id'];?>" required>
                     </div>
                     <div class="form-group" style="display: none;">
                                    <label> ID Employee  </label>
                                    <input type="text" id="empIDUpd" value="<?php echo  $_SESSION['emp'];?>" required>
                                </div>
                    <div class="form-group">
                        <label>    Request Type</label>
                        <select id="typReqUpd" required>
                            <option value=""> Select the type of request </option>
                            <option value="leave">leave</option>
                            <option value="Work certificate">Work certificate</option>
                            <option value="Transfers">Transfers</option>
                            <option value="resignations"> resignations</option>
                            <option value="retirements"> retirements</option>
                            <option value="retirements"> retirements</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Reason for request</label>
                        <input id="rsnReqUpd" rows="3" required>
                    </div>
                    <div class="form-group">
                        <label>Additional notes</label>
                        <textarea id="notReqUpd" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Attach File</label>
                        <input type="file" class="form-control" id="fileUpd"name="attachment">
                    </div>
                </div> 
                <div class="modal-footer">
                      <!-- <button type="reset" class="btn btn-secondary" data-dismiss="modal">RESET</button> -->
                      <button type="button" class="btn btn-primary btn-update-req" onclick=" update()">SAVE</button>
                  </div>
            </div>
        </div>
    </div>
</div>
<!-- delete request -->

<div class="modal model-delete-req" id="model-delete" tabindex="-1" role="dialog">

  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">delete request</h5>
        <button type="button" class="close"  data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <p>Are you sure you want to delete  request number  <span class="text-danger reqID"></span> ?</p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-delete-req">DELETE</button>
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