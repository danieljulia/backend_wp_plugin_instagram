<?php

/** sustituto de file_get_contents */

function file_get_contents_curl($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}


function instagram_get_photos($username,$num=5){

    //look for user id
    $requser="https://api.instagram.com/v1/users/search?q=".$username."&client_id=".INSTAGRAM_KEY;
    $res=file_get_contents_curl($requser);

    $user_data=json_decode($res);
    $user_id=0;
    //print_r($user_data);

    foreach($user_data->data as $user){
        if( $user->username==$username){
            $user_id=$user->id;
           
            break;
        }
    }
    if($user_id==0){
        return false;
    }


    //and now get the pictures
    $uri="https://api.instagram.com/v1/users/".$user_id."/media/recent?count=".$num."&client_id=".INSTAGRAM_KEY;
    $res=file_get_contents_curl($uri);
    $content=json_decode($res);
    return $content;
}
