<?php

namespace App\Repositories;

use App\Support\Repositories\BaseRepository;

class ListaSubstituicaoRepository extends BaseRepository
{
    public function getListSub($data)
    {
        if (array_key_exists('tipoLista', $data)){

            $tipoAlimento   = $data['tipoAlimento'];
            $qtdGrama       = $data['qtdGrama'];

            $sql = "SELECT
                        a.*,

                        CASE
                            WHEN a.TipoAlimentarID = 3 THEN ROUND(($qtdGrama * 100) / a.gordura, 2)
                            WHEN a.TipoAlimentarID = 2 THEN ROUND(($qtdGrama * 100) / a.carboidrato, 2)
                            WHEN a.TipoAlimentarID = 1 THEN ROUND(($qtdGrama * 100) / a.proteina, 2)
                        END qtd_gramas,

                        CASE
                            WHEN a.TipoAlimentarID = 3 THEN ROUND((($qtdGrama * 100) / a.gordura) * a.energia / 100, 2)
                            WHEN a.TipoAlimentarID = 2 THEN ROUND(($qtdGrama * 100) / a.carboidrato, 2)
                            WHEN a.TipoAlimentarID = 1 THEN ROUND(($qtdGrama * 100) / a.proteina, 2)
                        END caloria_kcal,

                        CASE
                            WHEN a.TipoAlimentarID = 3 THEN ROUND(((a.proteina * $qtdGrama) / a.gordura),2)
                            WHEN a.TipoAlimentarID = 2 THEN ROUND(((a.proteina * $qtdGrama) / a.carboidrato),2)
                            WHEN a.TipoAlimentarID = 1 THEN ROUND(((a.proteina * $qtdGrama) / a.proteina),2)
                        END proteina_calc,

                        CASE
                            WHEN a.TipoAlimentarID = 3 THEN ROUND(((a.carboidrato * $qtdGrama) / a.gordura),2)
                            WHEN a.TipoAlimentarID = 2 THEN ROUND(((a.carboidrato * $qtdGrama) / a.carboidrato),2)
                            WHEN a.TipoAlimentarID = 1 THEN ROUND(((a.carboidrato * $qtdGrama) / a.proteina),2)
                        END carboidrato_calc,

                        CASE
                            WHEN a.TipoAlimentarID = 3 THEN ROUND(((a.gordura * $qtdGrama) / a.gordura),2)
                            WHEN a.TipoAlimentarID = 2 THEN ROUND(((a.gordura * $qtdGrama) / a.carboidrato),2)
                            WHEN a.TipoAlimentarID = 1 THEN ROUND(((a.gordura * $qtdGrama) / a.proteina),2)
                        END gordura_calc,
                        tp.Preparo,
                        ta.NomeTipoAlimentar
                    FROM alimentos a
                    INNER JOIN tipo_preparo tp ON tp.id = a.TipoPreparoID
                    INNER JOIN tipo_alimentar ta ON ta.id = a.TipoAlimentarID
                    WHERE a.TipoAlimentarID = $tipoAlimento
                    ORDER BY a.NomeAlimento
            ";

        }else{

            $tipoMacro      = $data['tipoMacro'];
            $alimento       = $data['alimento'];
            $qtdGrama       = $data['qtdGrama'];
            $tipoAlimento   = $data['tipoAlimento'];

            $sql = "SELECT
                        a.*,
                        ROUND(((SELECT a1.ptn FROM alimentos a1 WHERE a1.id = $alimento) * $qtdGrama) /
                        (((SELECT a2.ptn FROM alimentos a2 WHERE a2.id = $alimento) * a.energia) /
                        (SELECT a3.energia FROM alimentos a3 WHERE a3.id = $alimento)), 2) substituicao,

                        ROUND((((SELECT a1.ptn FROM alimentos a1 WHERE a1.id = $alimento) * $qtdGrama) /
                        (((SELECT a2.ptn FROM alimentos a2 WHERE a2.id = $alimento) * a.energia) /
                        (SELECT a3.energia FROM alimentos a3 WHERE a3.id = $alimento)) * a.energia) / 100, 2) caloria_kcal,

                        ROUND(((SELECT a1.ptn FROM alimentos a1 WHERE a1.id = $alimento) * $qtdGrama) /
                        (((SELECT a2.ptn FROM alimentos a2 WHERE a2.id = $alimento) * a.energia) /
                        (SELECT a3.energia FROM alimentos a3 WHERE a3.id = $alimento)) * a.proteina / 100, 2) proteina_calc,

                        ROUND(((SELECT a1.ptn FROM alimentos a1 WHERE a1.id = $alimento) * $qtdGrama) /
                        (((SELECT a2.ptn FROM alimentos a2 WHERE a2.id = $alimento) * a.energia) /
                        (SELECT a3.energia FROM alimentos a3 WHERE a3.id = $alimento)) * a.carboidrato / 100, 2) carboidrato_calc,

                        ROUND(((SELECT a1.ptn FROM alimentos a1 WHERE a1.id = $alimento) * $qtdGrama) /
                        (((SELECT a2.ptn FROM alimentos a2 WHERE a2.id = $alimento) * a.energia) /
                        (SELECT a3.energia FROM alimentos a3 WHERE a3.id = $alimento)) * a.gordura / 100, 2) gordura_calc,

                        tp.Preparo
                    FROM alimentos a
                    INNER JOIN tipo_preparo tp ON tp.id = a.TipoPreparoID
                    WHERE a.TipoMacroID = $tipoMacro
                    AND a.TipoAlimentarID = $tipoAlimento
                    ORDER BY a.NomeAlimento
        ";
        }
        return $this->rawAsArray($this->raw($sql));
    }

}
