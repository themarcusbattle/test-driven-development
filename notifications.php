<?php
echo 'you got me?'; exit;
require __DIR__ . "/vendor/autoload.php";

use Twilio\Rest\Client as TwilioClient;

$dotenv = Dotenv\Dotenv::create(__DIR__);

$dotenv->load();

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    $webhook_info = file_get_contents('php://input');

    parse_str( $webhook_info, $output );

    $webhook_info = json_decode($output['payload']);

    if ($webhook_info->state === 'failed') {
        $message = build_message($webhook_info);
        send_sms($message);
    }
}

function build_message( $webhook_info )
{

    $committer_name = $webhook_info->committer_name;
    $commited_at = $webhook_info->committed_at;
    $build_url = $webhook_info->build_url;

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
