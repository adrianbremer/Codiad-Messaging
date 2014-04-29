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
    <label><span class="icon-message"></span>Message</label>
    <textarea type="text" name="txt_message" autofocus="autofocus" autocomplete="off"></textarea>
    <button class="btn-left">Send</button>
    <button class="btn-right" onclick="codiad.modal.unload(); return false;">Cancel</button>
<?php
            break;
    }
?>
</form>