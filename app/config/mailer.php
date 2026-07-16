<?php 
    return [
        # Set the SMTP server to send through
        "host" => "smtp.office365.com",
        
        #TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        "port" => 587,

        # SMTP username
        "username" => "portal@haus.com.tr",

        #SMTP password
        "password" => "AsAp2022!",

        #Enable SMTP authentication
        "smtpauth" => true
    ];