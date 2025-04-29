<div class="col-12 resposta" style="page-break-inside:avoid">
    @for($i = 0; $i < $p->qtd_linhas; $i++)
        @if($p->com_linhas == 1)
            <hr class="pt-2">
        @else
            <p>&nbsp;</p>
        @endif
    @endfor
</div>

