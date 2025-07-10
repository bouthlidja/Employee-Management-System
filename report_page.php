<?php
session_start();



if (isset($_SESSION['username']) ) {
    $pageTitle= "report";
   
    include "init.php";  
 
     include $tpl . "sidebar.php"; 
     include $tpl . "navbar.php"; 
?>


<main class="main-container" id="report">
    <div class="main-title">
        <h2>REPORT</h2>
    </div>
    <div class="report">
        <div class="tabs">
            <div class="tab active" onclick="showTab(0)">Annual leave report</div>
            <div class="tab" onclick="showTab(1)">Leave report by employee</div>
            <div class="tab" onclick="showTab(2)">Tab 3</div>
            <div class="tab" onclick="showTab(3)">Tab 4</div>
        </div>

        <div class="tab-content-container">
            <div class="tab-content reportAnnLev   ">
                
            
                <div class="alert alert-warning" role="alert">
                    <div class="text">  </div>
                    <div class="btn-close-alert">
                        <button type="button" class="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>

                <div class="box">
               
                    <form class="form-searsh">
                        <input type="text" class="form-control inputSearsh searsh me-2" placeholder="Enter Year" >
                        <button type="button" class="btn btn-primary btn-search">Search</button>
                    </form>
                </div>
               <div class=" bodyRep">
                    <div class="empLev">
                        <p>Employees who have benefited from annual leave for the year <span class="yyyy">YYYY</span></p>
                        <table class="table table-emp-Leave table-bordered table-striped border-primary">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">leave ID</th>
                                <th scope="col">Employee ID</th>
                                <th scope="col">Name Employee</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        </table>
                    </div>

                    <div class="empNoLev">
                        <p>Employees who did not benefit from annual leave for the year <span class="yyyy">YYYY</span></p>
                        <table class="table table-emp-no-Leave table-bordered table-striped border-primary">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">leave ID</th>
                                    <th scope="col">Employee ID</th>
                                    <th scope="col">Name Employee</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
               </div>
               

                <button class="btn btn-primary btn-lg" > print Report</button>
            </div>
            <div class="tab-content active LvRptEmp ">
                <div class="box">
                    <form class="form-searsh">
                        <input type="text" class="form-control empID searsh me-2" placeholder="Enter employee ID" >
                        <input type="date" class="form-control startDate searsh me-2" placeholder="Enter Start Date" >
                        <input type="date" class="form-control endDate searsh me-2" placeholder="End Start Date" >

                        <button type="button" class="btn btn-primary btn-search-emp">Search</button>
                    </form>
                </div>
                <div class="infoEmp">
                    <p class="empID">employee ID: <span></span> </p>
                    <p class="fullName"> full nme : <span></span> </p>
                </div>

                <div class="empLev">
                        <p>Employees who have benefited from annual leave for the year <span class="yyyy">YYYY</span></p>
                        <table class="table table-info-Leave table-bordered table-striped border-primary">
                        <thead class="table-dark">
                            <tr>
 
                                <th scope="col">Type leave</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        </table>
                    </div>
            </div>
             <div class="tab-content"><!-- start -->
    
                <main class="main-container" id="leaves">
                    <div class="main-title">
                        <h2>LEAVES</h2>
                    </div>
                    <div class="main">
                        <div class="box1">
                            <form class="form-search">
                            
                            <input type="text" id="searchInput" class="form-control search-box" placeholder="Enter search value">
                            
                            </form>
                            <button class="btn  btn-info btn-add-leave float-right" id="btn-add-model"><i class="far fa-user"></i>&nbsp;&nbsp; Add New leave</button>
                        </div>
                    </div>
                    <div class="filteBox">
                    <div class="itemFilter typ">
                        <label> type leave</label>
                        <select id="filterType">
                        <option $selected value="">All</option>
                        <option value="Annual leave">Annual leave</option>
                        <option value="Marriage leave">Marriage leave</option>
                        <option value="Bereavement leave">Bereavement leave</option>
                        <option value="Sick leave">Sick leave</option>
                        <option value="Maternity leave">Maternity leave</option>
                        <option value="Hajj leave">Hajj leave</option>
                        </select>
                    </div>
                    <div class="itemFilter rsn">
                        <label>reason leave</label>
                        <select id="filterRsn">
                        <option $selected value=""> All</option>
                        <option value="Rest">Rest</option>
                        <option value="Marriage">Marriage</option>
                        <option value="Bereavement ">Bereavement</option>
                        <option value="Health Reasons">Health Reasons</option>
                        <option value="Childbirth">Childbirth</option>
                        <option value="Performing Hajj">Performing Hajj</option>
                        </select>
                    </div>
                    <div class="itemFilter rsn">
                        <label>status leave</label>
                        <select id="filterStts">
                        <option $selected value=""> All</option>
                        <option value="Ended">Ended</option>
                        <option value="Not Ended">Not Ended</option>
                        </select>
                    </div>
                    <div class="itemFilter strDat">
                        <label>start date:</label>
                        <input type="date" id="filterStartDate">
                    </div>
                    <div class="itemFilter endDat">
                        <label>end date:</label>
                        <input type="date" id="filterEndDate">
                    </div>
                    </div>
                    <table class="table table-Leave table-bordered table-striped border-primary">
                    <thead class="table-dark">
                        <tr>
                        <th scope="col">leave ID</th>
                        <th scope="col">Name Employee</th>
                        <th scope="col">type</th>
                        <th scope="col">reason </th>
                        <th scope="col">duration</th>
                        <th scope="col">start date </th>
                        <th scope="col">end date </th>
                        <th scope="col">status </th>
                        <th scope="col">print </th>
                        <th scope="col">update </th>
                        <th scope="col">delete </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr>
                            <td>1</td>
                            <td>ali Bouthlidja</td>
                            <td>type1</td>
                            <td>reason</td>
                            <td>18</td>
                            <td>12-JAN-2024</td>
                            <td>30-JAN-2024</td>
                            <td>
                                <button class="btn btn-success" id="btn-update-leave" >Update</button>
                            </td>
                            <td>
                                <button class="btn btn-danger" id="btn-delete-leave">Delete</button>
                            </td>
                        </tr>  -->
                    </tbody>
                    </table> 

                    <!-- <div class="links">
                    <a href="report_page.php" target="_self" rel="noopener noreferrer">Report</a>
                    </div> -->
                </main>

            </div> <!--end -->
            <div class="tab-content">محتوى التبويب 4</div>
        </div>
    </div>
</main>

<?php
  include $tpl . "footer.php"; 
    
  }else{
      header('location: index.php');
  }


?>