<?php
    function generateCode(int $size, string $chars):string {
        $res = "";
        for ($i = 0; $i < $size; $i++) {
            $res .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $res;
    }

    if (isset($_POST['generateCode'])) {
        $res = generateCode($_POST['sizeCode'], $_POST['charsCodeGenerator']);
        echo "res = " . $res . "<br>";
    }
?>