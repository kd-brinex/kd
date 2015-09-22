<?php
echo $this->render('_search',['catalog'=>$catalog,'params'=>$params]);

if (isset($provider))
{
echo  $this->render('@app/modules/tovar/views/tovar/finddetails',
    ['provider' => $provider, 'columns' =>$columns]);}