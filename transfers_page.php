<?php
session_start();



if (isset($_SESSION['username'])) {
    $pageTitle= "Transfers";
   
    include "init.php";  
 
     include $tpl . "sidebar.php"; 
     include $tpl . "navbar.php"; 
?>

<main class="main-container" id="transfer">
  <div class="main-title">
    <h2>TRANSFERS</h2>
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
    <div class="transfers">
        <div class="tabs">
          <div class="tab active" onclick="showTab(0)">Employee Transfer</div>
          <div class="tab" onclick="showTab(1)"> Employee Transfer Report</div>
        
        </div>

        <div class="tab-content-container">
          <div class="tab-content active"> <!--strat content 1 -->
                   
                  <div class="box1">
                      <form class="form-search">
                        <input type="text" id="searchInput" class="form-control search-box" placeholder="Enter search value">
                      </form>
                          
                      <button class="btn  btn-info btn-add-transfer float-right" ><i class="fa-solid fa-plus"></i>&nbsp;&nbsp; Add New transfer</button>
                  </div>
             
                <div class="table-responsive">
                  <table class="table table-transfer table-bordered table-striped border-primary">
                      <thead class="table-dark">
                        <tr>
                          <th scope="col">transfer ID</th>
                          <th scope="col">employee ID</th>
                          <th scope="col">full name Employee</th>
                          <th scope="col">Original department</th>
                          <th scope="col">date Original dept approved </th>
                          <th scope="col">new department</th>
                          <th scope="col">date new dept approved </th>
                          <th scope="col">start date</th>
                          <th scope="col">print</th>
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
                </div>
          </div> <!--end content 1 -->


          <div class="tab-content "><!--strat content 2 -->
             <div class="content-report" id="reportTransfer">
                <!-- <h2 class="headerReport">Employee Transfer Report</h2> -->
                <div class="report-data">
                    <div class="form-group  ">
                        <label for=""> start date </label>
                        <input type="date" class="form-control strtDat">
                    </div>
                    <div class="form-group  ">
                        <label for=""> end date </label>
                        <input type="date" class="form-control endtDat">
                    </div>
                    <button type="button" class="btn btn-primary btn-rprt-trn">search</button>
                </div>

                <div class="report-result">
                  <div class="report-result-header">
                    <p>Employee Transfer Report</p>
                    <div>
                       from : <span class="from"></span>
                    </div>
                    <div>
                      to : <span class="to" ></span>
                    </div>
                    
                  </div>
                  <div class="report-result-body">
                    <p>Table showing all transfers during the time period from <span class="from">DD-MM-YYYY</span> to <span class="to">DD-MM-YYYY</span></p>
                  </div>
                  <div class="table-responsive">
                      <table class="table table-transfer table-bordered table-striped border-primary">
                          <thead class="table-dark">
                            <tr>
                              <th scope="col">transfer ID</th>
                              <th scope="col">employee ID</th>
                              <th scope="col">full name Employee</th>
                              <th scope="col"> department name</th>
                              <th scope="col">date Original dept approved </th>
                              <th scope="col">date new dept approved </th>
                              <th scope="col">start date</th>
                         
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
                </div>
                 
             </div>         
          </div> <!--end content 2 -->



        
        </div>
    </div>
</main>

   <!-- add transfers -->
  <div class="modal model-add-transfer " id="model-add" tabindex="-1" role="dialog">
             
             <div class="modal-dialog" role="document">
                 <div class="modal-content">
                     <div class="panel-heading">
                         <h5 class="modal-title">Add transfer</h5>
                       
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
                         
                         <form class="form-Add-transfer">
                           
                             <div class="form-group"> 
                                 <label for="maritalStatus">Original department </label>
                                 <input type="text" class="form-control orgDept" id="orgDept" name=""  >
                             </div>
                             <div class="form-group resetInput">
                               <label for=""> date Original dept approved </label>
                               <input type="date" class="form-control " id="datOrgApp" name="" >
                             </div>
                             <div class="form-group"> 
                                 <label for="">new department </label>
                                 <?php
                                     $sqlDep = "SELECT DepID, depName FROM T_Departments";
                                     $stmtDep = oci_parse($conn, $sqlDep);
                                     oci_execute($stmtDep);
                                 ?>
                                 <select id="newDept" name="dept" class="form-control" >
                                     <option value="">select  department</option>
                                     <?php
                                     // جلب الأقسام من قاعدة البيانات
                                     while ($row = oci_fetch_assoc($stmtDep)) {
                                         echo '<option value="' . htmlspecialchars($row['DEPID']) . '">' . htmlspecialchars($row['DEPNAME']) . '</option>';
                                       
                                     }
       
                               
                                     ?>
                                     
                                 </select>
                             </div>
                             <div class="form-group resetInput">
                               <label for=""> date new dept approved </label>
                               <input type="date" class="form-control " id="datNewApp" name="" >
                             </div>
                             <div class="form-group resetInput">
                               <label for=""> start date </label>
                               <input type="date" class="form-control " id="strtDat" name="" >
                             </div>
                             <div class="modal-footer"> 
                                 <button type="button" class="btn btn-primary btn-add-transfer">SAVE</button>
                         </div>
                         </form>
                        
                     </div>
                   
                            
                         
                         </div>
                     
                 </div>
             </div>
  </div>
       
         <!-- update transfer  -->
       <div class="modal model-update-transfer " id="model-add" tabindex="-1" role="dialog">
                  
           <div class="modal-dialog" role="document">
               <div class="modal-content">
                   <div class="panel-heading">
                       <h5 class="modal-title">Update transfer</h5>
                     
                       <button type="button" class="close" id="btn-close-model" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                       </button>
                   </div>
                   <div class="modal-body"> 
                       <form class="form-transfer">
                            <div class="form-group"  style="display: none;"> 
                               <label for="maritalStatus"> department id </label>
                               <input type="hidden" class="form-control trnID" id="orgDept" name=""  >
                             </div>
                           <div class="form-group"> 
                               <label for="maritalStatus">Original department </label>
                               <input type="text" class="form-control orgDept" id="orgDept"  readonly  >
                           </div>
                           <div class="form-group resetInput">
                             <label for=""> date Original dept approved </label>
                             <input type="date" class="form-control " id="datOrgApp" name="" >
                           </div>
                           <div class="form-group"> 
                               <label for="">new department </label>
                               <?php
                                   $sqlDep = "SELECT DepID, depName FROM T_Departments";
                                   $stmtDep = oci_parse($conn, $sqlDep);
                                   oci_execute($stmtDep);
                               ?>
                               <select id="newDept" name="dept" class="form-control" >
                                   <option value="">select  department</option>
                                   <?php
                                   // جلب الأقسام من قاعدة البيانات
                                   while ($row = oci_fetch_assoc($stmtDep)) {
                                       echo '<option value="' . htmlspecialchars($row['DEPID']) . '">' . htmlspecialchars($row['DEPNAME']) . '</option>';
                                     
                                   }
       
                              
                                   ?>
                                    
                               </select>
                           </div>
                           <div class="form-group resetInput">
                             <label for=""> date new dept approved </label>
                             <input type="date" class="form-control " id="datNewApp" name="" >
                           </div>
                           <div class="form-group resetInput">
                             <label for=""> start date </label>
                             <input type="date" class="form-control " id="strtDat" name="" >
                           </div>
                       </form>
       
                       <div class="modal-footer"> 
                               <button type="button" class="btn btn-primary btn-update-transfer">SAVE</button>
                       </div>
                   </div>    
               </div>
           </div>
       </div>
         <!-- delete model -->
       <div class="modal delete-transfer-box" id="model-delete" tabindex="-1" role="dialog">
                  
             
         <div class="modal-dialog" role="document">
           <div class="modal-content">
             <div class="modal-header">
               <h5 class="modal-title">delete employee</h5>
               <button type="button" class="close" id="btn-close-model-delete" data-dismiss="modal" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
               </button>
             </div>
       
             <div class="modal-body">
               <p>Are you sure you want to delete user number  <span class="text-danger idTrns"></span> ?</p>
             </div>
       
             <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <button type="button" class="btn btn-primary btn-delete-trns">DELETE</button>
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