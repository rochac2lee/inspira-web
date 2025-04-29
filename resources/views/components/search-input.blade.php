<form id="formPesquisa" action="{{ isset($action) ? $action : '' }}" method="get" class="{{ isset($class) ? $class : '' }} h-100 w-100 px-0 sm-mb-2">
    <div class="input-group search-div-input formPesquisarUserManagement">
        <input name="pesquisa" type="text" value="{{ Request::has('pesquisa') ? Request::get('pesquisa') : '' }}" required 
        class="form-control search-input"
            placeholder="{{ $placeholder }}" maxlength="80"
            aria-label="Recipient's username" aria-describedby="button-addon2">

        <div class="input-group-append search-div-icon" type="submit" onclick="submitPesquisa()">
            <div class="bg-white d-flex align-items-center" id="button-addon2">
                <span class="fa-stack fa-1x mr-3 search-icon-background">
                    <i class="fas fa-circle fa-stack-2x"></i>
                    <i class="fas fa-search fa-stack-1x search-icon"></i>
                </span>
            </div>
        </div>
    </div>
    @if(request('disciplina'))<input type="hidden" name="disciplina" value="{{ request('disciplina') }}">@endif
    @if(request('ciclo'))<input type="hidden" name="ciclo" value="{{ request('ciclo') }}">@endif
    @if(request('catalogo'))<input type="hidden" name="catalogo" value="{{ request('catalogo') }}">@endif
    @if(isset($hasFilters))<small class="float-right mt-2" id="clean-filters">
        <a href="#">Limpar filtros</a>
    </small>
    @endif
    @if(isset($filtro_status) && ($filtro_status))
    <select name="status" class="form-control rounded filtroPesquisarUserManagement">
        <option value="any">Todos</option>
        <option value="1" @if(request('status') == 1)selected="selected" @endif>Ativos</option>
        <option value="2" @if(request('status') == 2)selected="selected" @endif>Desativados</option>
    </select>
    @endif
</form>


<script>

    function submitPesquisa() {
        $('#formPesquisa').submit();
    }

</script>