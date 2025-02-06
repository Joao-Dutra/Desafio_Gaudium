<?php

date_default_timezone_set('America/Sao_Paulo');

class ApiController extends Controller
{
    private function validarToken()
    {
        $headers = getallheaders();
        $token = trim(file_get_contents(Yii::app()->basePath . '/config/secret.txt'));
        if (!isset($headers['api-token']) || $headers['api-token'] !== $token) {
            header('Content-Type: application/json; charset=latin1', true, 401);
            echo json_encode(['sucesso' => false, 'erros' => ['Token inválido']]);
            Yii::app()->end();
        }
    }

    public function actionSolicitarCorrida()
    {
        $this->validarToken();
        $data = json_decode(file_get_contents('php://input'), true);

        header('Content-Type: application/json; charset=utf-8');

        try {
            // 1️⃣ Validar passageiro
            $passageiro = Passageiro::model()->findByPk($data['passageiro']['id']);
            if (!$passageiro || $passageiro->status !== 'A') {
                http_response_code(400);
                echo json_encode(['sucesso' => false, 'erros' => ['Passageiro inválido ou inativo']]);
                return;
            }

            // Verificar se o passageiro já tem uma corrida em andamento
            $corridaPassageiro = Corrida::model()->find('passageiro_id=:id AND status=:status', [
                ':id' => $passageiro->id,
                ':status' => 'Em andamento'
            ]);
            if ($corridaPassageiro) {
                http_response_code(400);
                echo json_encode(['sucesso' => false, 'erros' => ['Passageiro já possui uma corrida em andamento']]);
                return;
            }

            // 2️⃣ Validar motorista
            $motorista = Motorista::model()->find(
                'status=:status AND id NOT IN (SELECT motorista_id FROM corridas WHERE status=:corrida_status)',
                [':status' => 'A', ':corrida_status' => 'Em andamento']
            );
            if (!$motorista) {
                http_response_code(400);
                echo json_encode(['sucesso' => false, 'erros' => ['Nenhum motorista disponível']]);
                return;
            }

            // 3️⃣ Validar endereço
            if ($data['origem']['endereco'] === $data['destino']['endereco']) {
                http_response_code(400);
                echo json_encode(['sucesso' => false, 'erros' => ['Endereço de origem e destino devem ser diferentes']]);
                return;
            }

            // 4️⃣ Calcular distância
            $distancia = $this->calcularDistancia(
                $data['origem']['lat'],
                $data['origem']['lng'],
                $data['destino']['lat'],
                $data['destino']['lng']
            );
            if ($distancia < 0.1) {
                http_response_code(400);
                echo json_encode(['sucesso' => false, 'erros' => ['Origem e destino muito próximos']]);
                return;
            }

            // 5️⃣ Calcular duração e previsão de chegada
            $duracao_minutos = round($distancia / 0.2) + 3;
            if ($duracao_minutos > 480) {
                http_response_code(400);
                echo json_encode(['sucesso' => false, 'erros' => ['Corrida excede o limite de 8 horas']]);
                return;
            }

            $previsao_chegada = date('Y-m-d H:i:s', strtotime("+$duracao_minutos minutes"));

            if (!$previsao_chegada || !strtotime($previsao_chegada)) {
                http_response_code(500);
                echo json_encode(['sucesso' => false, 'erros' => ['Erro ao calcular previsão de chegada']]);
                return;
            }

            // 6️⃣ Calcular tarifa
            $tarifa = round((2.0 * $distancia) + (0.5 * $duracao_minutos) + 5.0, 2);

            // 7️⃣ Criar a corrida
            $corrida = new Corrida();
            $corrida->passageiro_id = $passageiro->id;
            $corrida->motorista_id = $motorista->id;
            $corrida->origem_endereco = $data['origem']['endereco'];
            $corrida->destino_endereco = $data['destino']['endereco'];
            $corrida->data_inicio = date('Y-m-d H:i:s');
            $corrida->status = 'Em andamento';
            $corrida->previsao_chegada_destino = $previsao_chegada;
            $corrida->tarifa = $tarifa;
            $corrida->save();

            // Responder com sucesso
            http_response_code(200);
            echo json_encode([
                'sucesso' => true,
                'corrida' => [
                    'id' => $corrida->id,
                    'previsao_chegada_destino' => $previsao_chegada,
                    'tarifa' => number_format($tarifa, 2, '.', '')
                ],
                'motorista' => [
                    'nome' => trim($motorista->nome),
                    'placa' => $motorista->placa,
                    'quantidade_corridas' => Corrida::model()->count('motorista_id=:id', [':id' => $motorista->id])
                ]
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['sucesso' => false, 'erros' => ['Erro interno no servidor']]);
        }
    }

    public function actionFinalizarCorrida()
    {
        $this->validarToken();
        $data = json_decode(file_get_contents('php://input'), true);

        header('Content-Type: application/json; charset=utf-8');

        // 1️⃣ Validar corrida
        $corrida = Corrida::model()->findByPk($data['corrida']['id']);

        if (!$corrida) {
            // Caso a corrida não exista
            http_response_code(400);
            echo json_encode(['sucesso' => false, 'erros' => ['Corrida não existe']]);
            return;
        }

        if ($corrida->status !== 'Em andamento') {
            // Caso a corrida já tenha sido finalizada
            http_response_code(400);
            echo json_encode(['sucesso' => false, 'erros' => ['Corrida já finalizada']]);
            return;
        }


        // 2️⃣ Validar motorista
        if ($corrida->motorista_id != $data['motorista']['id']) {
            http_response_code(400);
            echo json_encode(['sucesso' => false, 'erros' => ['Motorista não corresponde à corrida']]);
            return;
        }

        // 3️⃣ Finalizar corrida
        $corrida->status = 'Finalizada';
        $corrida->data_fim = date('Y-m-d H:i:s');
        $corrida->save();

        http_response_code(200);
        echo json_encode(['sucesso' => true]);
    }

    private function calcularDistancia($lat1, $lng1, $lat2, $lng2)
    {
        $r = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $r * $c;
    }
}
