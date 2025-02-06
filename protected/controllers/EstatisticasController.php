<?php

class EstatisticasController extends Controller
{
    public function filters()
    {
        return array('accessControl');
    }

    public function accessRules()
    {
        return array(
            array('allow', 'actions' => array('estatisticasMotorista'), 'users' => array('*')),
            array('deny', 'users' => array('*')),
        );
    }

    public function actionEstatisticasMotorista()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['motorista']['id']) || !isset($data['intervalo']['inicio']) || !isset($data['intervalo']['fim']) || !isset($data['periodicidade'])) {
            http_response_code(400);
            echo json_encode(['sucesso' => false, 'erros' => ['Campos obrigatórios ausentes']]);
            return;
        }

        $motoristaId = $data['motorista']['id'];
        $dataInicio = $data['intervalo']['inicio'];
        $dataFim = $data['intervalo']['fim'];
        $periodicidade = strtoupper($data['periodicidade']);

        $motorista = Motorista::model()->findByPk($motoristaId);
        if (!$motorista) {
            http_response_code(400);
            echo json_encode(['sucesso' => false, 'erros' => ['Motorista não existe']]);
            return;
        }

        // ❌ ERRO DE DATA INICIAL MAIOR QUE A FINAL
        if (strtotime($dataInicio) > strtotime($dataFim)) {
            http_response_code(400);
            echo json_encode(['sucesso' => false, 'erros' => ['A data inicial deve ser menor ou igual à data final']]);
            return;
        }

        // 🔹 SE PERÍODO FUTURO, TRUNCAR PARA HOJE
        $hoje = date('Y-m-d');
        if (strtotime($dataInicio) > strtotime($hoje) && strtotime($dataFim) > strtotime($hoje)) {
            $dataInicio = $hoje;
            $dataFim = $hoje;
        } elseif (strtotime($dataFim) > strtotime($hoje)) {
            $dataFim = $hoje;
        }

        // 🔹 VERIFICAR PERIODICIDADE
        switch ($periodicidade) {
            case 'D':
                $groupBy = 'DATE(data_inicio)';
                break;
            case 'S':
                $groupBy = 'YEAR(data_inicio), WEEK(data_inicio)';
                break;
            case 'M':
                $groupBy = 'YEAR(data_inicio), MONTH(data_inicio)';
                break;
            default:
                http_response_code(400);
                echo json_encode(['sucesso' => false, 'erros' => ['Periodicidade inválida']]);
                return;
        }

        // 🔹 CONSULTA SQL SEM ACTIVE RECORD
        $sql = "
            SELECT
                MIN(data_inicio) AS inicio,
                MAX(data_fim) AS fim,
                COUNT(*) AS quantidade,
                SUM(TIMESTAMPDIFF(MINUTE, data_inicio, data_fim)) AS duracao_minutos,
                SUM(tarifa) AS faturamento
            FROM corridas
            WHERE motorista_id = :motoristaId
              AND data_inicio BETWEEN :dataInicio AND :dataFim
            GROUP BY $groupBy
        ";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':motoristaId', $motoristaId, PDO::PARAM_INT);
        $command->bindParam(':dataInicio', $dataInicio, PDO::PARAM_STR);
        $command->bindParam(':dataFim', $dataFim, PDO::PARAM_STR);
        $results = $command->queryAll();

        // 🔹 SE NÃO HÁ DADOS, RETORNAR LISTA VAZIA + MENSAGEM
        $lista = [];
        foreach ($results as $row) {
            $duracaoHoras = floor($row['duracao_minutos'] / 60);
            $duracaoMinutos = $row['duracao_minutos'] % 60;

            $lista[] = [
                'intervalo' => [
                    'inicio' => $row['inicio'],
                    'fim' => $row['fim'],
                ],
                'estatistica' => [
                    'quantidade' => (int)$row['quantidade'],
                    'duracao' => "{$duracaoHoras} horas {$duracaoMinutos} minutos",
                    'faturamento' => number_format($row['faturamento'], 2, '.', ''),
                ],
            ];
        }

        if (empty($lista)) {
            http_response_code(200);
            echo json_encode([
                'sucesso' => true,
                'mensagem' => 'Nenhuma corrida encontrada no período especificado.',
                'motorista' => [
                    'id' => $motorista->id,
                    'nome' => $motorista->nome,
                ],
                'periodicidade' => $periodicidade,
                'lista' => []
            ]);
            return;
        }

        echo json_encode([
            'motorista' => [
                'id' => $motorista->id,
                'nome' => $motorista->nome,
            ],
            'periodicidade' => $periodicidade,
            'lista' => $lista,
        ]);
    }
}
