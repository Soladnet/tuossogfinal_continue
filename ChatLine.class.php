<?php

/* Chat line is used for the chat entries */

class ChatLine extends ChatBase {

    protected $text = '', $author = '', $gravatar = '';

    public function save() {
        DB::query("
			INSERT INTO community_chat (com_id,user_id,text)
			VALUES (
				'" . DB::esc($this->author) . "',
				'" . DB::esc($this->gravatar) . "',
				'" . DB::esc($this->text) . "'
		)");

        // Returns the MySQLi object of the DB class

        return DB::getMySQLiObject();
    }

}

?>