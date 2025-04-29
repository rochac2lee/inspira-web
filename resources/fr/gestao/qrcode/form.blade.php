@extends('fr/master')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2.select2-container .select2-selection {
    border: 1px solid #ffb100;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    height: 39px;

    outline: none !important;
    transition: all .15s ease-in-out;
}
.select2.select2-container .select2-selection .select2-selection__rendered {
    color: #333;
    line-height: 32px;
    padding-right: 33px;
}
</style>
    <section class="section section-interna">
        <div class="container">
            <div class="row border-bottom">
                <div class="col-md-4 pb-2">
                    <h3>
                        <a href="{{ url('/gestao/qrcode')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        @if ( strpos(Request::path(),'edit')===false )Novo QRCode @else Editar QRCode @endif
                    </h3>
                </div>

            </div>
            <form action="" method="post">
            <div class="row">
                @csrf
                <input type="hidden" name="id" value="{{old('id',@$dados->id)}}">
                <div class="col-md-12 pt-4 pb-4 pl-4 pr-5">
                    <div class="form-group">
                        <label>* Descrição do QRCode:</label>
                        <input name="descricao" type="text" placeholder="" value="{{old('descricao',@$dados->descricao)}}" class="form-control rounded {{ $errors->has('descricao') ? 'is-invalid' : '' }}">
                        <div class="invalid-feedback">{{ $errors->first('descricao') }}</div>
                    </div>
                    <div class="form-group">
                        <label>* URL de destino:</label>
                        <input name="url" type="text" placeholder="" value="{{old('url',@$dados->url)}}" class="form-control rounded {{ $errors->has('url') ? 'is-invalid' : '' }}">
                        <div class="invalid-feedback">{{ $errors->first('url') }}</div>
                    </div>
                    <div class="form-group">
                        <label>Observações:</label>
                        <p style="color: red"><font size="-1">Campo complementar para descrição das páginas de aplicação, Coleções ou Etapa/Ano que compartilham o mesmo QRCode.</font></p>
                        <input name="observacao" type="text" placeholder="" value="{{old('observacao',@$dados->observacao)}}" class="form-control rounded {{ $errors->has('observacao') ? 'is-invalid' : '' }}">
                        <div class="invalid-feedback">{{ $errors->first('observacao') }}</div>
                    </div>
                    <div class="form-group">
                        <label>* Tipo de Mídia:</label>
                        <select id="tipo_midia" name="tipo_midia" class="form-control rounded">
                            <option value="Vídeo" @if(old('tipo_midia', isset($dados) ? $dados->tipo_midia : null) == 'Vídeo') selected @endif>Vídeo</option>
                            <option value="Áudio" @if(old('tipo_midia', isset($dados) ? $dados->tipo_midia : null) == 'Áudio') selected @endif>Áudio</option>
                            <option value="Jogo" @if(old('tipo_midia', isset($dados) ? $dados->tipo_midia : null) == 'Jogo') selected @endif>Jogo</option>
                            <option value="Quiz" @if(old('tipo_midia', isset($dados) ? $dados->tipo_midia : null) == 'Quiz') selected @endif>Quiz</option>
                            <option value="Site" @if(old('tipo_midia', isset($dados) ? $dados->tipo_midia : null) == 'Site') selected @endif>Site</option>
                            <option value="Gabarito" @if(old('tipo_midia', isset($dados) ? $dados->tipo_midia : null) == 'Gabarito') selected @endif>Gabarito</option>
                            <option value="Apresentação" @if(old('tipo_midia', isset($dados) ? $dados->tipo_midia : null) == 'Apresentação') selected @endif>Apresentação</option>
                            <option value="Simulador" @if(old('tipo_midia', isset($dados) ? $dados->tipo_midia : null) == 'Simulador') selected @endif>Simulador</option>
                            <option value="Documentos" @if(old('tipo_midia', isset($dados) ? $dados->tipo_midia : null) == 'Documentos') selected @endif>Documentos</option>
                            <option value="Livro Digital" @if(old('tipo_midia', isset($dados) ? $dados->tipo_midia : null) == 'Livro Digital') selected @endif>Livro Digital</option>
                            <option value="BNCC" @if(old('tipo_midia', isset($dados) ? $dados->tipo_midia : null) == 'BNCC') selected @endif>BNCC</option>
                            <option value="Imagem" @if(old('tipo_midia', isset($dados) ? $dados->tipo_midia : null) == 'Imagem') selected @endif>Imagem</option>
                            <option value="Prova" @if(old('tipo_midia', isset($dados) ? $dados->tipo_midia : null) == 'Prova') selected @endif>Prova</option>
                            <option value="RED" @if(old('tipo_midia', isset($dados) ? $dados->tipo_midia : null) == 'RED') selected @endif>RED</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>* Componente:</label>
                        <select id="disciplina" name="disciplina" class="form-control rounded">
                            @foreach($disciplinas as $disciplina)
                                <option value="{{$disciplina->id}}" @if(old('disciplina', isset($dados) ? $dados->disciplina_id : null) == $disciplina->id) selected @endif>{{$disciplina->titulo}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Coleção:</label>
                        <select id="colecaoLivros" name="colecaoLivros" class="form-control rounded">
                            <option value=""></option>
                            @foreach($colecaoLivros as $colecao)
                                <option value="{{$colecao->id}}" @if(old('colecaoLivros', isset($dados) ? $dados->colecao_livro_id : null) == $colecao->id) selected @endif>{{$colecao->nome}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>* Etapa/Ano:</label>
                        <select id="cicloEtapa" name="cicloEtapa" class="form-control rounded">
                            @foreach($cicloEtapa as $c)
                                <option value="{{$c->id}}" @if(isset($dados) && $c->id == $dados->ciclo_etapa_id) selected @endif>{{$c->ciclo}} / {{$c->ciclo_etapa}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 text-right pl-4 pr-5">
                    <a href="{{ url('/gestao/qrcode')}}" class="btn btn-secondary">Cancelar</a>
                    <button class="btn btn-success mt-0 ml-2">Salvar</button>
                </div>
            </div>
            </form>

        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
         $(document).ready(function() {
            $('#colecaoLivros').select2({
                placeholder: "Coleção",
                allowClear: true
            });
            $('#cicloEtapa').select2({
                placeholder: "Ciclo Etapa",
                allowClear: true
            });
            $('#disciplina').select2({
                placeholder: "Componente",
                allowClear: true
            });
            $('#tipo_midia').select2({
                placeholder: "Tipo Mídia",
                allowClear: true
            });
        });
    </script>
@stop
