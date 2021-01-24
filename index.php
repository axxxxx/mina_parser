<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
    <title>Mina</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="description" content="This is a default index page for a new domain."/>
    <style type="text/css">
        body {font-family:arial;}
    </style>
</head>

<body>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">


<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>

    <div style="margin: 0 auto;max-width:1200px;">
        <h1>Don't break the rules ...</h1>
        <div style="margin-bottom:20px;">Sum input and sum output from tx >= 200 tokens. Show accounts where sum input > 1000 or sum output > 1000 && balance > 10tokens<br>Data actuality: night / morning of January 24</div>
<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Address</th>
                <th>Discord</th>
                <th>Nonce</th>
                <th>Balance</th>
                <th>Input sum</th>
                <th>Output sum</th>
            </tr>
        </thead>
        <tbody>

        <?php
        if (isset($mina)){
            foreach ($mina as $item){
                if($item[6]>1000||$item[7]>1000){
                    echo '<tr>
                <td>'.$item[0].'</td>
                <td>'.$item[1].'</td>
                <td>'.$item[2].'</td>
                <td>'.$item[3].'</td>
                <td>'.$item[6].'</td>
                <td>'.$item[7].'</td>
            </tr>';
                }
            }
        }
        ?>
        </tbody>
    </table>
    </div>
    
    <script>
$(document).ready(function() {
    $('#example').DataTable( {
        lengthMenu: [[50, -1], [50, "All"]],
        order: [[ 2, "desc" ]],
        columnDefs: [ {
            targets: [ 0 ],
            orderData: [ 0, 1 ]
        }, {
            targets: [ 1 ],
            orderData: [ 1, 0 ]
        }, {
            targets: [ 4 ],
            orderData: [ 4, 0 ]
        }
        ]
    } );

} );
    </script>
</body>

</html>

