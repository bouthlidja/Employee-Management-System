<?php
session_start();

if (isset($_SESSION['username'])) {
    $pageTitle= "Retirement";
   
    include "init.php";  
 
     include $tpl . "sidebar.php"; 
     include $tpl . "navbar.php"; 
?>

<main class="main-container" id="retirement"  >
  <div class="main-title">
    <h2>RETIREMENTS</h2>
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
             <input type="text" id="searchInput" class="form-control search-box" placeholder="Enter search value">
            </form>
      <button class="btn  btn-info btn-add-retirement float-right" ><i class="fa-solid fa-plus"></i>&nbsp;&nbsp; Add New retirement</button>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-retirement table-bordered table-striped border-primary">
          <thead class="table-dark">
            <tr>
              <th scope="col">retirement ID</th>
              <th scope="col">employee ID</th>
              <th scope="col">full name Employee</th>
              <th scope="col">retirement reason</th>
              <th scope="col">Request date </th>
              <th scope="col">approval date</th>
              <th scope="col">print</th>
              <th scope="col">update</th>
              <th scope="col">delete</th>
            
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
  </div>
  
  
</main>

<!-- add retirement -->
<div class="modal model-add-retirement"  tabindex="-1" role="dialog">

          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="panel-heading">
                      <h5 class="modal-title">Add retirement</h5>
                    
                      <button type="button" class="close clsAdd" id="" data-dismiss="modal" aria-label="Close">
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
                      
                      <form class="form-retirement">
                        
                          <div class="form-group"> 
                              <label for="">Reason resignation </label>
                              <input type="text" class="form-control reason" id="orgDept" name=""  >
                          </div>
                          

                          <div class="form-group"> 
                            <label for=""> Request date </label>
                            <input type="date" class="form-control reqDat" id="" name="" >
                          </div>

                          <div class="form-group">
                            <label for=""> approval date </label>
                            <input type="date" class="form-control appDat " id="" name="" >
                          </div>
                      
                      </form>
                  </div>
                
                  <div class="modal-footer">
                      <button type="reset" class="btn btn-secondary" data-dismiss="modal">RESET</button>
                      <button type="button" class="btn btn-primary btn-add-retirement">SAVE</button>
                  </div>
                      
              </div>   
          </div>
    </div>
</div>
 <!-- update retirement -->
 <div class="modal model-update"  tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="panel-heading">
                      <h5 class="modal-title">UPDATE retirement</h5>
                    
                      <button type="button" class="close clsUpdate" id="" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      
                      <form class="form-retirement">
                        
                          <div class="form-group"> 
                              <label for=""> id retirement </label>
                              <input type="text" class="form-control idRet"  name=""  >
                          </div>

                          <div class="form-group"> 
                              <label for="">Reason retirement </label>
                              <input type="text" class="form-control reason" name=""  >
                          </div>
                          

                          <div class="form-group"> 
                            <label for=""> Request date </label>
                            <input type="date" class="form-control reqDat" id="" name="" >
                          </div>

                          <div class="form-group">
                            <label for=""> approval date </label>
                            <input type="date" class="form-control appDat " id="" name="" >
                          </div>
                      
                      </form>
                  </div>
                
                  <div class="modal-footer">
                      <button type="reset" class="btn btn-secondary" data-dismiss="modal">RESET</button>
                      <button type="button" class="btn btn-primary btn-update">SAVE</button>
                  </div>
                      
              </div>   
          </div>

</div>

<!-- delete retirement -->
<div class="modal model-delete-retirement" id="model-delete" tabindex="-1" role="dialog">
           
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">delete retirement</h5>
        <button type="button" class="close clsDlt" id="" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <p>Are you sure you want to delete user number  <span class="text-danger idRet"></span> ?</p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-delete-ret">DELETE</button>
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