<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function failed( Request $request ) {

        if ( 'failed' === $request->payload->state ) {
            $message = build_message( $request->payload );
            send_sms( $message );
        } else {
            send_sms( 'didnt work' . $request->payload );
        }
    }

    function build_message( $data )
    {
        $committer_name = $data->committer_name;
        $commited_at    = $data->committed_at;
        $build_url      = $data->build_url;

        $message ="Hi there, I hate to be the bearer of bad news but your build has failed :-("
        .". The commit was made by $committer_name at $commited_at. For more information, please check it "
        . "out here: $build_url.";

        return $message;
    }

    /**
    * Send an SMS about the failure
    *
    * @param string $message - failure message
    *
    * @return void
    */
    function send_sms( $message )
    {
        $twilio_sid   = getenv( "TWILIO_SID" );
        $twilio_token = getenv( "TWILIO_TOKEN" );

        $twilio = new TwilioClient( $twilio_sid, $twilio_token );

        $my_twilio_number = getenv( "TWILIO_NUMBER" );

        $twilio->messages->create(
            // Where to send a text message
            "13368252007",
            array(
                "from" => $my_twilio_number,
                "body" => $message
            )
        );
    }
}
