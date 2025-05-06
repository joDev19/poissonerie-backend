<?php

    if(!function_exists('checkIfUserIsAdmin')){
        function checkIfUserIsAdmin(){
            return auth()->user()->role == "admin";
        }
    }
