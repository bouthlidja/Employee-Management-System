<?php
session_start();


if (isset($_SESSION['username']) ) {
    $pageTitle= "folders";
   
    include "init.php";  
 
     include $tpl . "sidebar.php"; 
     include $tpl . "navbar.php"; 
?>


<main class="main-container" id="folder">
  <div class="alert" role="alert">
            <div class="text">
                <!-- This is a success alert—check it out!  -->
            </div>
            <div class="btn-close-alert">
              <button type="button" class="close" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </div>
        <div class="main-title">
            <h2>FOLDERES</h2>
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
        <div class="box1">
            <form class="form-search">
                <input type="text" id="searchInput" class="form-control search-box" placeholder="Enter search value">
            </form>
            <button class="btn  btn-info btn-add-folder float-right" ><i class="fa-solid fa-plus"></i>&nbsp;&nbsp; Add New folder</button>
        </div>

        <table class="table table-folder table-bordered table-striped border-primary">
            <thead class="table-dark">
                <tr>
                    <th scope="col">folder ID</th>
                    <th scope="col"> Employee ID </th>
                    <th scope="col">full name</th>
                    <th scope="col">open </th>
                    <th scope="col">upload </th>
                    <th scope="col"> delete </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table> 

  <!-- add folder -->
  <div class="modal model-add " id="model-add" tabindex="-1" role="dialog">     
      <div class="modal-dialog" role="document">
          <div class="modal-content">

              <div class="panel-heading">
                  <h5 class="modal-title">Add folder</h5>
              
                  <button type="button" class="close closeAdd" id="btn-close-model" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
              </div>

              <div class="modal-body">
                  <div class="form-group ">   
                      <label for="empID"> enter employee ID  </label>
                      <input type="text" class="form-control empID "  name="" >         
                  </div>
              </div>
              <div class="modal-footer">  
                  <button type="button" class="btn btn-primary btn-save-folder">SAVE</button>
              </div>       
          </div>
              
      </div>
  </div>
  <!-- upload -->

  <div class="modal model-upload " id="model-upload" tabindex="-1" role="dialog">     
      <div class="modal-dialog" role="document">
          <div class="modal-content">

              <div class="panel-heading">
                  <h5 class="modal-title">upload document</h5>
                  <button type="button" class="close closeAdd" id="btn-close-model" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
                  <span aria-hidden="true">&times;</span>
                  </button>
              </div>

              <div class="modal-body">
                <h2>upload document</h2>
                <input type="file" id="fileInput">
              </div>
              <div class="modal-footer">  
                <button class="btn btn-primary" onclick="uploadFile()">upload</button>
              </div>       
          </div>
              
      </div>
  </div>

  <!-- delete folder -->

  <div class="modal model-delete" id="model-delete" tabindex="-1" role="dialog">  
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">delete employee</h5>
          <button type="button" class="close" id="btn-close-model-delete" data-dismiss="modal" aria-label="Close" onclick="closeMdlDlt()">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <p>Are you sure you want to delete folder ID  <span class="text-danger fldID"></span> ?</p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeMdlDlt()">Close</button>
          <button type="button" class="btn btn-primary btn-delete">DELETE</button>
        </div>
      </div>
    </div>
  </div>
</main>





<?php
  include $tpl . "footer.php"; 
    
  }else{
      header('location: index.php');
  }


?>