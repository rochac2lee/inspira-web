<?php
namespace App\Services\Fr\Agenda;
use App\Library\Slim;
use App\Models\Escola;
use App\Models\FrAgendaCanaisAtendimento;
use App\Models\FrAgendaRegistroRotinaOpet;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Request;

class RegistroRotinaOpetService {

    public function lista($dados){
        $tipo = 1;
        if(isset($dados['tipo']) && $dados['tipo']!= '')
        {
            $tipo = $dados['tipo'];
        }
        return FrAgendaRegistroRotinaOpet::where('rotina',$tipo)
            ->orderByRaw('ISNULL(ordem), ordem ASC')
            ->orderBy('titulo')
            ->get();
    }

    public function excluir($id){
        DB::beginTransaction();
        try
        {
            $rotina = FrAgendaRegistroRotinaOpet::find($id);
            $rotina->delete();
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }



    public function getEditar($id){
        try {
            return FrAgendaRegistroRotinaOpet::find($id);
        }
        catch (\Exception $e)
        {
            return false;
        }


    }

	public function inserir($dados)
    {
        DB::beginTransaction();
        try
        {
            unset($dados['imagem']);
            $rotina = new FrAgendaRegistroRotinaOpet($dados);
            $rotina->save();
            $img = [
                'imagem' => $this->gravaImagem(),
            ];
            $rotina->update($img);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function update($dados)
    {
        DB::beginTransaction();
        try
        {
            $rotina = $this->getEditar($dados['id']);

            unset($dados['imagem']);


            if($dados['existeImg'] == ''){
                $dados['imagem'] = $this->gravaImagem();
            }
            $rotina->update($dados);
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    private function gravaImagem(){
        try {
            $images = Slim::getImages('imagem');
            if(count($images)>0)
            {

                $image = $images[0];
                // let's create some shortcuts
                $name = explode('.',$image['input']['name']);
                $ext = '.'.$name[count($name)-1];
                $data = $image['output']['data'];

                // store the file
                $file = Slim::saveFile($data, $ext,config('app.frTmp'));
                $fileName = $file['name'];

                $img = Image::make(config('app.frTmp').$fileName);
                $img->resize(150, 150, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('webp', 90);

                $resource = $img->stream()->detach();
                $fileName = $img->filename.'.webp';

                Storage::disk()->put(config('app.frStorage') .'agenda/registro/rotinas/'.$fileName, $resource);
                return $fileName;
            }
        }
        catch (\Exception $e)
        {
            return false;
        }
    }


    public function ordem($dados){
        try {
            DB::beginTransaction();
            foreach ($dados['ordem'] as $k => $v) {
                $rotina = [
                    'ordem' => $k
                ];

                $r = FrAgendaRegistroRotinaOpet::find($v);
                $r->update($rotina);
            }
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }

    }
    public function defineQtdRotinas(){
        $rotinas = FrAgendaRegistroRotinaOpet::groupBy('rotina')
                    ->orderBy('rotina')
                    ->selectRaw('count(id) as qtd, rotina')
                    ->get();
        $vetRotina = [
            '1' => ['id'=>1, 'qtd'=>0],
            '2' => ['id'=>2, 'qtd'=>0],
            '3' => ['id'=>3, 'qtd'=>0],
            '4' => ['id'=>4, 'qtd'=>0],
            '5' => ['id'=>5, 'qtd'=>0],
        ];
        foreach($rotinas as $r){
            $vetRotina[$r->rotina]['qtd'] = $r->qtd;
        }

        return $vetRotina;
    }

}
