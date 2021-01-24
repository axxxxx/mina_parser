<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\ArrayHelper;

class CheckController extends AppController {

    public function actionIndex() {

        $this->layout=false;


        $cache = Yii::$app->cache;

        if(!$aMina=$cache->get('mina')){

            # insert the html code here from the page with links to all addresses (https://minaexplorer.com/ledger)
            $htmlTable="";

            $aMina=[];
            if (preg_match_all('|<tr>(.+)</tr>|isU', $htmlTable, $arr)) {
                foreach ($arr[0] as $value) {
                    if (preg_match_all('|<td>(.+)</td>|isU', $value, $td)) {
                        $aItem=explode('" class="text-default letter-icon-title">',$td[0][0]);
                        $aItem[0]=str_replace('<td><a href="/wallet/','',$aItem[0]);
                        $aItem[1]=str_replace('</a></td>','',$aItem[1]);
                        $aItem[2]=str_replace('<td>','',$td[0][2]);
                        $aItem[2]=str_replace('</td>','',$aItem[2]);
                        $aItem[3]=str_replace('<td>','',$td[0][1]);
                        $aItem[3]=str_replace('</td>','',$aItem[3]);
                        if($aItem[1]=='O(1) Labs'||$aItem[1]=='Unknown'){
                            continue;
                        }
                        $aMina[]=$aItem;
                    }
                }
            }
            ArrayHelper::multisort($aMina, [2], SORT_DESC);
            $cache->set('mina', serialize($aMina),60*60*24*7);
            echo 'Cache created';
        }else{
            $aMina=unserialize($aMina);

            $i=3;
            foreach ($aMina as $key=>$value){
                $i++;
                if(isset($aMina[$key][4])&&isset($aMina[$key][5])&&$aMina[$key][4]>=$aMina[$key][5]){
                    continue;
                }


                $sHtml=null;

                // get page count
                if(!isset($aMina[$key][4])){
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://minaexplorer.com/wallet/'.$value[0].'/transactions');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Accept: application/json',
                    ));
                    $sHtml=curl_exec($ch);
                    curl_close ($ch);
                    preg_match('|<div class="card-footer bg-white d-sm-flex justify-content-sm-between align-items-sm-center">(.+)</strong>|isU', $sHtml, $match);
                    $iTx=explode('<strong>',$match[1]);
                    $iPages=ceil($iTx[1]/50);
                    $aMina[$key][4]=0;
                    $aMina[$key][5]=$iPages;
                    $aMina[$key][6]=0; // input
                    $aMina[$key][7]=0; // output
                    $cache->set('mina', serialize($aMina),60*60*24*7);
                }

                // get tx
                if($aMina[$key][4]<$aMina[$key][5]){

                    // 10 pages
                    foreach ([1,2,3,4,5,6,7,8,9,10] as $count){
                        $aMina[$key][4]=$aMina[$key][4]+1;

                        $ch = curl_init();
                        $sUrl='https://minaexplorer.com/wallet/'.$value[0].'/transactions?page='.$aMina[$key][4];
                        curl_setopt($ch, CURLOPT_URL, $sUrl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Accept: application/json',
                        ));
                        if(!$sHtml=curl_exec($ch)){
                            curl_close ($ch);
                            break;
                        }
                        curl_close ($ch);

                        if (preg_match_all('|<tr>(.+)</tr>|isU', $sHtml, $arrtx)) {
                            foreach ($arrtx[0] as $valtx) {
                                $tx=null;
                                if(substr_count($valtx,'icon-arrow-up7')){
                                    $tx='output';
                                }elseif(substr_count($valtx,'icon-arrow-down7')){
                                    $tx='input';
                                }
                                if($tx){
                                    $aTx=explode('<h6 class="font-weight-semibold mb-0">',$valtx);
                                    $aTx=explode('</h6>',$aTx[2]);
                                    $sTx=trim($aTx[0]);
                                    //exit;
                                    if($sTx>=200){
                                        if($tx=='input'){
                                            $aMina[$key][6]=$aMina[$key][6]+$sTx;
                                        }else{
                                            $aMina[$key][7]=$aMina[$key][7]+$sTx;
                                        }
                                        $cache->set('mina', serialize($aMina),60*60*24*7);
                                    }
                                }
                            }

                            $cache->set('mina', serialize($aMina),60*60*24*7);
                            if($aMina[$key][4]>=$aMina[$key][5]){
                                break;
                            }
                            $cache->set('mina', serialize($aMina),60*60*24*7);
                        }

                        $cache->set('mina', serialize($aMina),60*60*24*7);
                    }
                }
                $cache->set('mina', serialize($aMina),60*60*24*7);
                if($i>=1){
                    break;
                }
            }
            $cache->set('mina', serialize($aMina),60*60*24*7);

            // auto-refresh of the page in the browser to continue parsing.
            //echo '<meta http-equiv="refresh" content="2" />';

        }

        return $this->render('index', [
            'mina' => $aMina,
        ]);
    }


}

