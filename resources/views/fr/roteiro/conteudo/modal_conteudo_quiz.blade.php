<div class="col-12 elementosConteudo" id="elementoConteudoQuiz" style="display: none">
   <div class="row">
       <div class="col-12">
           <div class="form-group">
               <label for="">* Sua pergunta: </label>
               <textarea rows="6" name="conteudo_8" id="froalaQuiz" class="form-control rounded">{{old('conteudo_8')}}</textarea>
               <div id="msgErroConteudo_8" class="invalid-feedback @if($errors->first('conteudo_8'))d-block @endif">{{ $errors->first('conteudo_8') }}</div>
               <div id="msgErroCorreta" class="invalid-feedback @if($errors->first('correta'))d-block @endif">{{ $errors->first('correta') }}</div>

           </div>
       </div>
   </div>
    <div class="row">
        <div class="col-1">
            <input class="alternativaCorreta" type="radio" value="1" name="correta">
        </div>
        <div class="col-11">
            <div class="form-group">
                <label for="">* Alternativa 1: </label>
                <textarea rows="6" name="alternativa_1" id="froalaAlternativa1" class="form-control rounded">{{old('alternativa_1')}}</textarea>
                <div id="msgErroAlternativa_1" class="invalid-feedback @if($errors->first('alternativa_1'))d-block @endif">{{ $errors->first('alternativa_1') }}</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-1">
            <input class="alternativaCorreta" type="radio" value="2" name="correta">
        </div>
        <div class="col-11">
            <div class="form-group">
                <label for="">* Alternativa 2: </label>
                <textarea rows="6" name="alternativa_2" id="froalaAlternativa2" class="form-control rounded">{{old('alternativa_2')}}</textarea>
                <div id="msgErroAlternativa_2" class="invalid-feedback @if($errors->first('alternativa_2'))d-block @endif">{{ $errors->first('alternativa_2') }}</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-1">
            <input class="alternativaCorreta" type="radio" value="3" name="correta">
        </div>
        <div class="col-11">
            <div class="form-group">
                <label for="">* Alternativa 3: </label>
                <textarea rows="6" name="alternativa_3" id="froalaAlternativa3" class="form-control rounded">{{old('alternativa_3')}}</textarea>
                <div id="msgErroAlternativa_3" class="invalid-feedback @if($errors->first('alternativa_3'))d-block @endif">{{ $errors->first('alternativa_3') }}</div>
            </div>
        </div>
    </div>
</div>


