<?php

date_default_timezone_set('America/Sao_Paulo');


class ApiController extends Controller
{
    private function validarToken()
    {
        $headers = getallheaders();
        $token = trim(file_get_contents(Yii::app()->basePath . '/config/secret.txt'));
        if (!isset($headers['api-token']) || $headers['api-token'] !== $token) {
            header('Content-Type: application/json; charset=latin1');
            echo json_encode(['sucesso' => false, 'erros' => ['Token inválido']]);
            Yii::app()->end();
        }
    }

    public function actionSolicitarCorrida()
    {
        $this->validarToken();
        $data = json_decode(file_get_contents('php://input'), true);

        // 1️⃣ Validar passageiro
        $passageiro = Passageiro::model()->findByPk($data['passageiro']['id']);
        if (!$passageiro || $passageiro->status !== 'A') {
            echo json_encode(['sucesso' => false, 'erros' => ['Passageiro inválido ou inativo']]);
            return;
        }

        // Verificar se o passageiro já tem uma corrida em andamento
        $corridaPassageiro = Corrida::model()->find('passageiro_id=:id AND status=:status', [
            ':id' => $passageiro->id,
            ':status' => 'Em andamento'
        ]);
        if ($corridaPassageiro) {
            echo json_encode(['sucesso' => false, 'erros' => ['Passageiro já possui uma corrida em andamento']]);
            return;
        }

        // 2️⃣ Validar motorista
        $motorista = Motorista::model()->find(
            'status=:status AND id NOT IN (SELECT motorista_id FROM corridas WHERE status=:corrida_status)',
            [':status' => 'A', ':corrida_status' => 'Em andamento']
        );
        if (!$motorista) {
            echo json_encode(['sucesso' => false, 'erros' => ['Nenhum motorista disponível']]);
            return;
        }

        // 3️⃣ Validar endereço
        if ($data['origem']['endereco'] === $data['destino']['endereco']) {
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
            echo json_encode(['sucesso' => false, 'erros' => ['Origem e destino muito próximos']]);
            return;
        }

        // 5️⃣ Calcular duração e previsão de chegada
        $duracao_minutos = round($distancia / 0.2) + 3; // 1 minuto a cada 200 metros + 3 minutos fixos
        if ($duracao_minutos > 480) { // Máximo de 8 horas (480 minutos)
            echo json_encode(['sucesso' => false, 'erros' => ['Corrida excede o limite de 8 horas']]);
            return; 
        }

        // Calcular a previsão de chegada
        $previsao_chegada = date('Y-m-d H:i:s', strtotime("+$duracao_minutos minutes"));

        // Validação da previsão de chegada
        if (!$previsao_chegada || !strtotime($previsao_chegada)) {
            echo json_encode(['sucesso' => false, 'erros' => ['Erro ao calcular previsão de chegada']]);
            Yii::app()->end(); 
        }

        // 6️⃣ Calcular tarifa 'Possível erro de cálculo'
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
    }

    public function actionFinalizarCorrida()
    {
        $this->validarToken();
        $data = json_decode(file_get_contents('php://input'), true);

        // 1️⃣ Validar corrida
        $corrida = Corrida::model()->findByPk($data['corrida']['id']);
        if (!$corrida || $corrida->status !== 'Em andamento') {
            echo json_encode(['sucesso' => false, 'erros' => ['Corrida já finalizada ou não existe']]);
            return;
        }

        // 2️⃣ Validar motorista
        if ($corrida->motorista_id != $data['motorista']['id']) {
            echo json_encode(['sucesso' => false, 'erros' => ['Motorista não corresponde à corrida']]);
            return;
        }

        // 3️⃣ Finalizar corrida
        $corrida->status = 'Finalizada';
        $corrida->data_fim = date('Y-m-d H:i:s');
        $corrida->save();

        echo json_encode(['sucesso' => true]);
    }

    private function calcularDistancia($lat1, $lng1, $lat2, $lng2)
    {
        $r = 6371; // Raio da Terra em km
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $r * $c; // Distância em km
    }
}
