
</div>

    <!-- jquery -->
<script src="<?php echo $js; ?>jquery-1.12.1.min.js"></script>
<!-- bootstrap -->
<script src="<?php echo $js; ?>bootstrap.min.js"></script>

<!-- my file js -->
<script type="module" src="<?php echo $js; ?>script.js"></script>
 

<?php if ($pageTitle == "Login") {   ?>
    <script type="module" src="<?php echo $js; ?>login_script.js"></script>
 <?php  } ?> 

 <?php if ($pageTitle == "change password") {   ?>
    <script type="module" src="<?php echo $js; ?>change_password_script.js"></script>
 <?php  } ?> 
<?php if ($pageTitle == "Users") {   ?>
    <script type="module" src="<?php echo $js; ?>users_script.js"></script>
 <?php  } ?>

 <?php if ($pageTitle == "Employees") {   ?>
    <script type="module" src="<?php echo $js; ?>employee_script.js"></script>
 <?php  } ?>  
 <?php if ($pageTitle == "folders") {   ?>
    <script type="module" src="<?php echo $js; ?>folder_script.js"></script>
 <?php  } ?> 
 <?php if ($pageTitle == "Leaves") {   ?>
    <script type="module" src="<?php echo $js; ?>Leave_script.js"></script>
 <?php  } ?> 

 <?php  if($pageTitle == "Transfers")  {   ?>
 <script type="module" src="<?php echo $js; ?>transfer_script.js"></script>
 <?php  } ?> 

 <?php  if($pageTitle == "resignations")  {   ?>
 <script type="module" src="<?php echo $js; ?>resignation_script.js"></script>
 <?php  } ?> 

 <?php  if($pageTitle == "Retirement")  {   ?>
 <script type="module" src="<?php echo $js; ?>Retirement_script.js"></script>
 <?php  } ?>

 <?php  if($pageTitle == "profile")  {   ?>
 <script type="module" src="<?php echo $js; ?>profile_script.js"></script>
 <?php  } ?>

 <?php  if( $pageTitle == "Dashboard")  {   ?>
 <script type="module" src="layout/js/chart.js"></script>
 <script type="module" src="<?php echo $js; ?>dashboard_script.js"></script>
 <?php  } ?>

 <?php  if( $pageTitle == "report")  {   ?>
 <script type="module" src="layout/js/chart.js"></script>
 <script type="module" src="<?php echo $js; ?>report_script.js"></script>
 <?php  } ?>

 <?php  if( $pageTitle == "contact")  {   ?>
 <script type="module" src="layout/js/chart.js"></script>
 <script type="module" src="<?php echo $js; ?>contact_script.js"></script>
 <?php  } ?>

 <?php  if( $pageTitle == "request")  {   ?>
 <script type="module" src="layout/js/chart.js"></script>
 <script type="module" src="<?php echo $js; ?>request_script.js"></script>
 <?php  } ?>

 <?php  if( $pageTitle == "process")  {   ?>
 <script type="module" src="layout/js/chart.js"></script>
 <script type="module" src="<?php echo $js; ?>process_request_script.js"></script>
 <?php  } ?>

 
</body>
</html>