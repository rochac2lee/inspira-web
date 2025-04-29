@php $j=1; @endphp
@for($k=1;$k<=$p->qtd_alternativa;$k++)
    <div class="resposta" style="page-break-inside:avoid">
        <div class="letra">{{$alternativas[$j]}}</div>
        {!!$p->getAttribute('alternativa_'.$k);!!}
    </div>
    @php $j++; @endphp
@endfor

