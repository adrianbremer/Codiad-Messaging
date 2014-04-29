/*
*  Copyright (c) Codiad & RustyGumbo, distributed
*  as-is and without warranty under the MIT License. See
*  [root]/license.txt for more. This information must remain intact.
*/

(function(global, $){
    //Define core variables.
    var codiad = global.codiad,
        scripts = document.getElementsByTagName('script'),
        path = scripts[scripts.length-1].src.split('?')[0],
        curpath = path.split('/').slice(0, -1).join('/')+'/';

    //Instantiate the plugin.
    $(function() {
        codiad.Messaging.init();
    });

    //Declare the plugin properties and methods.
    codiad.Messaging = {
        //Controller path.
        controller: curpath + 'controller.php',
        
        //Dialog path.
        dialog: curpath + 'dialog.php',
        
        //Message poll interval
        interval: 3000,
        
        //Initialization function.
        init: function() {
            var _this = this;

            //Timer to check for messages.
            setInterval(function() {
                _this.checkNew();
            }, _this.interval);
        },
        
        //Show the form to create a new message.
        create: function() {
            var _this = this;

            //Show the modal form.
            codiad.modal.load(300, this.dialog + '?action=create');
            
            //Hook the submit event.
            $('#modal-content form').live('submit', function(e) {
                e.preventDefault();
                var /* Boolean */ is_valid = true;
                var /* String */ recipient = $(this).find('select[name="lst_recipient"]').val();
                var /* String */ message = $(this).find('textarea[name="txt_message"]').val();
                
                //Check for recipient selection.
                if(recipient.length === 0) {
                    codiad.message.error("Error: A recipient must be selected.");
                    is_valid = false;
                }
                
                // Check for empty message.
                if (message.trim().length === 0) {
                    codiad.message.error("Error: Message can't be empty.");
                    is_valid = false;
                }
                
                if (is_valid) {
                    //Send the message and close the modal form.
                    _this.send(recipient, message);
                    codiad.modal.unload();
                }
            });
        },
        
        //Get the count of users registered on the file.
        send: function(recipient, message) {
            var _this = this;
            
            $.get(
                _this.controller + "?action=send&recipient=" + recipient + "&message=" + message,
                function(data) {
                    var /* Object */ responseData = codiad.jsend.parse(data);

                    //Empty response data means success.
                    if(!responseData) {
                        codiad.message.success("Your message has been sent.");
                    }
                }
            );
        },

        //Check for a new message.
        checkNew: function() {
            var _this = this;

            $.get(
                _this.controller + "?action=checknew",
                function(data) {
                    var /* Object */ responseData = codiad.jsend.parse(data);

                    if(responseData) {
                        //Show the new message.
                        codiad.message.notice(responseData.sender + ": " + responseData.message);
                    }
                }
            );
        }
    };
})(this, jQuery);
