<?php

?>
<form method="$kw['contactMe-method']" class="standarExtension-formContactMe">
    <h1>Contact Me</h1>
    <div class="standarExtension-whiteSpacing">
        <input type="email" placeholder="Yout email..." required name="emailToContactMe">
        <input type="text" placeholder="Your message..." required name="messageToContactME">
        <input type="submit" value="Send" name="sendContactMe-$kw['contactMe-nameSender']">
    </div>
    <p>You can contact me by complete this formular. Your message will be send in my email.</p>
</form>
