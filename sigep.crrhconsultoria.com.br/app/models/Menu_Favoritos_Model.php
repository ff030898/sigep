<?php

class Menu_Favoritos_Model extends Model {

    public $_tabela = "menu_favoritos";

    public function checkFavoritos($id_menu, $id_user) {
        $result = $this->read("menu = '{$id_menu}' AND usuario = '{$id_user}'");
        if (count($result)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function controleFavoritos($id_menu, $tipo) {
        $id_user = $_SESSION["user_id"];
        if ($this->checkFavoritos($id_menu, $id_user)) {
            $this->delete("menu = '{$id_menu}' AND usuario = '{$id_user}'");
        } else {
            $this->insert(array(
                "menu" => $id_menu,
                "usuario" => $id_user,
                "tipo" => $tipo
            ));
        }
    }

    public function montaPainelFavoritos() {
        $id_user = $_SESSION["user_id"];
        $t_tarefas = 0;
        $t_relatorios = 0;
        $t_consultas = 0;
        $t_cadastros = 0;

        $tarefas = NULL;
        $relatorios = NULL;
        $consultas = NULL;
        $cadastros = NULL;

        $result = $this->query("SELECT * FROM menu_favoritos INNER JOIN menu ON (menu.code = menu_favoritos.menu) WHERE menu_favoritos.tipo = '1' AND usuario = '$id_user' ORDER BY menu.name ASC");
        $t_tarefas = count($result);
        if ($t_tarefas) {
            foreach ($result as $key => $value) {
                $tarefas .= '
                            <tr>
                                <td><a href="' . $value["link"] . '">' . $value["name"] . ' - ' . $value["code"] . '</a></td>';
                $id_menu = $value["code"];
                if ($this->checkFavoritos($id_menu, $id_user)) {
                    $tarefas .= '<td><a href="/favoritos/verifica/menu/' . $value["code"] . '/tipo/1"><span class="fa fa-star" aria-hidden="true"></span></a></td>';
                } else {
                    $tarefas .= '<td><a href="/favoritos/verifica/menu/' . $value["code"] . '/tipo/1"><span class="fa fa-star-o" aria-hidden="true"></span></a></td>';
                }
                $tarefas .= '</tr>';
            }
        }

        $result = $this->query("SELECT * FROM menu_favoritos INNER JOIN menu ON (menu.code = menu_favoritos.menu) WHERE menu_favoritos.tipo = '2' AND usuario = '$id_user' ORDER BY menu.name ASC");
        $t_relatorios = count($result);
        if ($t_relatorios) {
            foreach ($result as $key => $value) {
                $relatorios .= '
                            <tr>
                                <td><a href="' . $value["link"] . '">' . $value["name"] . ' - ' . $value["code"] . '</a></td>';
                $id_menu = $value["code"];
                if ($this->checkFavoritos($id_menu, $id_user)) {
                    $relatorios .= '<td><a href="/favoritos/verifica/menu/' . $value["code"] . '/tipo/1"><span class="fa fa-star" aria-hidden="true"></span></a></td>';
                } else {
                    $relatorios .= '<td><a href="/favoritos/verifica/menu/' . $value["code"] . '/tipo/1"><span class="fa fa-star-o" aria-hidden="true"></span></a></td>';
                }
                $relatorios .= '</tr>';
            }
        }


        $result = $this->query("SELECT * FROM menu_favoritos INNER JOIN menu ON (menu.code = menu_favoritos.menu) WHERE menu_favoritos.tipo = '3' AND usuario = '$id_user' ORDER BY menu.name ASC");
        $t_consultas = count($result);
        if ($t_consultas) {
            foreach ($result as $key => $value) {
                $consultas .= '
                            <tr>
                                <td><a href="' . $value["link"] . '">' . $value["name"] . ' - ' . $value["code"] . '</a></td>';
                $id_menu = $value["code"];
                if ($this->checkFavoritos($id_menu, $id_user)) {
                    $consultas .= '<td><a href="/favoritos/verifica/menu/' . $value["code"] . '/tipo/1"><span class="fa fa-star" aria-hidden="true"></span></a></td>';
                } else {
                    $consultas .= '<td><a href="/favoritos/verifica/menu/' . $value["code"] . '/tipo/1"><span class="fa fa-star-o" aria-hidden="true"></span></a></td>';
                }
                $consultas .= '</tr>';
            }
        }



        $result = $this->query("SELECT * FROM menu_favoritos INNER JOIN menu ON (menu.code = menu_favoritos.menu) WHERE menu_favoritos.tipo = '4' AND usuario = '$id_user' ORDER BY menu.name ASC");
        $t_cadastros = count($result);
        if ($t_cadastros) {
            foreach ($result as $key => $value) {
                $cadastros .= '
                            <tr>
                                <td><a href="' . $value["link"] . '">' . $value["name"] . ' - ' . $value["code"] . '</a></td>';
                $id_menu = $value["code"];
                if ($this->checkFavoritos($id_menu, $id_user)) {
                    $cadastros .= '<td><a href="/favoritos/verifica/menu/' . $value["code"] . '/tipo/1"><span class="fa fa-star" aria-hidden="true"></span></a></td>';
                } else {
                    $cadastros .= '<td><a href="/favoritos/verifica/menu/' . $value["code"] . '/tipo/1"><span class="fa fa-star-o" aria-hidden="true"></span></a></td>';
                }
                $cadastros .= '</tr>';
            }
        }




        $mm = array(
            "t_tarefas" => $t_tarefas,
            "tarefas" => $tarefas,
            "t_relatorios" => $t_relatorios,
            "relatorios" => $relatorios,
            "t_consultas" => $t_consultas,
            "consultas" => $consultas,
            "t_cadastros" => $t_cadastros,
            "cadastros" => $cadastros
        );

        return $mm;
    }

}
