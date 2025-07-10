<?php
session_start();



if (isset($_SESSION['username']) ) {
    $pageTitle= "Users";
   
    include "init.php";  
 
     include $tpl . "sidebar.php"; 
     include $tpl . "navbar.php"; 
?>




            
  
<main class="main-container" id="user">
  <div class="main-title">
    <h2>USERS</h2>
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
                <input type="text" class="form-control searsh me-2" placeholder="Enter user ID" >
                <button type="button" class="btn btn-primary">Search</button>
            </form>
          
      <button class="btn  btn-info btn-add-users float-right" ><i class="fa-solid fa-plus"></i>&nbsp;&nbsp; Add New User</button>
    </div>
  </div>

  <table class="table table-user table-bordered table-striped border-primary">
      <thead class="table-dark">
        <tr>
          <th scope="col">user ID</th>
          <th scope="col">username</th>
          <th scope="col">Email</th>
          <th scope="col">phone number</th>
          <th scope="col">Role</th>
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

<div class="add-users">
  <div class="container">
        <div class="row col-md-6 col-md-offset-3">
          <div class="panel">
            <div class="panel-heading">
              <div class="text">
                add new User
              </div>
              <button class="btn-add-close">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
              </button>
            </div>
            <div class=" panel-body">
            <form id="addUsrForm">
              <div class="form-group">
                <label>Username</label>
                <input type="text"id="usrnm" name="usrnm" class="form-control" required />
              </div>
              <div class="form-group">
                <label>ID employee</label>
                <input type="text"id="empID" name="usrnm" class="form-control" required />
              </div>

              <div class="form-group">
                <label>Password</label>
                <input type="password" id="pass" name="pass" class="form-control" required />
              </div>

              <div class="form-group">
                <label>Email</label>
                <input type="email" id="eml" name="eml" class="form-control" required />
              </div>

              <div class="form-group">
                <label>Phone Number</label>
                <input type="text"  id="phn" name="phn" class="form-control" required />
              </div>

              <div class="form-group">
                <label>Role</label>
                <input type="text" id="rl" name="rl" class="form-control" required />
              </div>

              <button type="button" id="btn-add-user" class="btn btn-primary">Add User</button>
            </form>
          </div>
            
        </div>
      </div>
  </div>  
</div>


<div class="update-users">
  <div class="container">
        <div class="row col-md-6 col-md-offset-3">
          <div class="panel">
            <div class="panel-heading">
              <div class="text">
               update User
              </div>
              <button class="btn-update-close">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
              </button>
            </div>
            <div class=" panel-body">
            <form id="updateUsrForm">
              <div class="form-group">
                <input type="hidden"id="updateUsrId" name="usrnm" class="form-control"  required />
              </div>

              <div class="form-group">
                <label>Username</label>
                <input type="text"id="updateUsrnm" name="usrnm" class="form-control" required />
              </div>

              <!-- <div class="form-group">
                <label>Password</label>
                <input type="password" id="updateUsrPass" name="pass" class="form-control" required />
              </div> -->

              <div class="form-group">
                <label>Email</label>
                <input type="email" id="updateUsrEml" name="eml" class="form-control" required />
              </div>

              <div class="form-group">
                <label>Phone Number</label>
                <input type="text"  id="updateUsrPhn" name="phn" class="form-control" required />
              </div>

              <div class="form-group">
                <label>Role</label>
                <input type="text" id="updateUsrRrl" name="rl" class="form-control" required />
              </div>

              <button type="button" id="btn-updata-user" class="btn btn-primary">update User</button>
            </form>
          </div>
            
        </div>
      </div>
  </div>  
</div>

<div class="modal delete-user-box" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <p>Are you sure you want to delete user number  <span class=" text-danger userID"></span> ?</p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-delete-user">DELETE</button>
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