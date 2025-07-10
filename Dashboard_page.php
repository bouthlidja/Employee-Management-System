<?php
session_start();



if (isset($_SESSION['username']) ) {
    $pageTitle= "Dashboard";
   
    include "init.php";  
 
     include $tpl . "sidebar.php"; 
     include $tpl . "navbar.php"; 
?>

<main class="main-container"  id="dashboard"> 
  <div class="main-title">
    <h2>DASHBOARD</h2>
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

  <div class="main-cards"> <!-- start main cards  -->
    <div class="card">
        <div class="card-header">
            <span>Total Transactions</span>
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="card-number" id="totalLogs">0</div>
    </div>
    <div class="card">
        <div class="card-header">
            <span>operations today</span>
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="card-number" id="todayLogs">0</div>
    </div>
    <div class="card">
        <div class="card-header">
            <span>Most Frequent Action</span>
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="card-text" id="FrequentActionText"></div>
        <div class="card-number" id="mostFrequentAction"><div class="card-text" id="FrequentActionText"></div>0</div>
    </div>
  </div><!-- end main cards -->

  <div class="box">
    <form class="form-search">
      <input type="text" id="searchInput" class="form-control search-box" placeholder="Enter search value">
    </form>
    
    <div id="operations">
      <button class="btn btn-info" id="show">Show Last 5</button> 
      <button class="btn btn-info" id="showAll">Show All</button> 
      <button class="btn btn-danger" id="deleteSelected">Delete Selected</button> 
    </div>
  </div>

  
  <table class="table table-logs table-bordered table-striped border-primary">
            <thead class="table-dark">
                <tr>
                    <th scope="col"><input type="checkbox" id="selectAll"></th>
                    <th scope="col">Log ID</th>
                    <th scope="col"> User ID </th>
                    <th scope="col">Action Type</th>
                    <th scope="col">Action Time </th>
                    <th scope="col"> Ip Address </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
  </table>
  <div class="logsChart">
    <div class="chart logsPerDay">
     <canvas id="logsPerDay"></canvas>
     <h3>System Activity Comparison Over the Last 7 Days</h3>
    </div>
    <div class="chart">
    <canvas id="operationPieChart" ></canvas>
    <!-- <canvas id="logsPerDay"></canvas> -->
    </div>
   
  </div>
  
        
        

</main>
<?php
  include $tpl . "footer.php"; 
    
  }else{
      header('location: index.php');
  }


?>