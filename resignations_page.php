<?php
session_start();



if (isset($_SESSION['username']) ) {
    $pageTitle= "resignations";
   
    include "init.php";  
 
     include $tpl . "sidebar.php"; 
     include $tpl . "navbar.php"; 
?>


<main class="main-container" id="resignations">
  <div class="main-title">
    <h2>RESIGNATIONS</h2>
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
      <form class="form-search">
          <input type="text" id="searchInput" class="form-control search-box" placeholder="Enter search value">
      </form>
            
      <button class="btn  btn-info btn-add-resignation float-right" ><i class="fa-solid fa-plus"></i>&nbsp;&nbsp; Add New resignation</button>
    </div>
  </div>

  <table class="table table-resignation table-bordered table-striped border-primary">
      <thead class="table-dark">
        <tr>
          <th scope="col">resignation ID</th>
          <th scope="col">employee ID</th>
          <th scope="col">employee name</th>
          <th scope="col">Reason  resignation</th>
          <!-- <th scope="col">Resignation status</th> -->
          <th scope="col">Resignation Request Date</th>
          <th scope="col">date resignation</th>
          <th scope="col">print</th>
          <th scope="col">Update</th>
          <th scope="col">Delete</th>
        </tr>
      </thead>
      <tbody>
  
      </tbody>
    </table>
</main>
<!-- add model -->
<div class="modal model-add-resignation " id="model-add-resignation" tabindex="-1" role="dialog">
          <div class="alert alert-success" role="alert">
            <div class="text">
                <!-- This is a success alert—check it out! -->
            </div>
            <div class="btn-close-alert">
              <button type="button" class="close" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </div>
          <div class="alert alert-warning" role="alert">
            <div class="text">  </div>
            <div class="btn-close-alert">
              <button type="button" class="close" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </div>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="panel-heading">
                <h5 class="modal-title">Add resignation</h5>
              
                <button type="button" class="close" id="btn-close-model" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="info-emp">
                    <label for="empID"> enter employee ID  </label>
                    <div class="form-group inpSrsh"> 
                        <input type="text" class="form-control empID "  name="" > 
                        <button type="button" class="btn btn-primary" id="btnSearshEmp">Searsh</button>
                    </div>
                  
                    <p class= "detileEmp">
                      <span class="empID">
                        
                      </span>
                      <span class="fullNameTilte">
                       
                      </span>
                      <span class="fullName">
                       
                      </span>
                    </p>
                </div>
                
                <form class="form-resignation">
                   
                    <div class="form-group"> 
                        <label for="">Reason resignation </label>
                        <input type="text" class="form-control reason" id="orgDept" name=""  >
                    </div>
                    <!-- <div class="form-group">
                    <div class="custom-select " >
                      <label for="">Resignation status</label>
                        <select class="status" id="">
                          <option $selected value=""> Resignation status</option>
                          <option value="Approved">Approved</option>
                          <option value="Rejected">Rejected</option>
                        </select>
                      </div>
                    </div> -->

                    <div class="form-group"> 
                    <label for=""> Resignation Request Date</label>
                    <input type="date" class="form-control reqDat" id="" name="" >
                    </div>

                    <div class="form-group resetInput">
                      <label for=""> date resignation </label>
                      <input type="date" class="form-control resDat " id="" name="" >
                    </div>
                
                </form>
            </div>
           
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-dismiss="modal">RESET</button>
                        <button type="button" class="btn btn-primary btn-add-resignation">SAVE</button>
                    </div>
                 
                </div>
            
        </div>
    </div>
</div>
<!-- update model -->
<div class="modal model-update-resignation "  tabindex="-1" role="dialog">
          <div class="alert alert-success" role="alert">
            <div class="text">
                <!-- This is a success alert—check it out! -->
            </div>
            <div class="btn-close-alert">
              <button type="button" class="close" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </div>
          <div class="alert alert-warning" role="alert">
            <div class="text">  </div>
            <div class="btn-close-alert">
              <button type="button" class="close" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </div>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="panel-heading">
                <h5 class="modal-title">update resignation</h5>
              
                <button type="button" class="close btnClsUpd" id="btn-close-model" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">   
                <form class="form-resignation">
                <div class="form-group"> 
                        <label for=""> resignation id </label>
                        <input type="text" class="form-control resigID" id="orgDept" name=""  >
                    </div>
                    <div class="form-group"> 
                        <label for="">Reason resignation </label>
                        <input type="text" class="form-control reason" id="orgDept" name=""  >
                    </div>
                    <!-- <div class="form-group">
                    <div class="custom-select " >
                      <label for="">Resignation status</label>
                        <select class="status" id="status">
                          <option $selected value="" > Resignation status</option>
                          <option value="Approved">Approved</option>
                          <option value="Rejected">Rejected</option>
                        </select>
                      </div>
                    </div> -->

                    <div class="form-group"> 
                    <label for=""> Resignation Request Date</label>
                    <input type="date" class="form-control reqDat" id="" name="" >
                    </div>

                    <div class="form-group resetInput">
                      <label for=""> date resignation </label>
                      <input type="date" class="form-control resDat " id="" name="" >
                    </div>
                
                </form>
            </div>
           
            <div class="modal-footer">
              <button type="reset" class="btn btn-secondary" data-dismiss="modal">RESET</button>
              <button type="button" class="btn btn-primary btn-update-resignation">SAVE</button>
            </div>
                 
        </div>
            
        </div>
    </div>
</div>
<!-- delete model -->
<div class="modal model-delete-resignation" id="model-delete" tabindex="-1" role="dialog">
          <div class="alert alert-success" role="alert">
                      <div class="text">
                          <!-- This is a success alert—check it out! -->
                      </div>
                      <div class="btn-close-alert">
                        <button type="button" class="close" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
          </div>
          <div class="alert alert-warning" role="alert">
            <div class="text">  </div>
            <div class="btn-close-alert">
              <button type="button" class="close " aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </div>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">delete resignation</h5>
        <button type="button" class="close clsDlt" id="btn-close-model-delete" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <p>Are you sure you want to delete user number  <span class="text-danger idTrns"></span> ?</p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-delete-resig">DELETE</button>
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