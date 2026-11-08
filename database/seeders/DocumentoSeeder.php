<?php

namespace Database\Seeders;

use App\Models\Documento;
use App\Models\FonteDocumento;
use App\Models\Prova;
use App\Models\Instituicao;
use Illuminate\Database\Seeder;

/**
 * DocumentoSeeder
 *
 * Registra as fontes oficiais dos documentos das provas SIS/UEA e PSC/UFAM.
 *
 * FONTES OFICIAIS (Google Drive):
 *   PSC/UFAM: https://drive.google.com/drive/folders/1BWvhgtRPzvSJ-6kkGuZohAzrEzfNVdQg
 *   SIS/UEA:  https://drive.google.com/drive/folders/1zj3y07rGTJli6nVw_EEpRntV6DSXewlW
 *
 * IMPORTANTE: Este seeder registra apenas documentos com URLs verificáveis.
 * Não inventa URLs nem cria registros sem fonte real.
 * Para adicionar novos documentos, use o painel admin → Provas → Documentos.
 */
class DocumentoSeeder extends Seeder
{
    // URLs das pastas raiz no Google Drive (fontes oficiais)
    private const DRIVE_PSC = 'https://drive.google.com/drive/folders/1BWvhgtRPzvSJ-6kkGuZohAzrEzfNVdQg';
    private const DRIVE_SIS = 'https://drive.google.com/drive/folders/1zj3y07rGTJli6nVw_EEpRntV6DSXewlW';

    public function run(): void
    {
        $uea  = Instituicao::where('sigla', 'UEA')->first();
        $ufam = Instituicao::where('sigla', 'UFAM')->first();

        if (!$uea || !$ufam) {
            $this->command->warn('Instituições não encontradas. Execute InstituicaoSeeder primeiro.');
            return;
        }

        // ─── Documento já cadastrado manualmente (PSC 2025 — PSC 1) ──────
        // Garante que a fonte está registrada para o documento existente
        $docExistente = Documento::where('prova_id', function ($q) use ($ufam) {
            $q->select('id')->from('provas')
              ->where('instituicao_id', $ufam->id)
              ->where('ano', 2025)
              ->where('etapa', 'PSC 1')
              ->limit(1);
        })->first();

        if ($docExistente && $docExistente->fontes()->count() === 0) {
            FonteDocumento::create([
                'documento_id'  => $docExistente->id,
                'descricao'     => 'Google Drive oficial PSC/UFAM',
                'url'           => self::DRIVE_PSC,
                'data_obtencao' => '2025-01-01',
                'verificado'    => false,
                'observacoes'   => 'Pasta raiz do Drive PSC/UFAM. URL do arquivo individual a confirmar.',
            ]);
        }

        $this->command->info('DocumentoSeeder concluído.');
        $this->command->line('');
        $this->command->line('BLOQUEIO EXTERNO — Acesso ao Google Drive:');
        $this->command->line('  PSC/UFAM: ' . self::DRIVE_PSC);
        $this->command->line('  SIS/UEA:  ' . self::DRIVE_SIS);
        $this->command->line('');
        $this->command->line('Para cadastrar documentos das provas:');
        $this->command->line('  1. Acesse as pastas acima e obtenha as URLs individuais de cada PDF.');
        $this->command->line('  2. No painel admin, acesse Provas → selecione a prova → adicione o documento.');
        $this->command->line('  3. Informe o tipo (prova/gabarito/resolucao), a URL externa e as observações.');
        $this->command->line('');
        $this->command->line('Provas 2020-2026 aguardando documentos: ' .
            Prova::whereIn('instituicao_id', [$uea->id, $ufam->id])
                ->where('ano', '>=', 2020)
                ->whereDoesntHave('documentos')
                ->count()
        );
    }
}
