<?php
    
    /*
    *  Copyright (c) Codiad & RustyGumbo, distributed
    *  as-is and without warranty under the MIT License. See
    *  [root]/license.txt for more. This information must remain intact.
    */
    
    require_once('../../common.php');
    require_once('class.message.php');

    /* Object */ $Message = new Message();

    //////////////////////////////////////////////////////////////////
    // Verify Session or Key
    //////////////////////////////////////////////////////////////////
    
    checkSession();
?>
<form>
<?php
    switch($_GET['action']){
    
        //////////////////////////////////////////////////////////////////
        // Create
        //////////////////////////////////////////////////////////////////
        case 'create':
            $users = $Message->GetOtherUsers();
?>
    <label>Recipient</label>
    <select name="lst_recipient">
        <option value="">Select a recipient...</option>
        <?php foreach($users as $user): ?>
        <option value="<?php echo $user['username']; ?>"><?php echo $user['username']; ?></option>
        <?php endforeach; ?>
    </select>
    <input type="text" name="txt_message" autofocus="autofocus" autocomplete="off" placeholder="Write a message..." />
    <button class="btn-left">Send</button>
    <button class="btn-right" onclick="codiad.modal.unload(); return false;">Cancel</button>
<?php
            break;
    
        //////////////////////////////////////////////////////////////////
        // History
        //////////////////////////////////////////////////////////////////
        case 'history':
            $Message->sender = $_GET['sender'];
            $Message->recipient = $_SESSION['user'];
            $messages = $Message->GetHistory();
?>
    <label>Chat with <?php echo $_GET['sender']; ?></label>
    <div id="messaging-history-<?php echo $_GET['sender']; ?>" class="messaging-history">
<?php
            foreach($messages as $message) {
                echo $message->get_field('date') . ": " . $message->get_field('message') . "<br />";
            }
?>
    </div>
    <input type="text" name="txt_message" autofocus="autofocus" autocomplete="off" placeholder="Write a message..." />
    <input type="hidden" name="hdn_recipient" value="<?php echo $_GET['sender']; ?>" />
    <button class="btn-left">Send</button>
    <button class="btn-right" onclick="codiad.modal.unload(); return false;">Cancel</button>
    <script>$('.messaging-history').scrollTop($('.messaging-history')[0].scrollHeight);</script>
<?php
            break;
    }
?>
</form>