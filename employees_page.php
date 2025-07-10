<?php
session_start();



if (isset($_SESSION['username'])) {
    $pageTitle= "Employees";
   
    include "init.php";  
 
     include $tpl . "sidebar.php"; 
     include $tpl . "navbar.php"; 
?>



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

<main class="main-container" id="employees">
    <div class="main-title">
        <h2>employees</h2>
    </div> 
    <div class="tabs">
      <div class="tab active" onclick="showTab(0)"> Employee Appointment</div>
      <div class="tab" onclick="showTab(1)">Employee Certificate</div>
       
    </div>

    <div class="tab-content-container">
        <div class="tab-content active"> <!-- strat Appointment -->
            <div class="box1">
                <form class="form-searsh">
                    <input type="text" class="form-control searsh me-2" placeholder="Enter search value" >
                    <!-- <button type="button" class="btn btn-primary">Search</button> -->
                </form>
                
                <button class="btn  btn-info btn-add-emp float-right" ><i class="fa-solid fa-plus"></i>&nbsp;&nbsp; Add New Employee</button>
            </div>
         
            <div class="filteBox"> <!-- strat filter box -->
                <div class="itemFilter">
                    <label> Gender </label>
                    <select id="gender">
                        <option $selected value="">All</option>
                        <option value="M">Male </option>
                        <option value="F">female</option>
                    </select>
                </div>
                <div class="itemFilter ">
                    <label>department</label>
                    <?php
                        $sqlDep = "SELECT DepID, depName FROM T_Departments";
                        $stmtDep = oci_parse($conn, $sqlDep);
                        oci_execute($stmtDep);
                    ?>
                    <select id="dept" name="dept" class="form-control" >
                        <option value="">Select Department</option>
                        <?php
                            // جلب الأقسام من قاعدة البيانات
                            while ($row = oci_fetch_assoc($stmtDep)) {
                                echo '<option value="' . htmlspecialchars($row['DEPID']) . '">' . htmlspecialchars($row['DEPNAME']) . '</option>';
                            
                            }

                        ?>
                    </select>
                </div>
                <div class="itemFilter ">
                    <label for="sctr">Sectors</label>
                    <?php
                        $sqlSec = "SELECT secID, secName FROM t_sectors";
                        $stmtSec = oci_parse($conn, $sqlSec);
                        oci_execute($stmtSec);
                    ?>
                    <select class="form-control sctr" id="sctr" name="sctr"  >
                        <option value="">Select Sector</option>
                        <?php
                        while ($rowSec = oci_fetch_assoc($stmtSec)) {
                            echo '<option value="' . htmlspecialchars($rowSec['SECID']) . '">' . htmlspecialchars($rowSec['SECNAME']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="itemFilter ">
                    <label for="rnk">Ranks</label>
                    <select class="form-control rnk" id="rnk" name="rnk"  >
                        <option value="">Select Rank</option>
                        <!-- سيتم تحديث هذه القائمة عبر AJAX -->
                    </select>
                </div>
            </div><!-- strat filter box -->

            <div class="table-responsive">
                <table class="table table-employees table-bordered table-striped border-primary">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col"> ID</th>
                            <th scope="col">first name</th>
                            <th scope="col">last name</th>
                            <th scope="col">gender</th>
                            <th scope="col">date of birth</th>
                            <th scope="col">municipality of birth</th>
                            <th scope="col">state of birth</th>
                            <th scope="col">Nationality</th>
                            <th scope="col">social security number</th>
                            <th scope="col">bank account number</th>
                            <th scope="col">national service card number</th>
                            <th scope="col">national identification card number</th>


                            <th scope="col">current address</th>
                            <th scope="col">phone number</th>
                            <th scope="col">email</th>
                            
                            <th scope="col">Family status</th>
                            <th scope="col">Husband Name</th>
                            <th scope="col">Husband Family Name</th>
                            <th scope="col">Number of children</th>
                            
                            <th scope="col">relationship works</th>
                            <th scope="col">Year of appointment </th>
                            <th scope="col"> department</th>
                            <th scope="col">sector  </th>
                            <th scope="col"> ranks  </th>
                            <th scope="col"> print </th>
                            <th scope="col"> Update </th>
                            <th scope="col">  delete </th>
                        </tr>

                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

        </div><!-- end Appointment -->

        <div class="tab-content"> <!-- strat Certificate -->
            <div class="box1">
                <form class="form-searsh">
                    <input type="text" class="form-control searsh me-2" placeholder="Enter search value" >
                    <!-- <button type="button" class="btn btn-primary">Search</button> -->
                </form>
            </div>
            <table class="table table-Certificate table-bordered table-striped border-primary">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col"> Certificate ID</th>
                            <th scope="col"> employee ID</th>
                            <th scope="col">full name</th>
                            <th scope="col">print</th>
                        </tr>

                    </thead>
                    <tbody>

                    </tbody>
                </table>
        </div> <!-- end Certificate -->
         
    </div>
        
    
    
</main>
<!-- add -->
<div class="modal model-add-employees " tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="panel-heading">
                <h5 class="modal-title">Add Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

        <div class="modal-body">
            <div id="err_msg"></div>
            <div class="tabs">
                <div class="tab active" data-tab="tab1">Personal information</div>
                <div class="tab" data-tab="tab2">Contact information</div>
                <div class="tab" data-tab="tab3">Family <br> status</div>
                <div class="tab" data-tab="tab4">Administrative information</div>
            </div>
            <form id="workerForm">
                <!-- Personal information-->
                <div  class="tab-content tab1 active">
                    <div class="form-group">
                        <label for="fNm"> first name </label>
                        <input type="text" class="form-control" id="fNm" name="fNm" >
                    </div>
                    <div class="form-group">
                        <label for="lNm"> last name </label>
                        <input type="text" class="form-control" id="lNm" name="lNm" >
                    </div>
                    <div class="form-group">
                        <label for="datBrth"> date of birth  </label>
                        <input type="date" class="form-control" id="datBrth" name="datBrth" > 
                    </div>
                    <div class="form-group">
                        <label for="muncpBrth"> Municipality of birth </label>
                        <input type="text" class="form-control" id="muncpBrth" name="muncpBrth" >
                    </div>
                    <div class="form-group">
                        <label for="sttBrth">state of birth </label>
                        <input type="text" class="form-control" id="sttBrth" name="sttBrth" >
                    </div>
                    <div class="form-group">
                        <label for="idCrdNum"> identification card number</label>
                        <input type="text" class="form-control" id="idCrdNum" name="idCrdNum" >
                    </div>
                    <div class="form-group">
                        <label for="serCrdNum"> service card number </label>
                        <input type="text" class="form-control" id="serCrdNum" name="serCrdNum" >
                    </div>
                    <div class="form-group">
                        <label for="bnkAccNum"> bank account number </label>
                        <input type="text" class="form-control" id="bnkAccNum" name="bnkAccNum" >
                    </div>
                    <div class="form-group">
                        <label for="socSecNum"> social security number </label>
                        <input type="text" class="form-control" id="socSecNum" name="socSecNum" >
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <div>
                            <input type="radio" id="M" name="gnd" value="M">
                            <label for="male">Male</label>
                        </div>
                        <div>
                            <input type="radio" id="female" name="gnd" value="F">
                            <label for="F">Female</label>
                        </div>
                    </div>
                </div>

                <!-- Contact information-->
                <div class="tab-content tab2">
                    <div class="form-group">
                        <label for="addrs">the address</label>
                        <input type="text" class="form-control" id="addrs" name="addrs" >
                    </div>
                    <div class="form-group">
                        <label for="eml">ُemail</label>
                        <input type="text" class="form-control" id="eml" name="eml" >
                    </div>
                    <div class="form-group">
                        <label for="phn">phone number</label>
                        <input type="text" class="form-control" id="phn" name="phn" >
                    </div>
                </div>

                <!-- Family status-->
                <div class="tab-content tab3">
                    <div class="form-group">
                        <label for="maritalStatus">Family status</label>
                        <select id="maritalStatus" name="maritalStatus" class="form-control" >
                            <option value="">Family status</option>
                            <option value="single">single</option>
                            <option value="married">married</option>
                            <option value="divorced">divorced</option>
                            <option value="widowed">widowed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="husbNm"> Husband Name </label>
                        <input type="text" class="form-control" id="husbNm" name="husbNm" >
                    </div>
                    <div class="form-group">
                        <label for="husFmlyNm">Husband Family Name</label>
                        <input type="text" class="form-control" id="husFmlyNm" name="husFmlyNm"  >
                    </div>
                    <div class="form-group">
                        <label for="numChld">Number of children</label>
                        <input type="number" class="form-control" id="numChld" name="numChld" value="0">
                    </div>
                </div>

                <!-- Administrative information-->
                <div class="tab-content tab4">
                    
                    <div class="form-group">
                        <label for="sctr">Sectors</label>
                        <?php
                        $sqlSec = "SELECT secID, secName FROM t_sectors";
                        $stmtSec = oci_parse($conn, $sqlSec);
                        oci_execute($stmtSec);
                        ?>
                        <select class="form-control sctr" id="sctr" name="sctr"  >
                            <option value="">Select Sector</option>
                            <?php
                            while ($rowSec = oci_fetch_assoc($stmtSec)) {
                                echo '<option value="' . htmlspecialchars($rowSec['SECID']) . '">' . htmlspecialchars($rowSec['SECNAME']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="rnk">Ranks</label>
                        <select class="form-control rnk" id="rnk" name="rnk"  >
                            <option value="">Select Rank</option>
                            <!-- سيتم تحديث هذه القائمة عبر AJAX -->
                        </select>
                    </div>

                    <div class="form-group"> 
                        <label for="maritalStatus">departments </label>
                        <?php
                            $sqlDep = "SELECT DepID, depName FROM T_Departments";
                            $stmtDep = oci_parse($conn, $sqlDep);
                            oci_execute($stmtDep);
                        ?>
                        <select id="dept" name="dept" class="form-control dept" >
                            <option value="">select department </option>
                            <?php
                            // جلب الأقسام من قاعدة البيانات
                            while ($row = oci_fetch_assoc($stmtDep)) {
                                echo '<option value="' . htmlspecialchars($row['DEPID']) . '">' . htmlspecialchars($row['DEPNAME']) . '</option>';
                              
                            }

                            
                            ?>
                             <!-- <button type="submit">Submit</button> -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="maritalStatus">Working relationship </label>
                        <select id="wrkRlt" name="wrkRlt" class="form-control" >
                            <option value=""> Working relationship  </option>
                            <option value=" Permanent"> Permanent Employee  </option>
                            <option value=" Contractor"> Contractor </option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">reset</button>
                        <button type="submit" id="addEmployee"class="btn btn-primary ">SAVE</button>
                    </div>
                </div>    
            </form>
        </div>
        </div>
    </div>
</div>
<!-- update -->
<div class="modal model-update-employees " tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="panel-heading">
                <h5 class="modal-title">Update Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

        <div class="modal-body">
            <div id="err_msg"></div>
            <div class="tabs">
                <div class="tab active" data-tab="tab1">Personal information</div>
                <div class="tab" data-tab="tab2">Contact information</div>
                <div class="tab" data-tab="tab3">Family <br> status</div>
                <div class="tab" data-tab="tab4">Administrative information</div>
            </div>
                <form id="">
                    <!-- Personal information-->
                        <div  class="tab-content tab1 active">
                            <div class="form-group" style="display: none;">
                                    <label for="idEmpUdate"> first name </label>
                                    <input type="hidden" class="form-control" id="idEmpUdate" name="idfNmEmpUdate" >
                                </div>
                                <div class="form-group">
                                    <label for="fNmEmpUdate"> first name </label>
                                    <input type="text" class="form-control" id="fNmEmpUdate" name="fNmEmpUdate" >
                                </div>
                                <div class="form-group">
                                    <label for="lNmEmpUdate"> last name </label>
                                    <input type="text" class="form-control" id="lNmEmpUdate" name="lNmEmpUdate" >
                                </div>
                                <div class="form-group">
                                    <label for="datBrthEmpUdate"> date of birth  </label>
                                    <input type="date" class="form-control" id="datBrthEmpUdate" name="datBrthEmpUdate" > 
                                </div>
                                <div class="form-group">
                                    <label for="muncpBrthEmpUdate"> Municipality of birth </label>
                                    <input type="text" class="form-control" id="muncpBrthEmpUdate" name="muncpBrthEmpUdate" >
                                </div>
                                <div class="form-group">
                                    <label for="sttBrthEmpUdate">state of birth </label>
                                    <input type="text" class="form-control" id="sttBrthEmpUdate" name="sttBrthEmpUdate" >
                                </div>
                                <div class="form-group">
                                    <label for="idCrdNumEmpUdate"> identification card number</label>
                                    <input type="text" class="form-control" id="idCrdNumEmpUdate" name="idCrdNumEmpUdate" >
                                </div>
                                <div class="form-group">
                                    <label for="serCrdNumEmpUdate"> service card number </label>
                                    <input type="text" class="form-control" id="serCrdNumEmpUdate" name="serCrdNumEmpUdate" >
                                </div>
                                <div class="form-group">
                                    <label for="bnkAccNumEmpUdate"> bank account number </label>
                                    <input type="text" class="form-control" id="bnkAccNumEmpUdate" name="bnkAccNumEmpUdate" >
                                </div>
                                <div class="form-group">
                                    <label for="socSecNumEmpUdate"> social security number </label>
                                    <input type="text" class="form-control" id="socSecNumEmpUdate" name="socSecNumEmpUdate" >
                                </div>
                                <div class="form-group">
                                    <label>Gender</label>
                                    <div>
                                        <input type="radio" id="MaleEmpUdate" name="gndEmpUdate" value="M">
                                        <label for="male">Male</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="femaleEmpUdate" name="gndEmpUdate" value="F">
                                        <label for="F">Female</label>
                                    </div>
                                </div>
                        </div>

                    <!-- Contact information-->
                        <div class="tab-content tab2">
                            <div class="form-group">
                                <label for="addrsEmpUpdate">the address</label>
                                <input type="text" class="form-control" id="addrsEmpUpdate" name="addrsEmpUpdate" >
                            </div>
                            <div class="form-group">
                                <label for="emlEmpUpdate">ُemail</label>
                                <input type="text" class="form-control" id="emlEmpUpdate" name="emlEmpUpdate" >
                            </div>
                            <div class="form-group">
                                <label for="phnEmpUpdate">phone number</label>
                                <input type="text" class="form-control" id="phnEmpUpdate" name="phnEmpUpdate" >
                            </div>
                        </div>

                    <!-- Family status-->
                        <div class="tab-content tab3">
                            <div class="form-group">
                                <label for="maritalStatusEmpUdate">Family status</label>
                                <select id="maritalStatusEmpUdate" name="maritalStatusEmpUdate" class="form-control" >
                                    <option value="">Family status</option>
                                    <option value="single">single</option>
                                    <option value="married">married</option>
                                    <option value="divorced">divorced</option>
                                    <option value="widowed">widowed</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="husbNmEmpUdate"> Husband Name </label>
                                <input type="text" class="form-control" id="husbNmEmpUdate" name="husbNmEmpUdate" >
                            </div>
                            <div class="form-group">
                                <label for="husFmlyNmEmpUdate">Husband Family Name</label>
                                <input type="text" class="form-control" id="husFmlyNmEmpUdate" name="husFmlyNmEmpUdate" >
                            </div>
                            <div class="form-group">
                                <label for="numChldEmpUdate">Number of children</label>
                                <input type="number" class="form-control" id="numChldEmpUdate" name="numChldEmpUdate" >
                            </div>
                        </div>

                        <!-- Administrative information-->
                        <div class="tab-content tab4">
                            <div class="form-group">
                                <label for="sctrEmpUpdate">Sectors</label>
                                <?php
                                $sqlSec = "SELECT secID, secName FROM t_sectors";
                                $stmtSec = oci_parse($conn, $sqlSec);
                                oci_execute($stmtSec);
                                ?>
                                <select id="sctrEmpUpdate" class="form-control sctr" name="sctrEmpUpdate" >
                                    <option value="">Select Sector</option>
                                    <?php
                                    while ($rowSec = oci_fetch_assoc($stmtSec)) {
                                        echo '<option value="' . htmlspecialchars($rowSec['SECID']) . '">' . htmlspecialchars($rowSec['SECNAME']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="rnkEmpUpdate">Ranks</label>
                                <select id="rnkEmpUpdate" class="form-control rnk" name="rnkEmpUpdateEmpUpdate">
                                    <option value="">Select Rank</option>
                                    <!-- سيتم تحديث هذه القائمة عبر AJAX -->
                                </select>
                            </div>

                            <div class="form-group"> 
                                <label for="maritalStatus">departments </label>
                                <?php
                                    $sqlDep = "SELECT DepID, depName FROM T_Departments";
                                    $stmtDep = oci_parse($conn, $sqlDep);
                                    oci_execute($stmtDep);
                                ?>
                                <select id="deptEmpUpdate" name="deptEmpUpdateEmpUpdate" class="form-control" >
                                    <option value="">select department</option>
                                    <?php
                                    $currentDepartment = "1";

                                    // عرض الخيارات
                                    while ($row = oci_fetch_assoc($stmtDep)) {
                                        $selected = ($row['DEPID'] == $currentDepartment) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($row['DEPID']) . '" ' . $selected . '>' . htmlspecialchars($row['DEPNAME']) . '</option>';
                                    }

                                 
                                    ?>
                                    
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="maritalStatus">Working relationship </label>
                                <select id="wrkRltEmpUpdate" name="wrkRlt" class="form-control" >
                                    <option value=""> Working relationship  </option>
                                    <option value=" Permanent"> Permanent   </option>
                                    <option value=" Contractor"> Contractor </option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">reset</button>
                                <button type="button" id="updateEmployee"class="btn btn-primary ">SAVE</button>
                        </div>
                    </div>    
                </form>
            </div>
        </div>
    </div>
</div>
<!-- delete -->
<div class="modal delete-Employee-box" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">delete employee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <p>Are you sure you want to delete user number  <span class=" text-danger empID"></span> ?</p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-delete-Emp">DELETE</button>
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


