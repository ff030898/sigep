<?php

class clientes extends Controller {

    public function index_action() {

    }

    public function empresas() {

        $clients = new Clients_Model();
        $dados["clients"] = $clients->getClients();

        $this->view('header');
        $this->view('menu');
        $this->view('content_empresas', $dados);
        $this->view('footer');
    }

    public function add() {
        $tipo_negocio = new Tipo_Negocio_Model();

        $dados["tipo_negocio"] = $tipo_negocio->getTipo_Negocio(1);

        $this->view('header');
        $this->view('menu');
        $this->view('content_empresas_add', $dados);
        $this->view('footer');
    }

    public function edit() {
        $clients = new Clients_Model();
        $security = new securityHelper();
        $tipo_negocio = new Tipo_Negocio_Model();

        $dados["clients"] = $clients->getClient($security->antiInjection(filter_input(INPUT_POST, 'id')));
        $dados["tipo_negocio"] = $tipo_negocio->getTipo_Negocio(1);

        $this->view('header');
        $this->view('menu');
        $this->view('content_empresas_edit', $dados);
        $this->view('footer');
    }

    public function adicionar() {
        $security = new securityHelper();
        $clients = new Clients_Model();

        $id_tipo_negocio = $security->antiInjection(filter_input(INPUT_POST, "tipo_negocio"));
        $name = $security->antiInjection(filter_input(INPUT_POST, "name"));
        $social_razon = $security->antiInjection(filter_input(INPUT_POST, "social_razon"));
        $activity = $security->antiInjection(filter_input(INPUT_POST, "activity"));
        $nature = $security->antiInjection(filter_input(INPUT_POST, "nature"));
        $cnpj = $security->antiInjection(filter_input(INPUT_POST, "cnpj"));
        $cpf = $security->antiInjection(filter_input(INPUT_POST, "cpf"));
        $inss = $security->antiInjection(filter_input(INPUT_POST, "inss"));
        $ie = $security->antiInjection(filter_input(INPUT_POST, "ie"));
        $im = $security->antiInjection(filter_input(INPUT_POST, "im"));
        $cnae = $security->antiInjection(filter_input(INPUT_POST, "cnae"));
        $address = $security->antiInjection(filter_input(INPUT_POST, "address"));
        $district = $security->antiInjection(filter_input(INPUT_POST, "district"));
        $provincy = $security->antiInjection(filter_input(INPUT_POST, "provincy"));
        $pobox = $security->antiInjection(filter_input(INPUT_POST, "pobox"));
        $number = $security->antiInjection(filter_input(INPUT_POST, "number"));
        $city = $security->antiInjection(filter_input(INPUT_POST, "city"));
        $cep = $security->antiInjection(filter_input(INPUT_POST, "cep"));
        $complement = $security->antiInjection(filter_input(INPUT_POST, "complement"));
        $phone1 = $security->antiInjection(filter_input(INPUT_POST, "phone1"));
        $phone2 = $security->antiInjection(filter_input(INPUT_POST, "phone2"));
        $phone3 = $security->antiInjection(filter_input(INPUT_POST, "phone3"));
        $ramal1 = $security->antiInjection(filter_input(INPUT_POST, "ramal1"));
        $ramal2 = $security->antiInjection(filter_input(INPUT_POST, "ramal2"));
        $ramal3 = $security->antiInjection(filter_input(INPUT_POST, "ramal3"));
        $email = $security->antiInjection(filter_input(INPUT_POST, "email"));
        $site = $security->antiInjection(filter_input(INPUT_POST, "site"));
        $observacao = $security->antiInjection(filter_input(INPUT_POST, "observacao"));
        $pt_reference = $security->antiInjection(filter_input(INPUT_POST, "pt_reference"));
        $qtde_funcionarios = $security->antiInjection(filter_input(INPUT_POST, "qtde_funcionarios"));

        print_r($clients->adicionar($id_tipo_negocio, $name, $social_razon, $activity, $nature, $cnpj, $cpf, $inss, $ie, $im, $cnae, $address, $district, $provincy, $pobox, $number, $city, $cep, $complement, $phone1, $phone2, $phone3, $ramal1, $ramal2, $ramal3, $email, $site, $observacao, $pt_reference, $qtde_funcionarios));
    }

    public function editar() {
        $security = new securityHelper();
        $clients = new Clients_Model();

        $id = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $id_tipo_negocio = $security->antiInjection(filter_input(INPUT_POST, "tipo_negocio"));
        $name = $security->antiInjection(filter_input(INPUT_POST, "name"));
        $social_razon = $security->antiInjection(filter_input(INPUT_POST, "social_razon"));
        $activity = $security->antiInjection(filter_input(INPUT_POST, "activity"));
        $nature = $security->antiInjection(filter_input(INPUT_POST, "nature"));
        $cnpj = $security->antiInjection(filter_input(INPUT_POST, "cnpj"));
        $cpf = $security->antiInjection(filter_input(INPUT_POST, "cpf"));
        $inss = $security->antiInjection(filter_input(INPUT_POST, "inss"));
        $ie = $security->antiInjection(filter_input(INPUT_POST, "ie"));
        $im = $security->antiInjection(filter_input(INPUT_POST, "im"));
        $cnae = $security->antiInjection(filter_input(INPUT_POST, "cnae"));
        $address = $security->antiInjection(filter_input(INPUT_POST, "address"));
        $district = $security->antiInjection(filter_input(INPUT_POST, "district"));
        $provincy = $security->antiInjection(filter_input(INPUT_POST, "provincy"));
        $pobox = $security->antiInjection(filter_input(INPUT_POST, "pobox"));
        $number = $security->antiInjection(filter_input(INPUT_POST, "number"));
        $city = $security->antiInjection(filter_input(INPUT_POST, "city"));
        $cep = $security->antiInjection(filter_input(INPUT_POST, "cep"));
        $complement = $security->antiInjection(filter_input(INPUT_POST, "complement"));
        $phone1 = $security->antiInjection(filter_input(INPUT_POST, "phone1"));
        $phone2 = $security->antiInjection(filter_input(INPUT_POST, "phone2"));
        $phone3 = $security->antiInjection(filter_input(INPUT_POST, "phone3"));
        $ramal1 = $security->antiInjection(filter_input(INPUT_POST, "ramal1"));
        $ramal2 = $security->antiInjection(filter_input(INPUT_POST, "ramal2"));
        $ramal3 = $security->antiInjection(filter_input(INPUT_POST, "ramal3"));
        $email = $security->antiInjection(filter_input(INPUT_POST, "email"));
        $site = $security->antiInjection(filter_input(INPUT_POST, "site"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));
        $observacao = $security->antiInjection(filter_input(INPUT_POST, "observacao"));
        $pt_reference = $security->antiInjection(filter_input(INPUT_POST, "pt_reference"));
        $qtde_funcionarios = $security->antiInjection(filter_input(INPUT_POST, "qtde_funcionarios"));

        print_r($clients->edita($id, $id_tipo_negocio, $name, $social_razon, $activity, $nature, $cnpj, $cpf, $inss, $ie, $im, $cnae, $address, $district, $provincy, $pobox, $number, $city, $cep, $complement, $phone1, $phone2, $phone3, $ramal1, $ramal2, $ramal3, $email, $site, $status, $observacao, $pt_reference, $qtde_funcionarios));
    }

    public function remove() {
        $security = new securityHelper();
        $clients = new Clients_Model();

        $id = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($clients->remove($id));
    }

    public function show() {
        $clients = new Clients_Model();
        $security = new securityHelper();
        $os = new OS_Model();
        $boletos = new Boletos_Model();
        $contratos = new Contratos_Model();
        $fotos = new Clientes_Fotos_Model();

        $clienteContrato = new Clientes_Contratos_Model();
        $id = $security->antiInjection(filter_input(INPUT_POST, 'id'));
        $id = 9;
        $dados["clients"] = $clients->getClient($id);
        $dados["os"] = $os->getOsByClient($id);
        $dados["boletos"] = $boletos->getBoletosByClient($id);
        $dados["contrato"] = $contratos->getContratoByTipo($dados["clients"]["nature"]);

        $dados["clients"]["status"] = ($dados["clients"]["status"] ? "ATIVO" : "INATIVO");
        $dados["clients"]["cnpj"] = ($dados["clients"]["nature"] == 1 ? $dados["clients"]["cnpj"] : $dados["clients"]["cpf"]);
        $dados["clients"]["tipo"] = $dados["clients"]["nature"];
        $dados["clients"]["nature"] = ($dados["clients"]["nature"] == 1 ? "PESSOA JURÍDICA" : "PESSOA FÍSICA");

        $dados["contratos_cliente"] = $clienteContrato->getClienteContratoID($dados["clients"]["id"]);

        $dados["fotos"] = $fotos->lista_fotos_cliente($id);


        $this->view('header');
        $this->view('menu');
        $this->view('content_empresas_show', $dados);
        $this->view('footer');
    }

    public function adicionar_contrato() {
        $security = new securityHelper();
        $contrato = new Clientes_Contratos_Model();

        $id_contrato = $security->antiInjection(filter_input(INPUT_POST, "contrato"));
        $id_cliente = $security->antiInjection(filter_input(INPUT_POST, "cliente"));
        $data_emissao = $security->antiInjection(filter_input(INPUT_POST, "data_emissao"));


        print_r($contrato->adicionar($id_contrato, $id_cliente, $data_emissao));
    }

    public function remover_contrato() {
        $security = new securityHelper();
        $contrato = new Clientes_Contratos_Model();

        $id_contrato = $security->antiInjection(filter_input(INPUT_POST, "id_contrato"));
        print_r($contrato->remover($id_contrato));
    }

    public function adicionar_contrato_assinado() {
        $security = new securityHelper();
        $contrato = new Clientes_Contratos_Model();

        $id_contrato = $security->antiInjection(filter_input(INPUT_POST, "id_contrato"));
        $contrato_assinado = (isset($_FILES["contrato"]) ? $_FILES["contrato"] : NULL);

        print_r($contrato->adicionar_assinado($id_contrato, $contrato_assinado));
    }

    public function trocar_cliente() {
        $security = new securityHelper();
        $clients = new Clients_Model();
        $id = $security->antiInjection(filter_input(INPUT_POST, "id"));
        print_r($clients->trocar_cliente($id));
    }

    public function search_cliente() {
        $security = new securityHelper();
        $clientes = new Clients_Model();

        $pesquisa = $security->antiInjection(filter_input(INPUT_POST, "pesquisa"));
        $funcao = $security->antiInjection(filter_input(INPUT_POST, "funcao"));

        print_r($clientes->search_Empresas($pesquisa, $funcao));
    }

    public function search_cliente_boletos() {
        $security = new securityHelper();
        $clientes = new Clients_Model();

        $pesquisa = $security->antiInjection(filter_input(INPUT_POST, "pesquisa"));
        $funcao = $security->antiInjection(filter_input(INPUT_POST, "funcao"));

        print_r($clientes->search_Empresas_Boletos($pesquisa, $funcao));
    }

    public function preview_modal() {
        $security = new securityHelper();
        $clients = new Clients_Model();

        $id = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($clients->preview_modal($id));
    }

    public function rel_empresas() {

        $this->view('header');
        $this->view('menu');
        $this->view('content_rel_empresa');
        $this->view('footer');
    }

    public function rel_empre() {
        $clientes = new Clients_Model();
        $security = new securityHelper();
        $empresa = $security->antiInjection(filter_input(INPUT_POST, 'empresa'));
        $situacao = $security->antiInjection(filter_input(INPUT_POST, 'situacao'));
        $tipo = $security->antiInjection(filter_input(INPUT_POST, 'tipo'));
        $formato = $security->antiInjection(filter_input(INPUT_POST, 'formato'));
        $saida = $security->antiInjection(filter_input(INPUT_POST, 'saida'));

        if ($saida == 0 && $formato == 1) {
            $clientes->relEmpresa_simples_pdf($empresa, $situacao, $tipo);
            print_r("OK-PDF");
        } else if ($saida == 2 && $formato == 1) {
            $clientes->relEmpresa_simples_excel($empresa, $situacao, $tipo);
            print_r("OK-EXCEL");
        } else if ($saida == 1 && $formato == 1) {
            print_r($clientes->relEmpresa_simples_tela($empresa, $situacao, $tipo));
        } else if ($saida == 1 && $formato == 0) {
            print_r($clientes->relEmpresa_detalhado_tela($empresa, $situacao, $tipo));
        } else if ($saida == 0 && $formato == 0) {
            print_r($clientes->relEmpresa_detalhado_pdf($empresa, $situacao, $tipo));
            print_r("OK-PDF");
        } else if ($saida == 2 && $formato == 0) {
            $clientes->relEmpresa_detalhado_excel($empresa, $situacao, $tipo);
            print_r("OK-EXCEL");
        }
    }

}
