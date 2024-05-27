<?php

class Clients_Menu_Model extends Model {

    public $_tabela = "clients_menu";
    private $_menu = NULL;

    private function createTreeView($id_user, $array, $currentParent, $currLevel = 0, $prevLevel = -1) {
        $permission = new Clients_Menu_User_Model();
        foreach ($array as $categoryId => $category) {
            if ($currentParent == $category['parent_id']) {
                if ($currLevel > $prevLevel)
                    $this->_menu .= ' <ul class="teste"> ';

                if ($currLevel == $prevLevel)
                    $this->_menu .= " </li> ";

                if ($permission->checkPermission($categoryId, $id_user)) {
                    $this->_menu .= '<li class="collapsed"> <input type="checkbox" checked id="chkMenu' . $categoryId . '" name="chkMenu' . $categoryId . '"/> <span>' . $category['name'] . '</span> ';
                } else {
                    $this->_menu .= '<li class="collapsed"> <input type="checkbox" id="chkMenu' . $categoryId . '" name="chkMenu' . $categoryId . '"/> <span>' . $category['name'] . '</span> ';
                }


                if ($currLevel > $prevLevel) {
                    $prevLevel = $currLevel;
                }
                $currLevel++;
                $this->createTreeView($id_user, $array, $categoryId, $currLevel, $prevLevel);
                $currLevel--;
            }
        }

        if ($currLevel == $prevLevel)
            $this->_menu .= " </li>  </ul> ";
    }

    private function createTreeView2($array, $currentParent, $currLevel = 0, $prevLevel = -1) {
        foreach ($array as $categoryId => $category) {
            if ($currentParent == $category['parent_id']) {
                if ($currLevel > $prevLevel)
                    $this->_menu .= ' <ul class="teste"> ';

                if ($currLevel == $prevLevel)
                    $this->_menu .= " </li> ";

                $this->_menu .= '<li class=""> <a href="javascript:edita(' . $categoryId . ');">'
                        . '<a href="javascript:edita(' . $categoryId . ');"><i class="fa fa-pencil-square-o"></i></a>&nbsp;<a href="javascript:remove(' . $categoryId . ');"><i class="fa fa-remove"></i></a>&nbsp;<span>' . $category['ordem'] . "-" . $category['name'] . '</span> ';

                if ($currLevel > $prevLevel) {
                    $prevLevel = $currLevel;
                }
                $currLevel++;
                $this->createTreeView2($array, $categoryId, $currLevel, $prevLevel);
                $currLevel--;
            }
        }

        if ($currLevel == $prevLevel)
            $this->_menu .= " </li>  </ul> ";
    }

    private function createTreeView3($array, $currentParent, $currLevel = 0, $prevLevel = -1) {
        foreach ($array as $categoryId => $category) {
            if ($currentParent == $category['parent_id']) {
                if ($currLevel > $prevLevel)
                    $this->_menu .= ' <ul class="teste"> ';

                if ($currLevel == $prevLevel)
                    $this->_menu .= " </li> ";

                $this->_menu .= '<li class=""> <input type="radio" required name="radRoot" id="radRoot" value="' . $categoryId . '"><span>' . $category['ordem'] . "-" . $category['name'] . '</span> ';

                if ($currLevel > $prevLevel) {
                    $prevLevel = $currLevel;
                }
                $currLevel++;
                $this->createTreeView3($array, $categoryId, $currLevel, $prevLevel);
                $currLevel--;
            }
        }

        if ($currLevel == $prevLevel)
            $this->_menu .= " </li>  </ul> ";
    }

    private function createMenuView_($id_user, $array, $currentParent, $currLevel = 0, $prevLevel = -1) {
        $permission = new Menu_User_Model();
        foreach ($array as $categoryId => $category) {
            if ($currentParent == $category['parent_id']) {
                if ($prevLevel == -1) {
//                    $this->_menu .= ' <ul class="nav" id="side-menu"> <li>
//                                <a href="/favoritos"><i class="fa fa-star"></i> Favoritos</a>
//                            </li>';
                    $this->_menu .= ' <ul class="nav" id="side-menu">';
                } else if ($currLevel > $prevLevel) {
                    $this->_menu .= ' <ul class = "nav nav-second-level"> ';
                }

                if ($currLevel == $prevLevel)
                    $this->_menu .= " </li> ";

                if ($permission->checkPermission($categoryId, $id_user)) {

                    if ($category["tipo"] == 0 AND $category["parent_id"] == 0) {
                        $this->_menu .= '<li> <a href = "' . $category["link"] . '" target="' . $category["target"] . '"><i class = "' . $category["icone"] . ' fa-fw"></i> ' . $category['name'] . '<span class = "fa arrow"></span></a>';
                    } else {
                        $this->_menu .= '<li> <a href = "' . $category["link"] . '" target="' . $category["target"] . '">' . $category['name'] . '</a>';
                    }
                }


                if ($currLevel > $prevLevel) {
                    $prevLevel = $currLevel;
                }
                $currLevel++;
                $this->createMenuView($id_user, $array, $categoryId, $currLevel, $prevLevel);
                $currLevel--;
            }
        }

        if ($currLevel == $prevLevel)
            $this->_menu .= " </li>  </ul> ";
    }

    private function createMenuView($id_user, $array, $currentParent, $currLevel = 0, $prevLevel = -1) {
        $permission = new Menu_User_Model();
        foreach ($array as $categoryId => $category) {
            if ($currentParent == $category['parent_id']) {
                if ($prevLevel == -1) {
//                    $this->_menu .= ' <ul class="nav" id="side-menu"> <li>
//                                <a href="/favoritos"><i class="fa fa-star"></i> Favoritos</a>
//                            </li>';
                    $this->_menu .= '
                            <ul class="nav" id="side-menu">';
                } else if ($currLevel > $prevLevel) {
                    $this->_menu .= '
                            <ul class = "nav nav-second-level">';
                }

                if ($currLevel == $prevLevel)
                    $this->_menu .= "
                                </li>
                    ";

                if ($permission->checkPermission($categoryId, $id_user)) {

                    if ($category["tipo"] == 0 AND $category["parent_id"] == 0) {
                        $this->_menu .= '
                                <li id="menu_' . $category["code"] . '">
                                    <a href = "' . $category["link"] . '" target="' . $category["target"] . '">
                                        <i class = "' . $category["icone"] . ' fa-fw"></i> ' . $category['name'] . '
                                        <span class = "fa arrow"></span>
                                    </a>';
                    } else {
                        $this->_menu .= '
                                <li>
                                    <a href = "' . $category["link"] . '" target="' . $category["target"] . '">' . $category['name'] . '</a>';
                    }
                }


                if ($currLevel > $prevLevel) {
                    $prevLevel = $currLevel;
                }
                $currLevel++;
                $this->createMenuView($id_user, $array, $categoryId, $currLevel, $prevLevel);
                $currLevel--;
            }
        }

        if ($currLevel == $prevLevel)
            $this->_menu .= "
                                </li>
                            </ul> ";
    }

    public function estruturaMenu($id_user) {
        $menu_esq = $this->read(NULL, NULL, NULL, "ordem ASC, name ASC");
        $arrayCategories = array();
        foreach ($menu_esq as $key => $value) {
            $arrayCategories[$value['code']] = array("parent_id" => $value['root'], "name" => $value['name'], "target" => $value['target']);
        }

        $this->createTreeView($id_user, $arrayCategories, 0);

        return $this->_menu;
    }

    public function estruturaMenu2() {
        $menu_esq = $this->read(NULL, NULL, NULL, "ordem ASC, name ASC");
        $arrayCategories = array();
        foreach ($menu_esq as $key => $value) {
            $arrayCategories[$value['code']] = array("parent_id" => $value['root'], "name" => $value['name'], "target" => $value['target'], "ordem" => $value['ordem']);
        }

        $this->createTreeView2($arrayCategories, 0);

        return $this->_menu;
    }

    public function estruturaMenu3() {
        $menu_esq = $this->read(NULL, NULL, NULL, "ordem ASC, name ASC");
        $arrayCategories = array();
        foreach ($menu_esq as $key => $value) {
            $arrayCategories[$value['code']] = array("parent_id" => $value['root'], "name" => $value['name'], "target" => $value['target'], "ordem" => $value['ordem']);
        }

        $this->createTreeView3($arrayCategories, 0);

        return $this->_menu;
    }

    public function montaMenu($id_user) {
        $id_user = $_SESSION["user_id"];
        $menu_esq = $this->read("tipo = '0'", NULL, NULL, "ordem ASC, name ASC");
        $arrayCategories = array();
        foreach ($menu_esq as $key => $value) {
            $arrayCategories[$value['code']] = array("code" => $value["code"], "parent_id" => $value['root'], "name" => $value['name'], "tipo" => $value["tipo"], "link" => $value["link"], "icone" => $value["icone"], "target" => $value["target"]);
        }

        $this->createMenuView($id_user, $arrayCategories, 0);

        return $this->_menu;
    }

    private function getSubMenuId($menu) {
        $mm = $this->read("nome_interno = '{$menu}'", NULL, NULL, NULL, "code");
        $menu_id = $mm[0]["code"];
        return $this->read("root = '{$menu_id}'", NULL, NULL, "tipo ASC", "code, tipo");
    }

    public function montaPainel($menu) {
        $permission = new Menu_User_Model();
        $favoritos = new Menu_Favoritos_Model();
        $id_user = $_SESSION["user_id"];
        $sub = $this->getSubMenuId($menu);
        $mm = array();
        $t_tarefas = 0;
        $t_relatorios = 0;
        $t_consultas = 0;
        $t_cadastros = 0;

        $tarefas = NULL;
        $relatorios = NULL;
        $consultas = NULL;
        $cadastros = NULL;

        foreach ($sub as $key => $value) {
            $submenu_id = $value["code"];
            if ($value["tipo"] == 1) {
                $result = $this->query("SELECT * FROM menu INNER JOIN menu_user ON (menu_user.menu = menu.code) WHERE menu_user.usuario = '{$id_user}' AND menu.root = '{$submenu_id}'");
                $t_tarefas = count($result);
                if ($t_tarefas >= 1) {
                    foreach ($result as $s_key => $s_value) {
                        $tarefas .= '
                            <tr>
                                <td><a href="' . $s_value["link"] . '">' . $s_value["name"] . '</a></td>';

                        $id_menu = $s_value["code"];
                        if ($favoritos->checkFavoritos($id_menu, $id_user)) {
                            $tarefas .= '<td><a href="/favoritos/verifica/menu/' . $s_value["code"] . '/tipo/1"><span class="fa fa-star" aria-hidden="true"></span></a></td>';
                        } else {
                            $tarefas .= '<td><a href="/favoritos/verifica/menu/' . $s_value["code"] . '/tipo/1"><span class="fa fa-star-o" aria-hidden="true"></span></a></td>';
                        }
                        $tarefas .= '</tr>';
                    }
                }
            } else if ($value["tipo"] == 2) {
                $result = $this->query("SELECT * FROM menu INNER JOIN menu_user ON (menu_user.menu = menu.code) WHERE menu_user.usuario = '{$id_user}' AND menu.root = '{$submenu_id}'");
                $t_relatorios = count($result);
                if ($t_relatorios >= 1) {
                    foreach ($result as $s_key => $s_value) {
                        $relatorios .= '
                            <tr>
                                <td><a href="' . $s_value["link"] . '">' . $s_value["name"] . '</a></td>';

                        $id_menu = $s_value["code"];
                        if ($favoritos->checkFavoritos($id_menu, $id_user)) {
                            $relatorios .= '<td><a href="/favoritos/verifica/menu/' . $s_value["code"] . '/tipo/2"><span class="fa fa-star" aria-hidden="true"></span></a></td>';
                        } else {
                            $relatorios .= '<td><a href="/favoritos/verifica/menu/' . $s_value["code"] . '/tipo/2"><span class="fa fa-star-o" aria-hidden="true"></span></a></td>';
                        }
                        $relatorios .= '</tr>';
                    }
                }
            } else if ($value["tipo"] == 3) {
                $result = $this->query("SELECT * FROM menu INNER JOIN menu_user ON (menu_user.menu = menu.code) WHERE menu_user.usuario = '{$id_user}' AND menu.root = '{$submenu_id}'");
                $t_consultas = count($result);
                if ($t_consultas >= 1) {
                    foreach ($result as $s_key => $s_value) {
                        $consultas .= '
                            <tr>
                                <td><a href="' . $s_value["link"] . '">' . $s_value["name"] . '</a></td>';

                        $id_menu = $s_value["code"];
                        if ($favoritos->checkFavoritos($id_menu, $id_user)) {
                            $consultas .= '<td><a href="/favoritos/verifica/menu/' . $s_value["code"] . '/tipo/3"><span class="fa fa-star" aria-hidden="true"></span></a></td>';
                        } else {
                            $consultas .= '<td><a href="/favoritos/verifica/menu/' . $s_value["code"] . '/tipo/3"><span class="fa fa-star-o" aria-hidden="true"></span></a></td>';
                        }
                        $consultas .= '</tr>';
                    }
                }
            } else if ($value["tipo"] == 4) {
                $result = $this->query("SELECT code, name, link FROM menu INNER JOIN menu_user ON (menu_user.menu = menu.code) WHERE menu_user.usuario = '{$id_user}' AND menu.root = '{$submenu_id}' ");
                $t_cadastros = count($result);
                if ($t_cadastros >= 1) {
                    foreach ($result as $s_key => $s_value) {
                        $cadastros .= '
                            <tr>
                                <td><a href="' . $s_value["link"] . '">' . $s_value["name"] . '</a></td>';

                        $id_menu = $s_value["code"];
                        if ($favoritos->checkFavoritos($id_menu, $id_user)) {
                            $cadastros .= '<td><a href="/favoritos/verifica/menu/' . $s_value["code"] . '/tipo/4"><span class="fa fa-star" aria-hidden="true"></span></a></td>';
                        } else {
                            $cadastros .= '<td><a href="/favoritos/verifica/menu/' . $s_value["code"] . '/tipo/4"><span class="fa fa-star-o" aria-hidden="true"></span></a></td>';
                        }
                        $cadastros .= '</tr>';
                    }
                }
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

    public function segurancaPrograma($programa) {

        if (isset($_SESSION["user_id"])) {
            $id_user = $_SESSION["user_id"];
        }

        $ver_menu = $this->read("nome_interno = '$programa'");
        if (count($ver_menu)) {
            $result = $this->query("SELECT * FROM menu INNER JOIN menu_user ON (menu_user.menu = menu.code) WHERE (menu.nome_interno = '{$programa}' AND menu_user.usuario = '{$id_user}') OR (menu.nome_interno = 'NULL')");
            if ($result) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return TRUE;
        }
    }

    public function adicionar($name, $link = "", $icone = "", $n_interno = "", $target = "", $root, $ordem = 1) {
        $result = $this->read("name = '{$name}' AND root = '{$root}'");
        if (!count($result)) {
            $saida = $this->insert(array(
                "name" => $name,
                "root" => $root,
                "tipo" => "0",
                "link" => $link,
                "icone" => $icone,
                "nome_interno" => $n_interno,
                "ordem" => $ordem,
                "target" => $target,
                    ), "fsadfs");
            if (is_numeric($saida)) {
                $this->log("Insersão", $name);
                return $saida;
            } else {
                $this->log("Insersão Falha", $name);
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function remove($id) {
        $result = $this->read("code = '{$id}' ");
        $nome = $result[0]["name"];

        $saida = $this->delete("code = '{$id}' ");
        if ($saida) {
            $this->log("Remoção", $nome);
            return $saida;
        } else {
            $this->log("Remoção Falha", $nome);
            return FALSE;
        }
    }

    public function getMenu($code) {
        $return = $this->read("code = '{$code}'");
        if (count($return)) {
            return $return[0];
        } else {
            return FALSE;
        }
    }

    public function editar($code, $name, $link = "", $icone = "", $n_interno = "", $target = "", $root, $ordem = 1) {
        $result = $this->read("name = '{$name}' AND root = '{$root}' AND code != '{$code}'");
        if (!count($result)) {
            $saida = $this->update(array(
                "name" => $name,
                "root" => $root,
                "tipo" => "0",
                "link" => $link,
                "icone" => $icone,
                "nome_interno" => $n_interno,
                "ordem" => $ordem,
                "target" => $target,
                    ), "code = '{$code}'");
            if (is_numeric($saida)) {
                $this->log("Alteração", $name);
                return $saida;
            } else {
                $this->log("Alteração Falha", $name);
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

}
