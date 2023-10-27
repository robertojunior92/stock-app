<?php

namespace App\Support\Controllers;

use App\Support\Controllers\BaseController;
use Illuminate\Support\Facades\View;

class AdminBaseController extends BaseController
{
    protected $permission;

    private function isLogged()
    {
        return session()->get('id') != false;
    }

    public function __construct()
    {
        // Sharing is caring

        if ($this->isLogged()) {
            View::share('menuTabs', $this->getMenuTabs());

            View::composer('*', function ($view) {
                $view_name = str_replace('.', '-', $view->getName());
                $view_name = explode("::", $view_name);

                if (isset($view_name[0])) {
                    View::share('view_name', $view_name[0]);
                } else {
                    View::share('view_name', "");
                }
            });

        }
    }
    public function getMenuTabs()
    {

        $rulesPermission = [
            "Banco de dados" => [
                "icon" => "",
                "tabs" => [
                    "Backup Banco de Dados" => [
                        "route" => "backup-database",
                        "icon" => "download"
                    ],
                    "Formulários" => [
                        "route" => "forms",
                        "icon" => "list"
                    ],
                    "Upload Banco de Dados" => [
                        "route" => "upload-database",
                        "icon" => "upload"
                    ],
                    "Gerenciar Banco de Dados" => [
                        "route" => "manage-database",
                        "icon" => "database"
                    ],
                    "Gerenciar Data Warehouse" => [
                        "route" => "manage-data-warehouse",
                        "icon" => "warehouse"
                    ],
                ],
            ],
            "Desenvolvimento" => [
                "icon" => "",
                "tabs" => [
                    "Endpoints Debugger" => [
                        "route" => "endpoints-debugger",
                        "icon" => "bug"
                    ],
                    "Status API" => [
                        "route" => "public-api",
                        "icon" => "chart-line"
                    ],
                    "Versões" => [
                        "route" => "versions",
                        "icon" => "code-branch"
                    ],
                    "Solicitar Acesso" => [
                        "route" => "manage-password-master",
                        "icon" => "fal fa-key"
                    ]
                ]
            ],
            "Produto" => [
                "icon" => "",
                "tabs" => [
                    "Autorizador TISS" => [
                        "route" => "autorizador-configuracao",
                        "icon" => "fal fa-fingerprint"
                    ],
                    "Integração Doctoralia" => [
                        "route" => "doctoralia-integration",
                        "icon" => "doctoralia",
                        "is_new" => true
                    ],
                    "Exames (Int. Lab)" => [
                        "route" => "lista-exames",
                        "icon" => "fa fa-flask"
                    ],
                    // "Permissões (Em Breve)" => [
                    //     "route" => "xxx",
                    //     "icon" => "fa fa-flask",
                    // ],
                    // "Recursos (Em Breve)" => [
                    //     "route" => "xxx",
                    //     "icon" => "fa fa-flask",
                    // ],
                    // "Configurações (Em Breve)" => [
                    //     "route" => "xxx",
                    //     "icon" => "fa fa-flask",
                    // ]
                ]
            ],
            "Financeiro / Administrativo" => [
                "icon" => "",
                "tabs" => [
                    "Planos" => [
                        "route" => "plans",
                        "icon" => "solar-panel"
                    ],
                    "Licenças" => [
                        "route" => "licenses",
                        "icon" => "hospital"
                    ],
                    "Permissão Admin" => [
                        "route" => "permission-admin",
                        "icon" => "fal fa-edit"
                    ],
                    "Usuários" => [
                        "route" => "users",
                        "icon" => "users"
                    ],
                    "Mensageria" => [
                        "route" => "messaging",
                        "icon" => "chart-bar"
                    ],
                    "Reajuste" => [
                        "route" => "readjustment",
                        "icon" => "sliders-h"
                    ],
                    "Marketplace" => [
                        "route" => "marketplace",
                        "icon" => "shopping-cart"
                    ],
                    "Marketplaces Pendentes" => [
                        "route" => "marketplace-pendentes",
                        "icon" => "shopping-cart"
                    ]
                ]
            ],
            "Sucesso" => [
                "icon" => "",
                "tabs" => [
                    "Help Desk" => [
                        "route" => "help-desk",
                        "icon" => "question-circle"
                    ]
                ]
            ],
            "Marketing" => [
                "icon" => "",
                "tabs" => [
                    "Novidades" => [
                        "route" => "news-admin",
                        "icon" => "newspaper"
                    ],

                    "Comunicados" => [
                        "route" => "marketing-campaign",
                        "icon" => "megaphone"
                    ]
                ]
            ],
            "Comercial" => [
                "icon" => "",
                "tabs" => [
                    "Vendas" => [
                        "route" => "sales",
                        "icon" => "dollar-sign"
                    ],
                    "Implantação" => [
                        "route" => "implementation",
                        "icon" => "sign-in-alt"
                    ],
                    "Serviços Adicionais" => [
                        "route" => "extra-services-info",
                        "icon" => "question-circle"
                    ]
                ]
            ],
            "Profissionais" => [
                "icon" => "",
                "tabs" => [
                    "Memed" => [
                        "route" => "memed",
                        "icon" => "notes-medical"
                    ]
                ]
            ]
        ];

        $this->permission = session()->get('permissions');

        foreach ($this->permission as $key => $permission) {
            if ($permission["Visualizar"] == "N") {
                unset($this->permission[$key]);
            }
        }

        foreach ($rulesPermission as $keys => $item) {
            $unpermission = array_diff_key($item['tabs'], $this->permission);
            foreach ($unpermission as $key => $item) {
                unset($rulesPermission[$keys]['tabs'][$key]);
            }
            if (empty($rulesPermission[$keys]['tabs'])) {
                unset($rulesPermission[$keys]);
            }
        }

        return $rulesPermission;
    }

    public function getPermission()
    {
        $route = $this->getMenuTabs();
        $tabs = current($route)['tabs'];
        if (is_array($tabs)) {
            $route = current($tabs)['route'];
        }

        return $route;
    }

}
