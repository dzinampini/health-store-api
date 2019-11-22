<?php $session_id = $this->session->userdata('id'); 
$session_role = $this->session->userdata('role');
$session_branch = $this->session->userdata('office');
$company_start_date = '2012-01-01';



//check if locked 
if($this->uri->segment(2) != 'login'){
  if($this->session->userdata('locked') == 1){
    $this->session->set_flashdata('error_msg', 'Your account has been temporarily locked. Contact ICT Support');
    redirect('/welcome/login/', 'refresh');
  }

  //check if active 
   if($this->session->userdata('active') == 0){
    $this->session->set_flashdata('error_msg', 'Your account has been deactivated. Contact ICT Support');
    redirect('/welcome/login/', 'refresh');
  }
}
else{
  $this->session->set_flashdata('error_msg', 'Your account is locked or has been deactivated. Contact ICT Support');
}

//chedck if change_password needed AND check password expiry

if($this->uri->segment(2) == 'changePassword'){
  $this->session->set_flashdata('error_msg', 'Your password needs to be changed. Change it now');
}
elseif($this->uri->segment(2) == 'changePasswordDetails'){
  //flash data is in the func
}
else{
  if($this->session->userdata('change_password') == 1 || date('Y-m-d') > $this->session->userdata('password_expiry') ){
    $this->session->set_flashdata('error_msg', 'Your password needs to be changed. Change it now');
    redirect('/welcome/changePassword/', 'refresh');
  } 
}


//alerts and notifications = messages 
$messages = 0; 

//clients 
$client_primary = 0; 
$client_secondary = 0;
$client_primary = $this->welcome_model->pendingClientPrimary(); 
$client_secondary = $this->welcome_model->pendingClientSecondary(); 

//groups
$group_primary = 0; 
$group_secondary = 0;
$group_primary = $this->welcome_model->pendingGroupPrimary(); 
$group_secondary = $this->welcome_model->pendingGroupSecondary(); 

//loans
$loan_primary = 0; 
$loan_secondary = 0;
$loan_primary = $this->welcome_model->pendingLoanPrimary(); 
$loan_secondary = $this->welcome_model->pendingLoanSecondary(); 
$loan_primary_disbursement = $this->welcome_model->pendingLoanPrimaryDisbursement(); 
$loan_secondary_disbursement = $this->welcome_model->pendingLoanSecondaryDisbursement(); 

// accounting
$fns = $this->accounting_model->pendingFinancialCategories();
$gls = $this->accounting_model->pendingGLs();
$journals = $this->accounting_model->pendingJournals();
$open_periods = $this->accounting_model->pendingOpenPeriods();
$close_periods = $this->accounting_model->pendingClosePeriods();

//configuration


//only open this to someone who has the role of doing primary approvals. so next line will be in an if statement. the same for all other counts 
$messages = $client_primary+$client_secondary+$group_primary+$group_secondary+$loan_primary+$loan_secondary+$loan_primary_disbursement+$loan_secondary_disbursement+$fns+$journals+$gls+$open_periods+$close_periods; ?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title> 
    <?php if ($messages > 0) echo '('.$messages.')'; ?>
    Mako System
  </title>

  <link rel="icon" href="<?php echo base_url(); ?>images/favicon.png" type="image/x-icon">
  <!-- Custom fonts for this template-->
  <link href="<?php echo base_url(); ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="<?php echo base_url(); ?>vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="<?php echo base_url(); ?>css/sb-admin.css" rel="stylesheet">
  
  <link href="<?php echo base_url(); ?>css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

</head>

<body id="page-top">
  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">
    <a class="navbar-brand mr-1" href="<?php echo base_url(); ?>">MAKO</a>
    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar Search -->
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" action="<?php echo base_url(); ?>clients/search">
      <div class="input-group">
        <input type="text" class="form-control" name="phrase" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <button class="btn btn-primary" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0">
      <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php 
          if($messages > 0){ ?>
            <span class="badge badge-danger">
              <?php echo $messages;  ?>
            </span>
          <?php } ?>
          
        </a>
      </li>
      <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-envelope fa-fw"></i>
          <!-- <span class="badge badge-danger">7</span> -->
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="messagesDropdown">
          <?php if($client_primary > 0 ){ ?>
            <a class="dropdown-item" href="<?php echo base_url(); ?>clients/pendingApproval">
              Client Primary Approvals (<?php echo $client_primary; ?>)
            </a>
          <?php } ?>

          <?php if($client_secondary > 0 ){ ?>
            <a class="dropdown-item" href="<?php echo base_url(); ?>clients/pendingApproval2">
              Client Secondary Approvals (<?php echo $client_secondary; ?>)
            </a>
          <?php } ?>

          <?php if($group_primary > 0 ){ ?>
            <a class="dropdown-item" href="<?php echo base_url(); ?>clients/groups">
              Group Primary Approvals (<?php echo $group_primary; ?>)
            </a>
          <?php } ?>

          <?php if($group_secondary > 0 ){ ?>
            <a class="dropdown-item" href="<?php echo base_url(); ?>clients/groups">
              Group Secondary Approvals (<?php echo $group_secondary; ?>)
            </a>
          <?php } ?>

          <?php if($loan_primary > 0 ){ ?>
            <a class="dropdown-item" href="<?php echo base_url(); ?>products/pendingPrimaryApproval">
              Loan Primary Approvals (<?php echo $loan_primary; ?>)
            </a>
          <?php } ?>

          <?php if($loan_secondary > 0 ){ ?>
            <a class="dropdown-item" href="<?php echo base_url(); ?>products/pendingSecondaryApproval">
              Loan Secondary Approvals (<?php echo $loan_secondary; ?>)
            </a>
          <?php } ?>

          <?php if($loan_primary_disbursement > 0 ){ ?>
            <a class="dropdown-item" href="<?php echo base_url(); ?>products/awaitingDisbursement">
              Loan Primary Disbursement Approvals (<?php echo $loan_primary_disbursement; ?>)
            </a>
          <?php } ?>

          <?php if($loan_secondary_disbursement > 0 ){ ?>
            <a class="dropdown-item" href="<?php echo base_url(); ?>products/awaitingSecondaryDisbursement">
              Loan Secondary Disbursement Approvals (<?php echo $loan_secondary_disbursement; ?>)
            </a>
          <?php } ?>

          <?php if($gls > 0 ){ ?>
            <a class="dropdown-item" href="<?php echo base_url(); ?>accounting/chartsOfAccounts">
              General Ledgers (<?php echo $gls; ?>)
            </a>
          <?php } ?>

          <?php if($fns > 0 ){ ?>
            <a class="dropdown-item" href="<?php echo base_url(); ?>accounting/financialCategories">
              Financial Categories (<?php echo $fns; ?>)
            </a>
          <?php } ?>

          <?php if($journals > 0 ){ ?>
            <a class="dropdown-item" href="<?php echo base_url(); ?>accounting/journals/0">
              Journals (<?php echo $journals; ?>)
            </a>
          <?php } ?>

          <?php if($open_periods > 0 ){ ?>
            <a class="dropdown-item" href="<?php echo base_url(); ?>accounting/closePeriods">
              Open Periods (<?php echo $open_periods; ?>)
            </a>
          <?php } ?>

          <?php if($close_periods > 0 ){ ?>
            <a class="dropdown-item" href="<?php echo base_url(); ?>accounting/closePeriods">
              Closed Periods (<?php echo $close_periods; ?>)
            </a>
          <?php } ?>

        </div>
      </li>
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle fa-fw"></i><?php echo $this->session->userdata('first_name').$this->session->userdata('surname'); ?>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
          <!-- <a class="dropdown-item" href="#"></a> -->
          
          <!-- <div class="dropdown-divider"></div> -->
          <a class="dropdown-item" href="<?php echo base_url(); ?>tasks/myTasks">Activity Log</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>welcome/changePassword">Change Password</a>
          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
        </div>
      </li>
    </ul>

  </nav>

  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="<?php echo base_url(); ?>">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-fw fa-folder"></i>
          <span>Clients</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
          <h6 class="dropdown-header">Individual Clients:</h6>
          <a class="dropdown-item" href="<?php echo base_url(); ?>clients/clients">View Clients</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>clients/addClient">Add Client</a>
          <div class="dropdown-divider"></div>
          <h6 class="dropdown-header">Group Clients:</h6>
          <a class="dropdown-item" href="<?php echo base_url(); ?>clients/groups">View Groups</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>clients/addGroup">Add Group</a>
          <!-- <a class="dropdown-item" href="<?php //echo base_url(); ?>clients/groupPosting">Group Posting</a> -->
          <div class="dropdown-divider"></div>
          <h6 class="dropdown-header">Transfers:</h6>
          <a class="dropdown-item" href="<?php echo base_url(); ?>clients/transferClient">Transfer Clients/Groups</a>
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-fw fa-folder"></i>
          <span>Products</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
          <a class="dropdown-item" href="<?php echo base_url(); ?>products/loans">Loans</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>products/fixedAccounts">Savings</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>products/standingInstructions">Standing Instructions</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>products/loanCalculator">Loan Calculator</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>products/fundsTransfer">Funds Transfer</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>products/runRepayments">Run Repayments</a>
          <!-- <a class="dropdown-item" href="<?php //echo base_url(); ?>products/runPenalties">Run Penalties</a> -->
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-fw fa-folder"></i>
          <span>Access Config</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
          <a class="dropdown-item" href="<?php echo base_url(); ?>config/regions">Regions</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>config/offices">Offices</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>config/users">Users</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>config/banks">Banks</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>config/fees">Fees</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>config/roles">Roles</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>config/permissions">Permissions</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>config/audits">Audit</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>clients/blacklisted">Blacklist</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>clients/crb">CRB</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>config/loanProducts">Products</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>config/sms">SMS Configuration</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>config/email">Email Configuration</a>
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-fw fa-folder"></i>
          <span>Tasks</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
          <a class="dropdown-item" href="<?php echo base_url(); ?>tasks/actions">System Actions</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>tasks/myActions">My Actions</a>      
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-fw fa-folder"></i>
          <span>Reports</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
          <a class="dropdown-item" href="<?php echo base_url(); ?>reports/clients">Client Reports</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>reports/group">Group Reports</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>reports/loans">Loans Reports</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>reports/savings">Savings Reports</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>reports/organisation">Organisation Reports</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>accounting/reports">Financial Reports</a>
        </div>
      </li>

     <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-fw fa-folder"></i>
          <span>Accounting</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
          <!-- <h6 class="dropdown-header">Charts of Accounts:</h6> -->
          <a class="dropdown-item" href="<?php echo base_url(); ?>accounting/financialCategories">Financial Categories</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>accounting/chartsOfAccounts">Charts of Accounts</a>

          <!-- <div class="dropdown-divider"></div> -->
          <!-- <h6 class="dropdown-header">Invoicing:</h6> -->
          <a class="dropdown-item" href="<?php echo base_url(); ?>accounting/suppliers">Suppliers</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>accounting/customers">Customers</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>accounting/journals/0">Journals</a>
          <!-- <div class="dropdown-divider"></div> -->
          
          <!-- <a class="dropdown-item" href="<?php //echo base_url(); ?>accounting/J">Journals</a> -->
          <!-- <a class="dropdown-item" href="<?php //echo base_url(); ?>welcome/journalTemp">Journal Template</a> -->
          <a class="dropdown-item" href="<?php echo base_url(); ?>accounting/reconciliation">Reconciliation</a>
           <!-- <a class="dropdown-item" href="<?php ///echo base_url(); ?>welcome/Teller">Teller Management</a> -->
          <!-- <a class="dropdown-item" href="<?php //echo base_url(); ?>welcome/AccountingExport">Accounting Export</a> -->
           <a class="dropdown-item" href="<?php echo base_url(); ?>accounting/periodicAccruals">Periodic Accrual</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>accounting/closePeriods">Close Periods</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>accounting/reports">Financial Reports</a>
          <!-- <a class="dropdown-item" href="<?php //echo base_url(); ?>accounting/chartsOfAccounts">Charts of Accounts</a> -->


        </div>
      </li> 
    </ul>