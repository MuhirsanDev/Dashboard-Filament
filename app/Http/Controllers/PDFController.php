<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use PDF;

class PDFController extends Controller
{
    public function downloadpdf(){
        $users = User::all();

        $data = [
            'date' => date('m/d/Y'),
            'users' => $users
        ];

        $pdf = PDF::loadView('userPDF', $data);

        return $pdf->download('asadeveloper.pdf');
    }

    public function userpdf($id){

        $user = User::find($id);

        return $user;
    }
}
