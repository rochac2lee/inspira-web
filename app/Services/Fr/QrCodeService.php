<?php
namespace App\Services\Fr;
use DB;
use App\Models\FrQrCode;
use App\Models\CicloEtapa;
use App\Models\ColecaoLivros;
use App\Models\Disciplina;
use Intervention\Image\Facades\Image;
use App\Http\Requests\Fr\QRCodeRequest;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Session;

class QrCodeService {

    public function lista($pesquisa = null)
    {
        $qrcode = FrQrCode::select('fr_qrcode.*', 'colecao_livros.nome AS nome_colecao', 'ciclo_etapas.titulo AS titulo_ciclo_etapa', 'disciplinas.titulo AS titulo_disciplina')
            ->leftJoin('colecao_livros', 'fr_qrcode.colecao_livro_id', '=', 'colecao_livros.id')
            ->leftJoin('ciclo_etapas', 'fr_qrcode.ciclo_etapa_id', '=', 'ciclo_etapas.id')
            ->leftJoin('disciplinas', 'fr_qrcode.disciplina_id', '=', 'disciplinas.id' )
            ->orderBy('fr_qrcode.id', 'desc');

        if (!empty($pesquisa['descricao'])) {
            $qrcode->where('fr_qrcode.descricao', 'like', '%' . $pesquisa['descricao'] . '%');
        }

        if (!empty($pesquisa['url'])) {
            $qrcode->where('fr_qrcode.url', 'like', '%' . $pesquisa['url'] . '%');
        }

        if (!empty($pesquisa['chave'])) {
            $qrcode->where('fr_qrcode.chave', 'like', '%' . $pesquisa['chave'] . '%');
        }

        if(!empty($pesquisa['observacao'])){
            $qrcode->where('fr_qrcode.observacao', 'like', '%' . $pesquisa['observacao'] . '%');
        }

        if (!empty($pesquisa['tipo_midia'])) {
            $qrcode->where('fr_qrcode.tipo_midia', 'like', '%' . $pesquisa['tipo_midia'] . '%');
        }

        if(!empty($pesquisa['disciplina'])){
            $qrcode->where('fr_qrcode.disciplina_id', $pesquisa['disciplina']);
        }

        if (!empty($pesquisa['colecaoLivros'])) {
            $qrcode->where('fr_qrcode.colecao_livro_id', $pesquisa['colecaoLivros']);
        }

        if (!empty($pesquisa['cicloEtapa'])) {
            $qrcode->where('fr_qrcode.ciclo_etapa_id', $pesquisa['cicloEtapa']);
        }



        $qrcode = $qrcode->paginate(20);

        return $qrcode;
    }

    public function listaDisciplina(){
        return Disciplina::all();
    }

    public function cicloEtapaList()
    {
        return CicloEtapa::join('ciclos', 'ciclos.id', 'ciclo_etapas.ciclo_id')
                ->orderBy('ciclos.titulo')
                ->orderBy('ciclo_etapas.titulo')
                ->selectRaw('ciclo_etapas.id, ciclos.titulo as ciclo, ciclo_etapas.titulo as ciclo_etapa')
                ->get();
    }

    public function listaColecaoLivros()
    {
        return ColecaoLivros::where('tipo', 21)->get();
    }

    public function inserir(array $dados)
    {
        DB::beginTransaction();
        try {
            //Cria uma hash de 6 caracteres alfanumérico para a chave
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $hashLength = 6;
            //Verifica se a chave já existe
            do {
                $hash = '';
                for ($i = 0; $i < $hashLength; $i++) {
                    $hash .= $characters[mt_rand(0, $charactersLength - 1)];
                }
            } while (FrQrCode::where('chave', $hash)->exists());

            $dados['chave'] = $hash;

            FrQrCode::create($dados);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
    }

    public function editar($dados)
    {
        Session::forget('old');
        DB::beginTransaction();
        try {
            $qr = FrQrCode::find($dados['id']);

            // Verifique se os valores das chaves estrangeiras existem nas tabelas relacionadas
            if (ColecaoLivros::where('id', $dados['colecaoLivros'])->exists() &&
                CicloEtapa::where('id', $dados['cicloEtapa'])->exists() &&
                Disciplina::where('id', $dados['disciplina'])){

                // Atualize os campos de chave estrangeira
                $qr->colecao_livro_id = $dados['colecaoLivros'];
                $qr->ciclo_etapa_id = $dados['cicloEtapa'];
                $qr->disciplina_id = $dados['disciplina'];

                $qr->update($dados);
                DB::commit();
                return true;
            } else {
                DB::rollback();
                return 'Valores de chaves estrangeiras inválidos.';
            }
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
    }


    public function deletar($id){
        DB::beginTransaction();
        try
        {
            $qr = FrQrCode::find($id);
            $qr->delete();
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e;
        }

    }

    public function info($id){
        $qr = FrQrCode::find($id);
        return $qr;
    }
        
    public function imagemQrCode($id){
        $qr = FrQrCode::find($id);
        $fileName = $qr->id.'.svg';
        if (!Storage::disk()->exists(config('app.frStorage') . 'qrcode/' .$fileName)) {
            $caminho = config('app.frTmp') . '/' . $fileName;
            QrCode::size(600)
                ->margin(2)
                ->generate($qr->codigo_url, $caminho);

            Storage::disk()->put(config('app.frStorage') . 'qrcode/' . $fileName, file_get_contents($caminho));
        }

        return config('app.frStorage') . 'qrcode/' .$fileName;
    }
    
}
