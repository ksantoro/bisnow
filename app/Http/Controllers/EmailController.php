<?php

namespace App\Http\Controllers;

use App\Mail\EmailMailable;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    public function queueEmail(Request $request)
    {
        /**
         * Validate Incoming Request
         */
        $validator = Validator::make($request->all(), [
            'email_address' => 'email|required|max:255',
            'message' => 'required|string',
            'attachment' => 'string',
            'attachment_filename' => 'string'
        ]);

        if (!$validator->fails()) {
            $validatedData = $validator->validated();

            $email = new Email();
            $email->email_address = $validatedData['email_address'];
            $email->message = $validatedData['message'];

            if (!empty($validatedData['attachment']) && base64_decode($validatedData['attachment'], true)) {
                $email->attachment = $validatedData['attachment'];
                $email->attachment_filename = $validatedData['attachment_filename'];
            }

            $email->save();

            Mail::to($email->email_address)->queue(new EmailMailable($email));

            $email->was_sent = 1;
            $email->sent_at = now();
            $email->save();

            return response()->json($validatedData);
        }

        return response($validator->errors(), 400);
    }

    public function successEmails()
    {
        $emails = Email::where('was_sent', '1')->pluck('email_address');

        return response($emails);
    }
}
