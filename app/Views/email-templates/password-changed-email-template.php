<p>Dear <b><?= $mail_data['user']->name ?></b></p>
<br>
<p>
    Your password on Online Voting System has been changed successfully. Here are your new login credentials:
    <br><br>
    <b>Login ID: </b><?= $mail_data['user']->username ?> or <?= $mail_data['user']->email ?>
    <br>
    <b>Password: </b><?= $mail_data['new_password'] ?>
</p>
<br><br>
Please keep your credentials confidential. Your usernmae and password are your own credentials and you should never share them with anybody else.
<p>
    Online Voting will not be liable for any misuse of your username or password.
</p>
<br>
---------------------------------------------------------------
<p>
    This email was automatically sent by Online Voting System. Do not reply it.
</p>