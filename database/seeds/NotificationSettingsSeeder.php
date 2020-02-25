<?php

use Illuminate\Database\Seeder;

class NotificationSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Backpack\Settings\app\Models\Setting::where('id', '>', 0)->delete();
        $field = '{"name":"value","label":"Value","type":"select_from_array","options":{"0":"Disabled","1":"Enabled"}}';
        $notifyOwnerEmail = new \Backpack\Settings\app\Models\Setting;
        $notifyOwnerEmail->key = 'notify_owner_email';
        $notifyOwnerEmail->name = 'Notify Owner By Mail';
        $notifyOwnerEmail->description = 'Whether to notify owner by mail on new payment or not.';
        $notifyOwnerEmail->value = 0;
        $notifyOwnerEmail->field = $field;
        $notifyOwnerEmail->active = 1;
        $notifyOwnerEmail->save();

        $notifyOwnerPhone = new \Backpack\Settings\app\Models\Setting;
        $notifyOwnerPhone->key = 'notify_owner_phone';
        $notifyOwnerPhone->name = 'Notify Owner By Phone';
        $notifyOwnerPhone->description = 'Whether to notify owner by phone on new payment or not.';
        $notifyOwnerPhone->value = 0;
        $notifyOwnerPhone->field = $field;
        $notifyOwnerPhone->active = 1;
        $notifyOwnerPhone->save();

        $notifyDepositPhone = new \Backpack\Settings\app\Models\Setting;
        $notifyDepositPhone->key = 'notify_user_deposit_phone';
        $notifyDepositPhone->name = 'Notify User By Phone On Wallet Deposit';
        $notifyDepositPhone->description = 'Whether to notify user when money are successfully deposited on wallet.';
        $notifyDepositPhone->value = 0;
        $notifyDepositPhone->field = $field;
        $notifyDepositPhone->active = 1;
        $notifyDepositPhone->save();

        $notifyDepositEmail = new \Backpack\Settings\app\Models\Setting;
        $notifyDepositEmail->key = 'notify_user_deposit_email';
        $notifyDepositEmail->name = 'Notify User By Email On Wallet Deposit';
        $notifyDepositEmail->description = 'Whether to notify user when money are successfully deposited on wallet.';
        $notifyDepositEmail->value = 0;
        $notifyDepositEmail->field = $field;
        $notifyDepositEmail->active = 1;
        $notifyDepositEmail->save();



        $notifySessionPhone = new \Backpack\Settings\app\Models\Setting;
        $notifySessionPhone->key = 'notify_user_session_phone';
        $notifySessionPhone->name = 'Notify User By Phone On Session Finish';
        $notifySessionPhone->description = 'Whether to notify user when session is finished.';
        $notifySessionPhone->value = 0;
        $notifySessionPhone->field = $field;
        $notifySessionPhone->active = 1;
        $notifySessionPhone->save();

        $notifySessionEmail = new \Backpack\Settings\app\Models\Setting;
        $notifySessionEmail->key = 'notify_user_session_email';
        $notifySessionEmail->name = 'Notify User By Email On Session Finish';
        $notifySessionEmail->description = 'Whether to notify user when session is finished.';
        $notifySessionEmail->value = 0;
        $notifySessionEmail->field = $field;
        $notifySessionEmail->active = 1;
        $notifySessionEmail->save();
    }
}
