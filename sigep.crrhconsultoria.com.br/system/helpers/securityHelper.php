<?php

class securityHelper {

    function antiInjection($str) {
//        $str = preg_replace(sql_regcase("/(\n|\r|%0a|%0d|Content-Type:|bcc:|to:|cc:|Autoreply:|from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\|')/"), "", $str);
        $str = $this->retirarSql($str);
//        $str = $this->antiInjection($str);
        $str = trim($str); # Remove espaços vazios.
        $str = strip_tags($str); # Remove tags HTML e PHP.
        $str = addslashes($str); # Adiciona barras invertidas à uma string.

        return $str;
    }

    private function anti_sql_injection($str) {
        if (!is_numeric($str)) {
            $str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
            $str = function_exists('mysql_real_escape_string') ? mysql_real_escape_string($str) : mysql_escape_string($str);
        }
        return $str;
    }

    private function retirarSql($string) {
        $badsql = "from|select|insert|delete|where|truncate table|drop table|show tables|#|*|--";
        $array = explode("|", $badsql);
        foreach ($array as $sql) {
            $string = str_replace($sql, "", $string);
        }
        return $string;
    }

    function Codifica($valor) {
        $ant = rand(100000000, 900000000);
        $dep = rand(100000000, 900000000);

        return base64_encode($ant . $valor . $dep);
    }

    function Descodifica($valor) {
        $valor = base64_decode($valor);
        $tam = strlen($valor);

        $v = "";

        for ($i = 9; $i <= $tam; $i++) {
            $v = $v . $valor[$i];
        }

        $tam = strlen($v) - 10;

        $valor = "";
        for ($i = 0; $i <= $tam; $i++) {
            $valor = $valor . $v[$i];
        }

        return antiInjection($valor);
    }

}
