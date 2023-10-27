<?php

namespace App\Http\Controllers;

use App\Support\Controllers\AdminBaseController;

class WelcomeController extends AdminBaseController
{
    public function redirectToAdmin() {
        return redirect('/admin');
    }
}

