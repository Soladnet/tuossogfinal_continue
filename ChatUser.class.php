<?php

class ChatUser extends ChatBase {

    protected $user_id = '', $com_id = '';

    public function save() {

        DB::query("
			INSERT INTO community_chat_online (com_id,user_id)
			VALUES (
				'" . DB::esc($this->user_id) . "',
				'" . DB::esc($this->com_id) . "'
		)");

        return DB::getMySQLiObject();
    }

    public function update() {
        DB::query("
			INSERT INTO community_chat_online (com_id,user_id)
			VALUES (
				'" . DB::esc($this->user_id) . "',
				'" . DB::esc($this->com_id) . "'
			) ON DUPLICATE KEY UPDATE last_activity = NOW()");
    }

}

?>