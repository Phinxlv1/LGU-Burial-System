<?php
/**
 * SupportController.php
 * Path: app/Http/Controllers/SupportController.php
 * Description: Controller for handling the User Manual and Knowledge Center.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * Display the Integrated Knowledge Center (User Manual).
     */
    public function manual()
    {
        return view('support.manual');
    }
}
