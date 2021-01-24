
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
<style>
    .dataTable {
        text-align: center;
    }
    .input {
        display: block;
        background-color: #9dc89d;
        color: #fff;
        font-size: 12px;
        padding: 5px;
    }
    .input:hover {
        background-color: #54b053;
    }

    .output {
        display: block;
        background-color: #f1b2b2;
        color: #fff;
        font-size: 12px;
        padding: 5px;
    }
    .output:hover {
        background-color: #f17d74;
    }
    .spoiler_links {
        cursor:pointer;
    }
    .spoiler_body {
        display:none;
        font-size: 13px;
        white-space: nowrap;
    }
    .spoiler_body ol {
        padding-left: 20px;
        text-align: left;
    }
    tr td:first-child {
        font-size: 12px;
    }
</style>

<div style="margin: 0 auto;max-width:1200px;">
    <h1>Don't break the rules ...</h1>
    <div style="margin-bottom:20px;">Sum input and sum output from tx >= 200 tokens. Show accounts where sum input > 1000 or sum output > 1000 && balance > 10 tokens<br>Data actuality: January 24 evening</div>
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
                    if($item[6]==$item[7]){
                        continue;
                    }
                    $sListInput='';
                    if($item[6]>0){
                        $sListInput.='<ol reversed>';
                        $item[10]=array_reverse($item[10]);
                        foreach ($item[10] as $in){
                            $sListInput.='<li>'.$in.'</li>';
                        }
                        $sListInput.='</ol>';
                    }

                    $sListOutput='';
                    if($item[7]>0){
                        $sListOutput.='<ol reversed>';
                        $item[11]=array_reverse($item[11]);
                        foreach ($item[11] as $out){
                            $sListOutput.='<li>'.$out.'</li>';
                        }
                        $sListOutput.='</ol>';
                    }

                    echo '<tr>
                <td>'.$item[0].'</td>
                <td>'.$item[1].'</td>
                <td>'.$item[2].'</td>
                <td>'.$item[3].'</td>
                <td>'.$item[6].($item[6]>0?'<div class="input spoiler_links">Show '.$item[8].' tx</div><div class="spoiler_body">'.$sListInput.'</div>':'').'</td>
                <td>'.$item[7].($item[7]>0?'<div class="output spoiler_links">Show '.$item[9].' tx</div><div class="spoiler_body">'.$sListOutput.'</div>':'').'</td>
            </tr>';
                }
            }
        }
        ?>

        </tbody>
    </table>
    <div style="margin: 30px 0">
        * transactions are excluded when the recipient's address is equal to the sender's address (to itself)<br>
        * excluded addresses in which the amount received is equal to the amount sent (got rid of the received tokens)
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable( {
            lengthMenu: [[25, 50, -1], [25, 50, "All"]],
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

        jQuery('body').on('click','.spoiler_links',function(){
            $(this).parent().children('div.spoiler_body').toggle('normal');
            return false;
        });




    } );

</script>
</body>

</html>




