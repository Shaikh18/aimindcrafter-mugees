<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Email;

class EmailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vendors = [
            ['id' => 1, 'name' => 'Offline Payment Due', 'subject' => 'Bank Transfer Payment Pending', 'message' => '<div>Thank you for subscribing to our platform via Bank Transfer payment option.</div><div><br></div><div>Please complete your subscription by transferring required funds to:</div><div><br></div><div>Bank Name:&nbsp;</div><div>Account Name:</div><div>Account Number/IBAN:</div><div>BIC/Swift:</div><div>Routing Number:&nbsp;</div>', 'footer' => '<div><div>With regards,</div><div><span style="font-weight: bolder;">DaVinci AI Team</span></div></div>', 'type' => 'system'],
            ['id' => 2, 'name' => 'Payment Success', 'subject' => 'Payment Successfully Processed', 'message' => 'Thank you for your subscription.&nbsp;<div><br></div><div>Your payment has been successfully processed and credits are already added to your account.&nbsp;</div>', 'footer' => '<div><div>With regards,</div><div><span style="font-weight: bolder;">DaVinci AI Team</span></div></div>', 'type' => 'system'],
            ['id' => 3, 'name' => 'Email Verification', 'subject' => 'Email Verification Code', 'message' => '<div>Thank you for registering with DaVinci AI.</div><div><br></div><div>Please find below your email verification code:&nbsp;</div>', 'footer' => '<div>With Regards,</div><div><b>DaVinci AI Team</b></div>', 'type' => 'system'],
            ['id' => 4, 'name' => 'Contact Us', 'subject' => 'Contact Us request submitted', 'message' => '<div>Thank you for contacting us.&nbsp;</div><div><br></div><div>Your email request has been recorded and we will get back to you shortly.&nbsp;</div><div><br></div><div>Have a nice day!</div>', 'footer' => '<div><div><br></div><div>With regards,</div><div><span style="font-weight: bolder;">DaVinci AI Team</span></div></div>', 'type' => 'system'],
            ['id' => 5, 'name' => 'Welcome Message', 'subject' => 'Welcome to DaVinci AI', 'message' => '<div style="text-align: center;">Welcome to <b>DaVinci AI</b>!</div><div style="text-align: center;"><br></div><div style="text-align: center;">We are happy to onboard you on our AI platform.</div><div style="text-align: center;"><br></div><div style="text-align: center;">Your account is fully activated now.</div><div style="text-align: center;"><br></div><div style="text-align: center;"><br></div><div style="text-align: center;"><br></div><div style="text-align: center;"><br></div>', 'footer' => '<div>Enjoy!,</div><div><br></div><div>With regards,</div><div><b>DaVinci AI Team</b></div>', 'type' => 'system'],
            ['id' => 6, 'name' => 'Referral Invite', 'subject' => 'You have been referred to join us', 'message' => '<div>Hey there,</div><div><br></div><div>I would like to refer you to DaVinci AI portal.&nbsp;</div><div><br></div><div>They have pretty amazing AI solutions that you can start using within seconds.&nbsp;</div><div><br></div><div>You can register by using my referral link below:</div>', 'footer' => '', 'type' => 'system'],
            ['id' => 7, 'name' => 'Team Member Invite', 'subject' => 'Invitation to my AI portal', 'message' => '<div>I would like to invite you to join my team at DaVinci AI.&nbsp;</div><div><br></div><div>Use the following link below to register.</div>', 'footer' => '', 'type' => 'system'],
            ['id' => 8, 'name' => 'Support Ticket Creation', 'subject' => 'Support ticket has been created successfully', 'message' => '<div>Thank you for creating a support ticket.&nbsp;</div><div><br></div><div>Your request has been recorded successfully and one of our team members will provide a response soon.&nbsp;</div>', 'footer' => '<div>With Regards,<br></div><div><b>DaVinci AI Team</b></div>', 'type' => 'system'],
            ['id' => 9, 'name' => 'Support Ticket Response', 'subject' => 'Your suport ticket has been updated', 'message' => '<div>Your support ticket receive a response from our support team.&nbsp;</div><div><br></div><div>Please login to your DaVinci AI account for further details.&nbsp;</div>', 'footer' => '<div><div>With regards,</div><div><span style="font-weight: bolder;">DaVinci AI Team</span></div></div>', 'type' => 'system'],
        ];

        foreach ($vendors as $vendor) {
            Email::updateOrCreate(['id' => $vendor['id']], $vendor);
        }
    }
}
