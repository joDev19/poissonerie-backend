<?php

    if(!function_exists('checkIfUserIsAdmin')){
        function checkIfUserIsAdmin(){
            return auth()->user()->role == "admin";
        }
    }
    if(!function_exists('connectedBtqId')){
        function connectedBtqId(){
            return auth()->user()->role == "admin" ? auth()->user()->id : auth()->user()->user_id;
        }
    }
