<?php
    require_once("shared.php");

    /****************************************************************/
    /* User Section in top-bar */
    /****************************************************************/

    function f_userHeaderDisplay()
    {
        if(isUserLoggedIn()) {
            global $loggedInUser;
            $name = $loggedInUser->displayname;

            echo "
            <ul class='right' id='top_bar_right'>" .
                f_user_logged_in_display($name) .
            "</ul>";
        }
        else {
            echo "
            <ul class='right' id='top_bar_right'>" .
                f_user_login_form("<a href='#'>.</a>") .
            "</ul>";
        }
    }

    function f_user_logged_in_display($name)
    {
        $tooltip_account  = 'View information about your user account such as allotted '.
            'permissions and registration details';
        $tooltip_settings = 'View and change your email and/or password';
        $tooltip_logout   = 'Logout of the MAP system. You will still be able to access read-only information.';
        return "<li class='has-dropdown'>
                    <a href='#'>$name</a>
                    <ul class='dropdown' id='user_dropdown'>
                        <li><a href='uc_account.php' title='$tooltip_account'>My Account</a></li>
                        <li><a href='uc_user_settings.php' title='$tooltip_settings'>My Settings</a></li>
                        <li><a href='uc_logout.php' title='$tooltip_logout'>Logout</a></li>
                    </ul>
                </li>";
    }

    function f_user_login_form($error_contents)
    {
        return "
        <li class='has-form' id='user_login_form'>
            <form data-abide>
                <div class='row collapse'>

                    <div class='small-1 columns top_bar_error' id='id_top_bar_error'>$error_contents</div>
                    <div class='small-2 columns large-offset-3 medium-offset-2 small-offset-1' id='top_bar_username_div'>
                        <input id='data-ise-username' type='text' placeholder='username' required>
                        <small class='error'>Required</small>
                    </div>
                    <div class='small-2 columns' id='top_bar_password_div'>
                        <input id='data-ise-password' type='password' placeholder='password'
                        onkeydown='if (event.keyCode == 13) submit_user_login(this, event)' required>
                        <small class='error'>Required</small>
                    </div>
                    <div class='small-2 columns' id='top_bar_login_div'>
                        <a href='#' class='button tiny' id='login_button'>Login</a>
                    </div>
                    <div class='small-2 columns end' id='top_bar_register_div'>
                        <a href='uc_register.php' class='button tiny' id='login_button'>Register</a>
                    </div>

                </div>
            </form>
        </li>";
    }
?>
