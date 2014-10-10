<?php


require '../vendor/autoload.php';
Use Aws\DynamoDb\DynamoDbClient;



/**
 * Allows for the display of a site's cron job history.  Requires a site id to be entered.  Displays the logs from
 * dynamodb at start, simple filtering will be included later.
 *
 *
 */


    $statusNameArray = array(
        'S' => 'Started',
        'C' => 'Complete'
    );;

    $SiteId = $_GET['siteId'];

    if($SiteId){
        // Then we need to list the records from DynamoDB. Scan based on the SiteId.

        // Load the DynamoDB client
        $client = DynamoDbClient::factory(array('region'=>'eu-west-1'));

        // Make the scan request

        $iterator = new  \Aws\DynamoDb\Iterator\ItemIterator($client->getIterator('Scan', array(
            "TableName"         => "cronwatcher-job"
        )));

        $newIterator = array();
        // Handle the simple fact that DynamoDB doesn't return the results in any particular order.
        foreach($iterator AS $row){
            $newIterator[strtotime($row->get('created'))] = $row;
        }

        krsort($newIterator);
    }


?>
<!DOCTYPE html>
<html lang="en">
<body>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CronWatcher Admin</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

    </head>
    <body>

    <div class="container">
        <?php if(!$SiteId){ ?>
            <form class="form-signin" role="form" method="GET">
                <h2 class="form-signin-heading">Please tell us your Site ID</h2>
                <input type="string" class="form-control" placeholder="Site ID" required autofocus name="siteId"><br />

                <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
            </form>
        <?php } else { ?>
            <h2>Cron job list for site <?php echo $SiteId;?> <small>(<a href="/admin/index.php">Change site</a>)</small></h2>


            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Site Id</th>
                        <th>Cron Id</th>
                        <th>Status</th>
                        <th>Started</th>
                        <th>Completed</th>
                        <th>Identifier</th>
                    </tr>
                </thead>
                <tbody class=".table-striped">
                <?php foreach($newIterator AS $row){
                    list($rowSiteId, $rowCronId) = explode("|", $row->get('SiteIdAndCronId'));
                    if($rowSiteId == $SiteId){
                    ?>
                    <tr>
                        <td><?php echo $rowSiteId;?></td>
                        <td><?php echo $rowCronId;?></td>
                        <td><?php echo $statusNameArray[$row->get('status')];?></td>
                        <td><?php echo $row->get('created');?></td>
                        <td><?php echo $row->get('completed');?></td>
                        <td><?php echo $row->get('identifier');?></td>
                    </tr>
                <?php } }?>
                </tbody>
            </table>
        <?php } ?>
    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
    </body>
</body>
</html>