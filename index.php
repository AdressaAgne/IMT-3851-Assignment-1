<?php
    require_once('app/app.php');

    /**
     * if someone clicked the upload button
     */
    if(isset($_FILES) && isset($_POST['type'])){
        // upload the file and echo msg
        echo $app->upload($_FILES['file'], $_POST['type']);
    }
    
    // get the account selected in $_GET, if that does not exist set account with id 0 as default
    $main = $app->accounts[((isset($_GET['id']) && !empty($_GET['id'])) ? (array_key_exists($_GET['id'], $app->accounts) ? $_GET['id'] : 0) : 0)];

    // sort by $_GET['t'], if not set sort by date, if illiage sort, sort by date
    $sort_by = (isset($_GET['t']) && preg_match("[date|value|currency_type]", $_GET['t']) ? $_GET['t'] : 'date');

    // sort deposits and withdrawals by $sort_by
    $app->sorting($main->deposits, $sort_by);
    $app->sorting($main->withdrawals, $sort_by);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bank</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    
    <nav>
        <h1>The Royal Bank of S.W.A.G.</h1>
        <h2>Customers <?= count($app->customers) ?></h2>
        <h2>Accounts <?= count($app->accounts) ?></h2> 
        <small class="nav">Sort with: 
            <a href="<?= $app->toogle_get('desc') ?>"><?= (isset($_GET['desc']) ? 'asc' : 'desc') ?></a>
            <a href="<?= $app->GET_request('t', "date") ?>">Date</a>
            <a href="<?= $app->GET_request('t', "value") ?>">Amount</a>
            <a href="<?= $app->GET_request('t', "currency_type") ?>">Currency Type</a>
        </small>       
    </nav>
    
    <aside>
        <div class="customer"><h2>Customers</h2></div>
        <hr>
        <?php 
        //loop trhough all the customers
        foreach($app->customers as $customer) { ?>
        <div class="customer">
            <h3><?= $customer->get_name() ?></h3>
            <p>Assets: <?= $customer->get_assets() ?>NOK</p>
            <small>Accounts</small>
            <ul class="accounts">
                  <?php 
                  //loop though all the customers accounts
                  foreach($customer->get_accounts() as $account) { ?>
                        <li class="account_item">
                            <a href="<?= $app->GET_request('id', $account->get_id()) ?>"><small>#<?= $account->get_account_number() ?></small></a>
                        </li> 
                  <?php } ?>
            </ul>
        </div>
        <hr>
        <?php } ?>
    </aside>
    
    <main>
       
        <h1><?= $main->get_holder()->get_name() ?> 
            <small>Birthday: <?= $main->get_holder()->get_birthdate()?></small>
            <small>Address: <?= $main->get_holder()->get_address()?></small>
            <small>Total Assets: <?= $main->get_holder()->get_assets()?>NOK</small>
        </h1>
       
       
        <h1>#<?= $main->get_account_number() ?> <small>Balance: <?= $main->get_balance() ?><?= $main->get_currency_type() ?></small></h1>
        
        <div class="col">
           <h2>Withdrawals: <?= count($main->get_withdrawals()) ?></h2>
           <ul>
            <?php 
            // loop though all the withdrawals of the current account viewed
            foreach($main->get_withdrawals() as $w) { ?>
                <li class="transaction">
                    <small><?= $w->get_date() ?></small>
                    <p>Amount: -<?= $w->get_value() ?><?= $w->get_currency_type() ?></p>
                    <small>Sent to: <?= $w->get_from()->get_holder()->get_name() ?> (<a href="<?= $app->GET_request('id', $w->get_from()->get_id()) ?>">#<?= $w->get_from()->get_account_number() ?></a>)</small>
                </li>
            <?php } ?>
            </ul>
        </div>
        <div class="col">
           <h2>Deposits: <?= count($main->get_deposits()) ?></h2>
           <ul>
            <?php 
            // loop trhough all the deposites of the current account viewd
            foreach($main->get_deposits() as $d) { ?>
                <li class="transaction">
                    <small><?= $d->get_date() ?></small>
                    <p>Amount: <?= $d->get_value() ?><?= $d->get_currency_type() ?></p>
                    <small>Sent from: <?= $d->get_to()->get_holder()->get_name() ?> (<a href="<?= $app->GET_request('id', $d->get_to()->get_id()) ?>">#<?= $d->get_to()->get_account_number() ?></a>)</small>
                </li>
            <?php } ?>
            </ul>
        </div>
        
        <div class="row">
            <small>Upload datasets</small>
            <form method="post" action="" enctype="multipart/form-data">

                <input type="file" name="file">

                <label>
                    <input type="radio" value="customer" name="type" checked> Customer
                </label>
                <label>
                    <input type="radio" value="account" name="type"> Account
                </label>
                <label>
                    <input type="radio" value="transaction" name="type"> Transaction
                </label>

                <input type="submit" value="upload">

            </form>
        </div>
    </main>
    
</body>
</html>