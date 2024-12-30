<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AguaController extends Controller
{
    public function calcularAgua(Request $request): JsonResponse
    {
        $peso = $request->input('peso');
        $altura = $request->input('altura');
        $idade = $request->input('idade');
        $sexo = $request->input('sexo');
        $tipoAtividade = $request->input('tipoAtividade');
        $horasAtividade = $request->input('horasAtividade');
        $clima = $request->input('clima');
        $cafeina = $request->input('cafeina');
        $alcool = $request->input('alcool');
        $horaAcordar = $request->input('horaAcordar');
        $horaDormir = $request->input('horaDormir');

        // Cálculo da ingestão básica de água (35 ml por kg)
        $agua = $peso * 35 / 1000;

        // Ajustes baseados em altura
        $agua += ($altura > 150) ? (($altura - 150) * 0.02) : 0;

        // Ajustes baseados no tipo de atividade física
        if ($tipoAtividade == 'Musculação') {
            $agua += 0.5;
        } elseif ($tipoAtividade == 'Aeróbica') {
            $agua += 1;
        }

        // Ajustes por horas de atividade física
        if ($horasAtividade) {
            $agua += $horasAtividade * 0.5;
        }

        // Ajustes por clima, cafeína e álcool
        if ($clima == 'Sim') $agua += 0.5;
        if ($cafeina == 'Sim') $agua += 0.5;
        if ($alcool == 'Sim') $agua += 0.5;

        // Calcular o número de copos de 500ml cada
        $numeroCopos = ceil(($agua * 1000) / 500);
        $mlPorCopo = 500;

        // Cálculo de horários
        $horaAcordar = explode(':', $horaAcordar);
        $horaDormir = explode(':', $horaDormir);

        $horaAcordarHoras = $horaAcordar[0];
        $horaAcordarMinutos = $horaAcordar[1];
        $horaDormirHoras = $horaDormir[0];
        $horaDormirMinutos = $horaDormir[1];

        // Cálculo do intervalo de tempo
        $totalHoras = ($horaDormirHoras + $horaDormirMinutos / 60) - ($horaAcordarHoras + $horaAcordarMinutos / 60);
        if ($totalHoras < 0) $totalHoras += 24; // Caso o horário de dormir

        // Caso o horário de dormir seja no dia seguinte
        $totalHoras = ($horaDormirHoras + $horaDormirMinutos / 60) - ($horaAcordarHoras + $horaAcordarMinutos / 60);
        if ($totalHoras < 0) $totalHoras += 24; // Ajusta se o horário de dormir é no dia seguinte

        // Cálculo do intervalo por copo (em horas)
        $intervaloPorCopo = $totalHoras / $numeroCopos;

        // Cálculo dos horários sugeridos para beber água
        $horarios = [];
        $horaAtual = $horaAcordarHoras + $horaAcordarMinutos / 60;

        for ($i = 0; $i < $numeroCopos; $i++) {
            $horario = date('H:i', mktime(floor($horaAtual), ($horaAtual - floor($horaAtual)) * 60));
            $horarios[] = $horario;
            $horaAtual += $intervaloPorCopo; // Adiciona o intervalo
        }

        // Formatar o resultado
        return response()->json([
            'totalAgua' => round($agua, 2),
            'numeroCopos' => $numeroCopos,
            'mlPorCopo' => $mlPorCopo,
            'intervaloPorCopo' => round($intervaloPorCopo, 2),
            'horarios' => $horarios,
        ]);
    }
}
