<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);
try {
  $mail->SMTPDebug  = 0;
  $mail->isSMTP();
  $mail->Host       = 'smtp.office365.com';
  $mail->SMTPAuth   = true;
  $mail->Username   = 'meeting9@civilengineering.co.th';
  $mail->Password   = 'init@1234';
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port       = 587;
  $mail->CharSet    = 'UTF-8';

  $mail->setFrom('meeting9@civilengineering.co.th');
  $mail->addAddress('Sales-Marketing@civilengineering.co.th');

  $mail->isHTML(true);
  $mail->Subject = $_POST['subject'];

  $email = $_POST['email'];
  $name = $_POST['name'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];
  $html = '';
  $html .= '
    <h6>ชื่อ: ' . $name . '</h6>
    <h6>อีเมล: ' . $email . '</h6>
    <h6>เรื่อง: ' . $subject . '</h6>
    <br />
    <h6>ข้อความ</h6>
    <p>' . $message . '</p>
  ';
  $mail->Body    = $html;

  $mail->send();
  echo 'Message has been sent';
} catch (Exception $e) {
  echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

if (false) {
  /**
   * Requires the "PHP Email Form" library
   * The "PHP Email Form" library is available only in the pro version of the template
   * The library should be uploaded to: vendor/php-email-form/php-email-form.php
   * For more info and help: https://bootstrapmade.com/php-email-form/
   */

  // Replace contact@example.com with your real receiving email address
  $receiving_email_address = 'contact@example.com';

  if (file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php')) {
    include($php_email_form);
  } else {
    die('Unable to load the "PHP Email Form" Library!');
  }

  $contact = new PHP_Email_Form;
  $contact->ajax = true;
  $contact->to = $receiving_email_address;
  $contact->from_name = $_POST['name'];
  $contact->from_email = $_POST['email'];
  $contact->subject = $_POST['subject'];

  // Uncomment below code if you want to use SMTP to send emails. You need to enter your correct SMTP credentials
  /*
  $contact->smtp = array(
    'host' => 'example.com',
    'username' => 'example',
    'password' => 'pass',
    'port' => '587'
  );
  */

  $contact->add_message($_POST['name'], 'From');
  $contact->add_message($_POST['email'], 'Email');
  $contact->add_message($_POST['message'], 'Message', 10);

  echo $contact->send();
}
